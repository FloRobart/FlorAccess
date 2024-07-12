<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Schedule;


class ResetPasswordController extends Controller
{
    /**
     * 1
     * Affiche le formulaire de demande de réinitialisation du mot de passe
     */
    public function emailRequest()
    {
        return view('reset_password.emailRequest');
    }


    /**
     * 2
     * Génère le token et
     * Envoie le mail de réinitialisation du mot de passe
     */
    protected function emailRequestSave(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Veuillez saisir une adresse mail.',
            'email.email' => 'Veuillez saisir une adresse mail valide.',
            'email.exists' => 'Cette adresse mail ne correspond pas à votre compte.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        // TODO : mettre back() à la place de redirect()->route('resetPassword.emailRequest')
        return $status === Password::RESET_LINK_SENT
                    ? back()->with('success', 'Un mail de réinitialisation du mot de passe vous a été envoyé à l\'adresse mail' . $request->only('email') . '. Vous pouvez fermé cette page et cliquer sur le lien du mail pour réinitialiser votre mot de passe.')
                    : back()->with('error', 'Une erreur est survenue lors de l\'envoi du mail de réinitialisation du mot de passe.');
    }


    /**
     * 3
     * Affiche le formulaire de réinitialisation du mot de passe
     */
    public function resetPassword(String $token)
    {
        /* Récupération de l'adresse mail */
        $email = request()->query('email');
        $user = User::where('email', $email)->first();

        /* Vérification du token */
        if ($user != null && Password::tokenExists($user, $token)) {
            return view('reset_password.resetPassword', ['token' => $token, 'email' => $email]);
        } else {
            return redirect()->route('accueil');
        }
    }


    /**
     * 4
     * Réinitialise le mot de passe
     */
    public function resetPasswordSave(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:4|max:20|confirmed',
            'password_confirmation' => 'required|min:4|max:20|same:password',
        ], [
            'token.required' => 'Le token est requis.',
            'email.required' => 'L\'adresse mail du votre compte est requise.',
            'email.email' => 'L\'adresse mail du votre compte est requise.',
            'email.exists' => 'L\'adresse mail du votre compte est requise.',
            'password.required' => 'Veuillez saisir un nouveau mot de passe.',
            'password.min' => 'Le mot de passe doit contenir au moins 4 caractères.',
            'password.max' => 'Le mot de passe ne peux pas contenir plus de 20 caractères',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password_confirmation.required' => 'Veuillez confirmer le mot de passe.',
            'password_confirmation.min' => 'Le mot de passe de confirmation doit contenir au moins 4 caractères.',
            'password_confirmation.max' => 'Le mot de passe de confirmation ne peux pas contenir plus de 20 caractères',
            'password_confirmation.same' => 'Les mots de passe ne correspondent pas.',
        ]);

        $email = $request->email;
        $user  = User::where('email', $email)->first();
        $token = $request->token;

        if ($user != null && !(Password::tokenExists($user, $token))) {
            return redirect()->route('accueil');
        }
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );

        Schedule::command('auth:clear-resets')->everyFifteenMinutes();
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('accueil')->with('success', 'Votre mot de passe a été réinitialisé avec succès.')
                    : back()->with('error', 'Une erreur est survenue lors de la réinitialisation de votre mot de passe.');
    }
}

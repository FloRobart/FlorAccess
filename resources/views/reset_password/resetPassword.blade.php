{{--
 * Ce fichier fait partie du projet FlorAccess
 * Copyright (C) 2024 Floris Robart <florobart.github@gmail.com>
--}}

<!-- Page de réinitialisation de mot de passe -->
@extends('layouts.page_template')
@section('title')
    Demande de réinitialisation de mot de passe
@endsection

@section('content')
<!-- Titre de la page -->
@include('components.page-title', ['title' => 'Demande de réinitialisation de mot de passe'])

<!-- Messages d'erreur et de succès -->
<div class="colCenterContainer mt-8 px-4">
    @include('components.information-message')
</div>

<!-- Formulaire de demande de réinitialisation de mot de passe -->
<section class="bgPage py-6 lg:py-12 px-4 mx-auto max-w-screen-md">
        <form action="{{ route('password.reset.save') }}" method="POST" class="space-y-10">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ $token }}">
            <!-- Adresse email -->
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- Mot de passe -->
            <div>
                @include('components.password-input', ['confirmation' => 'false', 'newPassword' => 'true'])

                <!-- Suggestions de mot de passe -->
                <div class="smallRowEndContainer">
                    <span class="normalTextBleuLogo font-bold hoverText cursor-pointer" onclick="passwordGenerator()">Suggestion de mot de passe sécurisé</a>
                </div>
            </div>

            <!-- Confirmation du mot de passe -->
            <div>
                @include('components.password-input', ['confirmation' => 'true', 'newPassword' => 'true'])
            </div>

            <!-- bouton de validation -->
            <div class="smallRowStartContainer">
                <button type="submit" class="buttonForm">Changer mon mot de passe</button>
            </div>
        </form>

        <!-- précision -->
        <div class="smallRowStartContainer mt-3">
            @include('components.asterisque')
            <span class="smallText ml-1">Champs obligatoires</span>
        </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/showPassword.js') }}"></script>
@endsection
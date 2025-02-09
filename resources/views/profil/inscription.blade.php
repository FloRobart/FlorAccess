{{--
 * Ce fichier fait partie du projet FlorAccess
 * Copyright (C) 2024 Floris Robart <florobart.github@gmail.com>
--}}

<!-- Page d'incription -->
@extends('layouts.page_template')
@section('title')
    Inscription
@endsection

@section('content')
<!-- Titre de la page -->
@include('components.page-title', ['title' => 'Inscription'])

<!-- Messages d'erreur et de succès -->
<div class="colCenterContainer mt-8 px-4">
    @include('components.information-message')
</div>

<!-- Formulaire d'inscription -->
<section class="bgPage">
    <div class="py-6 lg:py-12 px-4 mx-auto max-w-screen-md">
        <form action="{{ route('inscriptionSave') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
            @csrf
            <!-- Nom -->
            <div>
                <label for="name" class="labelForm">Prénom @include('components.asterisque')</label>
                <input name="name" type="text" id="name" minlength="3" maxlength="18" autofocus autocomplete="off" class="inputForm" placeholder="Prénom" required value="{{ old('name') }}">
            </div>

            <!-- Adresse email -->
            <div>
                <label for="email" class="labelForm">Adresse email @include('components.asterisque')</label>
                <input name="email" type="email" id="email" autocomplete="email" class="inputForm" placeholder="nom@mail.com" required value="{{ old('email') }}">
            </div>

            <!-- Mot de passe -->
            <div>
                @include('components.password-input', ['confirmation' => 'false', 'newPassword' => 'true'])

                <!-- Suggestions de mot de passe -->
                <div class="flex items-center justify-end">
                    <span class="font fontSizeSmall colorFontBleuLogo font-bold hover:underline cursor-pointer" onclick="passwordGenerator()">Suggestion de mot de passe sécurisé</a>
                </div>
            </div>

            <!-- Confirmation du mot de passe -->
            <div>
                @include('components.password-input', ['confirmation' => 'true', 'newPassword' => 'true'])
            </div>

            <!-- Photo de profil -->
            <div>
                <label for="profil_image" class="labelForm">Photo de profil @include('components.asterisque')</label>
                <div class="flex flex-col md:flex-row items-center sm:items-start md:items-center md:space-x-6">
                    <div class="shrink-0">
                        <img id='preview_img' class="bigIcons object-cover rounded-xl" src="{{ asset('img/profil_image/profil_placeholder.jpg') }}" alt="Photo de profil" />
                    </div>
                    <span class="sr-only">Choisir une photo de profil</span>
                    <input type="file" id="profil_image" name="profil_image" accept="image/*" autocomplete="off" onchange="loadFile(event)" class="block w-full font fontSizeSmall colorFont file:text-white mt-4 md:mt-0 file:py-2 file:px-5 file:rounded-full file:border-0 file:bg-[#3232FF] hover:file:bg-[#0000CC] hover:file:cursor-pointer focus:file:scale-105"/>
                </div>
            </div>

            <!-- Acceptation des CGU -->
            <div class="smallColStartContainer">
                <div class="rowStartContainer">
                    <input type="checkbox" id="cgu" name="cgu" required class="w-5 h-5 cursor-pointer">
                    <label for="cgu" class="labelForm ml-4">J'accepte les <a href="{{ route('cgu') }}" class="normalTextBleuLogo link">CGU</a> @include('components.asterisque')</label>
                </div>

                <div class="rowStartContainer ml-10">
                    <ul class="list-disc ml-8">
                        <li class="tinyText">Données sensible sécurisé</li>
                        <li class="tinyText">Aucune collecte, utilisation ou revente de données personnel</li>
                        <li class="tinyText">Pas de publicité</li>
                        <li class="tinyText">Aucun cookie</li>
                    </ul>
                </div>
            </div>

            <!-- bouton d'inscription -->
            <div class="smallRowStartContainer">
                <button type="submit" class="buttonForm">Inscription</button>
            </div>
        </form>

        <!-- précision -->
        <div class="smallRowStartContainer mt-3">
            @include('components.asterisque')
            <span class="smallText ml-1">Champs obligatoires</span>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/passwordGenerator.js') }}"></script>
<script src="{{ asset('js/showPassword.js') }}"></script>

<!-- Script pour afficher l'image de profil -->
<script>
    var loadFile = function(event)
    {
        var input = event.target;
        var file = input.files[0];
        var type = file.type;
        var previewImage = document.getElementById('preview_img');
        

        if (file === undefined)
        {
            previewImage.src = "{{ asset('img/product_placeholder.png') }}";
            return;
        }
        else
        {
            if (type.match('image.*') === null)
            {
                alert('Le fichier choisi n\'est pas une image');
                return;
            }

            
        }
        previewImage.src = URL.createObjectURL(event.target.files[0]);
        previewImage.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
</script>
@endsection
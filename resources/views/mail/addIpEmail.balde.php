{{--
 * Ce fichier fait partie du projet FlorAccess
 * Copyright (C) 2024 Floris Robart <florobart.github@gmail.com>
--}}

<h1>Validation de l'email</h1>
<a href="{{ route('addIp', ['token' => $data['token'], 'ip' => $data['ip']]) }}">Cliquez ici pour validé qu'il s'agit bien de vous</a>

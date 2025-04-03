<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> SAAMP </title>

    <!-- Fonts -->
    <link href="//fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        body {
            font-family: 'Nunito';
        }

        .content {
            padding-top: 2%;
            padding-left: 5%;
            padding-right: 5%;
            padding-bottom: 5%;
        }



        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }


        .title {
            font-size: 84px;
        }

        .links>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        b{padding-top:20px;}

    </style>
</head>

<body>
    <div class="">
        @if (Route::has('login'))
        <div class="top-right links">
            <a href="{{ url('/') }}">SAAMP</a>
            @if (Auth::check())
            <a href="{{ url('/dashboard') }}">Accueil</a>
            @else
            <a href="{{ url('/login') }}">Connexion</a>
            @endif
        </div>
        @endif

        <div class="content">
            <h2>Conditions d'Utilisation de l'Application</h2>
            Dernière mise à jour : Novembre 2024<br>
            <br>
            <b>1. Acceptation des Conditions</b><br> En accédant à notre application ("CRM SAAMP"), vous acceptez d'être lié par les présentes Conditions d'Utilisation. Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser notre application.<br>
            <br>
            <b>2. Services Fournis L'application</b><br> CRM SAAMP permet aux utilisateurs de gérer leurs rendez-vous et d'accéder à leur agenda Google pour créer, lire, et modifier des événements en leur nom, après autorisation de l'utilisateur.<br>
            <br>
            <b>3. Compte Utilisateur et Sécurité</b><br>Vous devez disposer d'un compte Google valide pour utiliser les fonctionnalités liées au calendrier. Vous êtes responsable de la confidentialité de vos identifiants et de toutes les activités menées avec votre compte.<br>
            <br>
            <b>4. Utilisation Acceptable</b><br>
            Autorisation : Vous acceptez d'accorder les permissions nécessaires pour que notre application accède à votre calendrier Google dans le cadre des services fournis.<br>
            Restrictions : Vous acceptez de ne pas utiliser l'application à des fins illégales ou non autorisées, ni de perturber le fonctionnement du service.<br>
            <br>
            <b>5. Propriété Intellectuelle</b><br> Tous les droits, titres et intérêts liés à l'application (y compris, sans s'y limiter, le contenu, les graphiques, le code source, et les logos) restent la propriété exclusive de SAAMP. Vous ne pouvez pas reproduire, distribuer, ou modifier toute partie de l'application sans autorisation.<br>
            <br>
            <b>6. Limitation de Responsabilité</b><br>Nous nous efforçons de maintenir l’application fonctionnelle et sécurisée, mais nous ne pouvons garantir son fonctionnement ininterrompu. Nous déclinons toute responsabilité en cas de perte de données, d'accès non autorisé ou d'interruption de service.<br>
            <br>
            <b>7. Résiliation</b><br>Nous nous réservons le droit de suspendre ou de résilier votre accès à l'application si nous estimons que vous avez violé ces conditions d'utilisation. Vous pouvez également résilier votre utilisation de l'application en révoquant son accès à votre calendrier dans vos paramètres Google.<br>
            <br>
            <b>8. Modifications des Conditions</b><br>Nous pouvons modifier ces conditions à tout moment. Les modifications prendront effet immédiatement après leur publication. Si vous continuez à utiliser l’application après la publication des nouvelles conditions, cela signifie que vous acceptez les modifications.<br>
            <br>
            <b>9. Contact</b><br>Si vous avez des questions sur ces conditions d'utilisation, veuillez nous contacter à contact@saamp.com.<br>

        </div>
    </div>
</body>

</html>
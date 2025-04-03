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
        /*
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
*/
        @import url('//fonts.googleapis.com/css?family=Nunito');

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
        b{margin-top:20px;}
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

            <h2>Règles de Confidentialité de l'Application</h2>
            Dernière mise à jour : Novembre 2024<br>
            <br>
            Introduction Nous respectons votre vie privée et nous nous engageons à protéger les informations personnelles que vous partagez avec nous. Cette politique de confidentialité explique comment notre application ("CRM SAAMP") collecte, utilise, partage et protège vos informations personnelles.<br>
            <br>
            <b>1. Informations Collectées</b><br>Nous collectons les informations suivantes lorsque vous utilisez notre application :</b><br>
            <br>
            Informations d’identification : telles que votre nom, adresse e-mail, et autres informations de contact nécessaires à l’authentification.<br>
            Données de calendrier : avec votre permission, notre application accède à votre agenda Google pour créer, afficher ou modifier des événements, conformément aux fonctionnalités de notre application.<br>
            <br>
            <b>2. Utilisation des Informations</b><br>Nous utilisons vos informations pour les raisons suivantes :</b><br>
            <br>
            Gestion des rendez-vous : accès à votre calendrier pour y ajouter, modifier ou lire des événements selon vos besoins.<br>
            Amélioration de l'application : nous analysons de manière anonyme certaines données d'utilisation pour améliorer l'expérience utilisateur.<br>
            Communication : nous pourrions vous envoyer des notifications relatives à votre compte ou aux changements de l’application.<br>
            <br>
            <b>3. Partage des Informations</b><br>Nous ne vendons ni ne partageons vos informations personnelles avec des tiers non autorisés. Nous ne partageons vos données qu'avec les entités suivantes :</b><br>
            <br>
            Google : via l'API Google Calendar, pour accéder aux fonctionnalités du calendrier.<br>
            Services tiers : uniquement lorsque cela est nécessaire pour fournir des fonctionnalités spécifiques de l'application, et toujours avec votre consentement.<br>
            <br>
            <b>4. Sécurité des Données</b><br>Nous utilisons des mesures de sécurité avancées pour protéger vos données contre les accès non autorisés, la perte ou la divulgation.</b><br>
            <br>
            <b>5. Vos Droits </b><br>
            Vous avez le droit de :<br>
            Accéder à vos informations : Demandez une copie des informations que nous possédons à votre sujet.<br>
            Révoquer votre consentement : Vous pouvez retirer les permissions accordées à notre application pour accéder à votre Google Calendar à tout moment.<br>
            <br>
            <b>6. Contact</b><br> Pour toute question concernant cette politique de confidentialité, veuillez nous contacter à l’adresse : contact@saamp.com.</b><br>
            <br>
            <b>7. Modifications de cette Politique</b><br> Nous nous réservons le droit de modifier cette politique de confidentialité. Les modifications seront publiées sur cette page et nous vous en informerons par e-mail si elles affectent vos droits.</b>
        </div>
    </div>
</body>

</html>
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="8WFo21CjtNAM9x6ZPW1O_-J319dH3HEm1InRDgqN04M" />
        <title> SAAMP </title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;

                font-family: 'Nunito', sans-serif;
                font-weight: 100;
                /*height: 100vh;*/
                margin: 0;
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
            .footer-links{
                position: fixed;
                bottom: 20px;
                right: 10px;
            }
            .content {
                padding-top:10%;
                text-align: center;
                padding-bottom:10%;

            }

            .title {
                font-size: 64px;
                color: #636b6f;
            }

            .links > a {
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
            .text{
                padding-right:10%;
                padding-left:10%;
                padding-top:1%;
                color:#3f5367!important;
                font-size:14px;

            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Accueil</a>
                    @else
                        <a href="{{ url('/login') }}">Connexion</a>
                     @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    CRM SAAMP
                </div>
                <div class="text" >
                Cette application web est spécialement conçue pour les agents de SAAMP afin de faciliter la gestion des relations avec les clients et les activités quotidiennes.<br> Elle permet aux agents de centraliser et de suivre leurs interactions avec les clients, d'organiser leurs rendez-vous, et de gérer leurs tâches.<br>
                <br>
                Pour offrir une meilleure intégration avec les outils de productivité, l'application inclut également une fonctionnalité de synchronisation avec Google Agenda. Cela permet aux agents de lier leurs rendez-vous directement à leur calendrier Google, garantissant une meilleure organisation et un suivi optimal des engagements.<br>
                </div>
            </div>
            <div class="footer-links links">
                <a href="{{route('regles')}}">Conditions d'Utilisation</a>
                <a href="{{route('confid')}}">Règles de Confidentialité</a>
            </div>

        </div>
    </body>
</html>

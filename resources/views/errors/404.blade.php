<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
            background-image: url({{  URL::asset('img/error.jpg') }}); 
            background-size: cover; /* Ajuste l'image pour couvrir tout l'écran */
            background-position: center; /* Centre l'image */
            background-repeat: no-repeat; /* Empêche la répétition de l'image */
        }
        .content {
			margin-top:50px;
            text-align: center;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Ajoute un fond blanc semi-transparent pour le texte */
            border-radius: 15px;
            display: inline-block;
        }
        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }
        .message {
            font-size: 24px;
        }
		body{
			text-align:center;
		}
    </style>
</head>
<body>
    <div class="content">
        <div class="title">404</div>
        <p class="message">Désolé, la page que vous cherchez n'a pas été trouvée.</p>
        <a href="{{ url('/') }}">Retour à l'accueil</a>
    </div>
</body>
</html>

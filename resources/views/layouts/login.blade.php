<!DOCTYPE html>
<html lang="fr">
<?php    setlocale(LC_ALL, "fr_FR.UTF-8");    ?>
<head>
    <title>SAAMP</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

 
    <!-- Styles -->

	<!-- global level css -->
    <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css" />
   <link rel="shortcut icon" type="image/png" href="{{  URL::asset('site/img/favicon.png') }}">


</head>
<body>
    <div id="app">


        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

<style>
.icon1 {
  background-image: linear-gradient(to right, rgb(181, 127, 255), rgb(138, 127, 255));
  box-shadow: rgb(137, 109, 219) 0px 0px 0px 0px, rgb(159, 127, 255) 0px 0px 0px 0px;
}

.icon2 {
  box-shadow: rgb(55, 189, 131) 0px 0px 0px 0px, rgb(64, 220, 152) 0px 0px 0px 0px;
  background-image: linear-gradient(to right, rgb(64, 220, 178), rgb(64, 220, 126));
 }


.icon3 {
   box-shadow:rgb(65, 156, 219) 0px 0px 0px 0px, rgb(76, 181, 255) 0px 0px 0px 0px;
   background-image: linear-gradient(to right, rgb(76, 196, 255), rgb(76, 166, 255));
}

.icon4 {

  box-shadow: rgb(219, 157, 0) 0px 0px 0px 0px, rgb(255, 182, 0) 0px 0px 0px 0px;
  background-image: linear-gradient(to right, rgb(255, 204, 0), rgb(255, 161, 0));
}

.icon5 {
  box-shadow: rgb(255, 120, 150) 0px 0px 0px 0px, rgb(255, 103, 128) 0px 0px 0px 0px;
  background-image: linear-gradient(to right, rgb(255, 103, 128), rgb(255, 120, 150));

}

.icon6 {
  box-shadow:  rgb(36, 84, 227) 0px 0px 0px 0px, rgb(66, 114, 287) 0px 0px 0px 0px;
  background-image: linear-gradient(to right,  rgb(66, 114, 287),  rgb(36, 84, 227));

}

</style>
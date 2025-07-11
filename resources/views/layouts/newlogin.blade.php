
<!DOCTYPE html>
<html  >

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Iheb SAAD">

    <title>CRM SAAMP</title>
    <link rel="shortcut icon" type="image/png" href="{{  URL::asset('img/favicon.png') }}">

    <!-- Custom fonts for this template-->
    <link href="{{ URL::asset('sbadmin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

	<link rel="stylesheet" href="{{ URL::asset('front/css/default.css') }}">


    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link  href="{{URL::asset('sbadmin/css/sb-admin-2.css')}}"   rel="stylesheet">
    <script src="{{ URL::asset('sbadmin/vendor/jquery/jquery.min.js')}}"></script>


    <script  src="{{ URL::asset('sbadmin/js/sb-admin-2.min.js')}}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.7/bootstrap-notify.js"></script>




</head>

<body class="" style="font-family:Nunito">

        @yield('content')


    <!-- Bootstrap core JavaScript -->
    <script src="{{ URL::asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript -->
    <script src="{{ URL::asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>


    <!-- Custom scripts for all pages
    <script src="js/sb-admin-2.min.js"></script>
-->
</body>

</html>

 <head>
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="iheb saad">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <link rel="shortcut icon" type="image/png" href="{{  URL::asset('img/favicon.png') }}">
   <meta charset="UTF-8">
   <title>
     @section('title')
     CRM SAAMP
     @show
   </title>


   <meta  >

   <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
   <!-- CSRF Token -->
   {{ csrf_field() }}

   <script src="{{ URL::asset('sbadmin/vendor/jquery/jquery.min.js')}}"></script>

   <link href="{{ asset('sbadmin/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">

   <link rel="stylesheet" href="{{ URL::asset('bootstrap/css/bootstrap.min.css') }}">

   <link href="{{ URL::asset('css/datepicker.css') }}" rel="stylesheet">

   <link rel="stylesheet" href="{{ URL::asset('front/css/default.css') }}">

   <!-- include alertify css -->

   <link rel="stylesheet" href="{{ URL::asset('assets/css/styles.css') }}">

   <link rel="stylesheet" href="{{ URL::asset('assets/css/alertify.css') }}">
   <link rel="stylesheet" href="{{ URL::asset('assets/css/alertify-bootstrap.css') }}">

   <link rel="stylesheet" href="{{ URL::asset('css/custom_css/tables.css') }}">


   <link href="{{ asset('js/select2/css/select2.css') }}" rel="stylesheet" type="text/css" />
   <link href="{{ asset('js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />

  <style>
  .container-fluid {
    display: flex;
    flex-direction: column;
    min-height: calc(100vh - 180px);
  }
  footer{
      height:80px;
  }
  main {
    flex: 1;
  }
  </style>
   <script>
     $(document).ready(function() {
       if (parseInt(window.screen.availWidth) < 1024) {
         $("body").toggleClass("sidebar-toggled");
         $(".sidebar").toggleClass("toggled");

       } else {

       }
     });
   </script>

   <!-- Custom fonts for this template-->
   <link href="{{ asset('sbadmin/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css" integrity="sha512-u7ppO4TLg4v6EY8yQ6T6d66inT0daGyTodAi6ycbw9+/AU8KMLAF7Z7YGKPMRA96v7t+c7O1s6YCTGkok6p9ZA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

   <!-- Custom styles for this template-->
   <link href="{{ asset('sbadmin/css/sb-admin-2.css') }}" rel="stylesheet">

   <!-- Responsive-->
   <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">

   <script src="{{ URL::asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
   <!-- Core plugin JavaScript -->
   <script src="{{ URL::asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

 </head>
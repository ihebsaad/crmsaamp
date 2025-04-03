<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/png" href="{{  URL::asset('img/favicon.png') }}">

        <title> SAAMP - ERROR</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
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

            .content {
                text-align: center;
            }

            .title {
                font-size: 34px;
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

/*
  ##Device = Tablets, Ipads (portrait)
  ##Screen = B/w 768px to 1024px
*/

@media (min-width: 768px) and (max-width: 1024px) {

 #errorimg{width:450px!important;}
 #logo{width:150px!important;}

}

/*
  ##Device = Tablets, Ipads (landscape)
  ##Screen = B/w 768px to 1024px
*/

@media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {

 #errorimg{width:450px!important;}
  #logo{width:130px!important;}

}
/*
  ##Device = Low Resolution Tablets, Mobiles (Landscape)
  ##Screen = B/w 481px to 767px
*/

@media (min-width: 481px) and (max-width: 767px) {

 #errorimg{width:300px!important;}
 #logo{display:none;}

}

/*
  ##Device = Most of the Smartphones Mobiles (Portrait)
  ##Screen = B/w 320px to 479px
*/

@media (min-width: 320px) and (max-width: 480px) {

 #errorimg{width:200px!important;}
 #logo{display:none;}

}

        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
		 <div class=" links"  style="position: absolute;left: 10px;top: 18px;">  <img id="logo" src="{{ URL::asset('front/images/logo.png')}}" alt="Logo"> </div>

         <!--   @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/dashboard') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                     @endif
                </div>
            @endif
		-->
            <div class="content">

                 <center>   <img style="margin-top:30px" width="600" id="errorimg" src="{{ URL::asset('front/images/error.jpg')}}" alt="error page"/></center>

                <div class="title m-b-md">

					<?php echo json_encode($response['message']); ?><br>
					<span style="font-size:16px"><i><?php echo 'code: <b>'.json_encode($response['status_code']); ?></b></i></span><br><br>
					<a style="font-size:14px;background-color:#e6d685;color:black;font-weight:bold;padding:5px 20px 5px 20px; ;border-radius:10px;"  href="{{ url('/dashboard') }}"   >{{__('msg.Home')}}</a>
                </div>


            </div>
        </div>
    </body>
</html>

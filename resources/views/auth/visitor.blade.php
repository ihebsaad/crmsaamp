@extends('layouts.newlogin')

@section('content')
<style>
#login,#password{display:none;}
</style>
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Accès Visiteur </h1>
                                    </div>
                                    <form class="user"   method="POST" action="{{ route('login') }}">
                                     {{ csrf_field() }}
						
                                        <div class="form-group">
                                            <input   class="form-control form-control-user  form-control{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                                                id="login" aria-describedby="emailHelp"
                                                placeholder="Votre identifiant ou adresse email"  readonly name="login" value="visitor@gmail.com" required autofocus>
                                  @if ($errors->has('username') || $errors->has('email'))
                                    <span class="invalid-feedback">
								<strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>

									</span>
                                @endif                                     
									   </div>
                                        <div class="form-group">
                                            <input type="password" name="password"  readonly class="form-control form-control-user"
                                                id="password" placeholder="Mot de passe" value="remy2020*">
												
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif												
                                        </div>

                                        <div class="g-recaptcha mb-20" 
                                         data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}">
                                        </div>
                                        <script src='https://www.google.com/recaptcha/api.js'></script>


                                        <button type="submit"  class="btn btn-primary btn-user btn-block">
                                            Connexion
                                        </button>
 
                                    </form>
                                    <hr>

                                    <div class="text-center">
                                        <a class="small" href="{{ route('register') }}">Créer un compte!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
	
	
@endsection
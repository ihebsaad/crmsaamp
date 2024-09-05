@extends('layouts.newlogin')

@section('content')
@php
 $activites=DB::table('type_client')->get();

@endphp
    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-2">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Créer mon compte</h1>
                            </div>
                            <form class="user"  method="POST" action="{{ route('registration') }}">
								{{ csrf_field() }}

                            @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div><br />
                                    @endif

                                @if (!empty( Session::get('success') ))
                                    <div class="alert alert-success">

                                    {{ Session::get('success') }}
                                    </div>
                                @endif
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName" name="name" required  oninvalid="this.setCustomValidity('Champ Obligatoire')"   oninput="this.setCustomValidity('')"     placeholder="Prénom*"  value="{{old('name')}}">
								@if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName" name="lastname" required   oninvalid="this.setCustomValidity('Champ Obligatoire')"  oninput="this.setCustomValidity('')"      placeholder="Nom*" value="{{old('lastname')}}">
                                    </div>
                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                                </div>
								<style>
								#activity:placeholder-shown{
								color: darkgrey;
                                }
								</style>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
										<select class="form-control  " id="activity" name="activity"  placeholder="Sélectionnez votre activité*"  required   oninvalid="this.setCustomValidity('Champ Obligatoire')"  oninput="this.setCustomValidity('')"	style="font-size: 0.8rem;border-radius: 10rem;padding-left:15px;padding-top:10px;height:50px;font-family:Nunito">
											<option value="">Sélectionnez votre activité*</option>
											@foreach($activites as $activite)
												<option  value="{{$activite->type_client_ident}}">{{$activite->type_client_lib}}</option>
											@endforeach
										</select>
									</div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
										<input type="text" class="form-control form-control-user" id="siret" name="siret"  required  pattern=".{8,9}"   oninvalid="this.setCustomValidity('9 caractères')"  oninput="this.setCustomValidity('')"     placeholder="SIREN*"   onchange="checkexiste( this,'siret')"  value="{{old('siret')}}">
									</div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
										<input type="text" class="form-control form-control-user" id="mobile" name="mobile" pattern=".{10,10}" required   oninvalid="this.setCustomValidity('10 Chiffres')"  oninput="this.setCustomValidity('')"   placeholder="Téléphone portable*" value="{{old('mobile')}}">
									</div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="phone" name="phone"  pattern=".{0,10}"
                                        placeholder="Téléphone fixe" value="{{old('phone')}}">
									</div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
										<input type="email" class="form-control form-control-user" id="email" name="email"  required   Autocomplete="NoAutocomplete"   placeholder="Adresse Email*"  onchange="checkexiste( this,'email')"  oninvalid="this.setCustomValidity('Insérez une adresse email valide')"  oninput="this.setCustomValidity('')"  value="{{old('email')}}">
									</div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
										<button type="button" id="demande" onclick="demand()" disabled style="display:none" class="btn btn-primary btn-user btn-block">Demande de création de compte</button>
									</div>
                                </div>
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
								<div class="row pl-20 pr-20">
									<label><small>Le mot de passe doit contenir <b>8 caractères</b>, <b>une majuscule</b>, <b>un chiffre</b> et <b>un caractère spécial </b> parmi !?@#\$%\^&\.*  au minimum.</small></label>
								</div>
								<div class="form-group row">

                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" name="password" id="password"  required  autocomplete="new-password"  pattern=".{8,30}"  id="exampleInputPassword" placeholder="Mot de passe*"  onchange="CheckPassword()" oninvalid="this.setCustomValidity('La taille minimale est 8 caractères')"   oninput="this.setCustomValidity('')"  >
                                        <label style="font-size:12px" class="ml-4 mt-2 mb-2"><input type="checkbox" onclick="show_pass()">Afficher le mot de passe</input></label>
                                    </div>

                                    <div class="col-sm-6">
                                         <input type="password" class="form-control form-control-user" name="confirmation"  required id="password_confirmation"   autocomplete="new-password"   pattern=".{8,30}"  id="exampleRepeatPassword" placeholder="Confirmation du mot de passe*"  disabled>
                                    </div>
								   @if ($errors->has('password') )
										<span class="help-block">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
									@endif

								   @if ($errors->has('password_confirmation') )
										<span class="help-block">
											<strong>{{ $errors->first('password_confirmation') }}</strong>
										</span>
									@endif
                                </div>

								<div class="row pl-20 pb-10">
									<label><input type="checkbox"  required > J'accepte les conditions générales de ventes.</input></label>
								</div>
								<input type="hidden"  name="client_id"  id="client_id"  />
								<input type="hidden"  name="client_id2"  id="client_id2"  />

								<div class="g-recaptcha mb-20"     data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
								<script src='https://www.google.com/recaptcha/api.js'></script>

                                <button type="submit" class="btn btn-primary btn-user btn-block"  id="register" disabled>
                                    Inscription
                                </button>

                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('password.request') }}">Mot de passe oublié?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{route('login')}}">Vous avez un compte? Connexion ici</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
	  <!--<script src="//bootstrap-notify.remabledesigns.com/js/bootstrap-notify.min.js"></script>-->
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js"></script>

	<style>
	.btn:disabled{opacity:0.5;}
	</style>
	<script>

        function show_pass() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
        }

        var siret_existe=0;
	 function CheckPassword()
	{
	var inputtxt=document.getElementById('password').value;
	//var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
	 var passw = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!?@#\$%\^&\.*])(?=.{8,})/;
	//if(inputtxt.value.match(passw))
	 if( passw.test(inputtxt))
	{
	 $.notify({
        message: 'Mot de passe valide  !',
        icon: 'glyphicon glyphicon-remove'
            },{
            type: 'success',
            delay: 1000,
            timer: 3000,
            placement: {
				from: "bottom",
                align: "right"
                },
            });
		$('#password').css('border','1px solid #18aa76');
		$("#password_confirmation").prop('disabled', false);
		$("#password_confirmation").focus();
	}
	else
	{
		$.notify({
            message: 'Mot de passe faible  !',
            icon: 'glyphicon glyphicon-remove'
            },{
            type: 'danger',
            delay: 1000,
            timer: 3000,
            placement: {
				from: "bottom",
                align: "right"
                    },
                });
			$('#password').css('border','2px solid #f1592a');
 			$('#password').focus();
			$("#password_confirmation").prop('disabled', true);

		}
	}
        function demand( ) {
            var _token = $('input[name="_token"]').val();
            var email = $('#email').val();
            var siret = $('#siret').val();
            if(siret_existe && email !=''){
                $.ajax({
                    url: "{{ route('send_demand') }}",
                    method: "POST",
                    data: {  siret:siret,email:email, _token: _token},
                    success: function () {

                        $.notify({
                            message: 'Demande envoyée !',
                            icon: 'glyphicon glyphicon-remove'
                                },{
                                type: 'success',
                                delay: 1000,
                                timer: 3000,
                                placement: {
                                    from: "bottom",
                                    align: "right"
                                    },
                                });
                                $('#demande').prop('disabled',true);
                        }
                    });
                }
            }

	   function checkexiste( elm,type) {
        var id=elm.id;
        var val =document.getElementById(id).value;
        //  var type = $('#type').val();

        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('checkexiste') }}",
            method: "POST",
            data: {   val:val,type:type, _token: _token},
            success: function (data) {

				if(type=="siret"){
				if(data==0){
		           $.notify({
                        message: 'SIREN inexistant !',
                        icon: 'glyphic  on glyphicon-remove'
                    },{
                        type: 'danger',
                        delay: 1000,
                        timer: 3000,
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                    });
					$('#siret').css('border','2px solid #f1592a');
					//$('#siret').val('');
					$('#siret').focus();
						}else{
                            siret_existe=1;
                            $('#siret').css('border','1px solid #18aa76');
					        $('#client_id').val(data);
						    if( $('#client_id2').val() >0 ){
						        $("#register").prop('disabled', false);

						    }
						}


				}

				if(type=="email"){
				if(data==0){
		           $.notify({
                        message: "Votre adresse mail n’est pas encore répertoriée dans notre base, Cliquez sur : Demande de création de compte. Cette procédure peut prendre quelques heures. Vous recevrez un message vous indiquant la validation de votre compte dans les plus brefs délais",
                        icon: 'glyphicon glyphicon-remove'
                    },{
                        type: 'danger',
                        delay: 1000,
                        timer: 30000,
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                    });
					$('#email').css('border','2px solid #f1592a');
					//$('#email').val('');
					$('#email').focus();
					$('#demande').show();
					$('#demande').prop('disabled',false);
						}
				else{
					$('#email').css('border','1px solid #18aa76');
 				    $('#client_id2').val(data);
				    $("#register").prop('disabled', false);
                    $('#demande').hide();
				}


				}



			}
		});

		} ;


	$( "#siret" ).keypress(function( evt ) {

     var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
		});

	 $( "#phone" ).keypress(function( evt ) {

    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
		});

	$( "#mobile" ).keypress(function( evt ) {

     var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
		});

		$( "#username" ).keypress(function( evt ) {

     var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode >= 65 &&  ASCIICode <= 120  && ASCIICode > 57  && ASCIICode != 32  && ASCIICode != 0  )
            return true;
        return false;
		});

	$( "#password_confirmation" ).change(function() {
		password=$('#password').val();
		confirm=$('#password_confirmation').val();
					if(password!=confirm ){
		                    $.notify({
                        message: 'Les mots de passes sont différents !',
                        icon: 'glyphicon glyphicon-remove'
                    },{
                        type: 'danger',
                        delay: 1000,
                        timer: 3000,
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                    });
					$('#password_confirmation').val('');
					$('#password_confirmation').focus();
					}
			});

	</script>


<!--<script   src="https://code.jquery.com/jquery-3.5.1.js"  ></script>-->
<script>
$(function () {

	/*		$('.btn-refresh').click(function(){
 	var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('refresh') }}",
            method: "GET",
            success: function (data) {
 			$('.captcha span').html(data);
            }
        });
   });*/
});

</script>
@endsection
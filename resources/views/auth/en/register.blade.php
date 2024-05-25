@extends('layouts.newlogin')

@section('content')

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-2">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">{{__('msg.Create an account')}}</h1>
                            </div>
                            <form class="user"  method="POST" action="{{ route('register') }}">
								{{ csrf_field() }}
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName" name="name" required
                                            placeholder="{{__('msg.Name')}}*">
								@if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName" name="lastname" required
                                            placeholder="{{__('msg.Last name')}}*">
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
}								}
								</style>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">								
                                    <select class="form-control  " id="activity" name="activity"  placeholder="Sélectionnez votre activité*"  required
									style="font-size: 0.8rem;border-radius: 10rem;padding-left:15px;padding-top:10px;height:50px;font-family:Nunito">
									<option value="">{{__('msg.Select your activity')}}*</option>
									<option value="artisan">{{__('msg.Artisan')}}</option>
									<option value="fabricant" >{{__('msg.Manufacturer')}}</option>
									<option value="industriel" >{{__('msg.Industrial')}}</option>
									<option value="laboratoire" >{{__('msg.Laboratory')}}</option>
									<option value="recuperateur" >{{__('msg.Gold Scraps Buyer')}}</option>
									<option value="investisseur" >{{__('msg.Investor')}}</option>
									</select>
									</div>	
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="siret" name="siret"  required  pattern=".{14,14}"
                                        placeholder="SIRET* (ex : 7362 521 879 00034)">									
									</div>
                                </div>
								
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">								
                                    <input type="text" class="form-control form-control-user" id="mobile" name="mobile" pattern=".{10,10}" required
                                        placeholder="{{__('msg.Cell phone')}}*">
									</div>	
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="phone" name="phone"  pattern=".{0,10}"
                                        placeholder="{{__('msg.Phone')}}">									
									</div>
                                </div>	
								
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">								
                                    <input type="text" class="form-control form-control-user" id="username" name="username" required  pattern=".{4,20}"  autocomplete="off"
                                        placeholder="{{__('msg.Username')}}*">
									</div>	
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email"  required
                                        placeholder="{{__('msg.Email address')}}*">									
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
								<div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" name="password"   pattern=".{6,30}"
                                            id="exampleInputPassword" placeholder="{{__('msg.Password')}}*">
                                    </div>
									
								
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" name="password_confirmation"   pattern=".{6,30}"
                                            id="exampleRepeatPassword" placeholder="{{__('msg.Password confirmation')}}*">
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
								<small>{{__('msg.One of our staff will call you to finalize your file')}}.</small>
								</div>
								<div class="row pl-20 pb-10">
								<label><input type="checkbox"  required >{{__('msg.Password confirmation')}}</input>
								</label>
								</div>								
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    {{__('msg.Register')}}
                                </button>
                                 
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('password.request') }}">{{__('msg.Password confirmation')}}?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{route('login')}}">{{__('msg.You have an account? Login here')}}Vous avez un compte? Connexion ici</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
	
	<script>
	
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
		
	/*	    $("#username").keydown(function(event){
        var inputValue = event.which;
        // allow letters and whitespaces only.
        if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)) { 
            event.preventDefault(); 
        }
    });*/
	
	</script>
@endsection
@extends('layouts.back')

@section('content')
<?php

  $contacts=DB::table('contact')->get();
  $clients=DB::table('client')->get();
  $activites=DB::table('type_client')->get();

?>
 
<style>
label{color:black;}
.select2-selection--single{border:1px solid #d1d3e2!important;}
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

	<div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-9 col-sd-12 mb-4">

						 <div class="card shadow mb-4">
                                <div class="  ">
                                    <a href="#div1" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">								
                                    <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Add a new user')}}</h6>
									</a>
                                </div>
                                <div id="div1" class="card-body">

								
                            <div class="text-center">
                             </div>
                            <form class="user"  method="POST" action="{{ route('adding') }}">
								{{ csrf_field() }}
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
									<label>{{__('msg.Client ID')}}*</label>
                                 <select class="form-control  " name="client_id" id="client_id"  required placeholder="ID CLient"  style="font-size: 0.8rem;border-radius: 10rem;padding-left:15px;padding-top:10px;height:50px;font-family:Nunito"  >
 <option></option>
 <?php 
	foreach($clients as $ct)
	{
	echo	' <option value="'.$ct->cl_ident.'" title="'.$ct->siret.'" >'.$ct->cl_ident.'</option>';
	}
 ?>

 </select>
   </div>
                                     <div class="col-sm-6 mb-3 mb-sm-0">
									 <label>SIRET</label>
                                    <input type="text" class="form-control  " id="siret" name="siret"  required  pattern=".{7,14}"
                                        placeholder="SIREN*  "   oninvalid="this.setCustomValidity('Le format doit être 9 chiffres')"
  oninput="this.setCustomValidity('')">									
									</div>
					 
                                    </div>
									
	                                <div class="form-group row">
									
                                    <div class="col-sm-6">
									<label>{{__('msg.Last name')}}*</label>
                                        <select type="text" class="form-control  " id="lastname" name="lastname" required  
                                            placeholder="{{__('msg.Last name')}}"  >
											<option></option>
 <?php 
	foreach($contacts as $ct)
	{
 	echo	' <option value="'.$ct->nom.'" title="'.$ct->prenom.'"  target="'.$ct->email.'" >'.$ct->nom.'</option>';
	}
 ?>
 </select>
                                    </div>
									
                                    <div class="col-sm-6">
									<label>{{__('msg.Name')}}*</label>
                                        <input type="text" class="form-control  " id="name" name="name" required
                                            placeholder="{{__('msg.Name')}}"   oninvalid="this.setCustomValidity('Champ obligatoire')"
  oninput="this.setCustomValidity('')">
                                    </div>
               								
                                </div>
								<style>
								#activity:placeholder-shown{
								color: darkgrey;
}								}
								</style>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">		
									<label>{{__('msg.Activity')}}</label>									
                                    <select class="form-control  " id="activity" name="activity"     required
									style="font-size: 0.8rem; padding-left:15px;padding-top:10px; color:black"    oninvalid="this.setCustomValidity('Champ obligatoire')"   oninput="this.setCustomValidity('')">
									<option value=""></option>
                                    @foreach($activites as $activite)
                                        <option  value="{{$activite->type_client_ident}}">{{$activite->type_client_lib}}</option>
                                    @endforeach

									</select>
									</div>	
									
                          <div class="col-sm-6 mb-3 mb-sm-0">
						  <label>{{__('msg.Email')}}</label>
                                    <input type="email" class="form-control  " id="email" name="email"  required
                                        placeholder="{{__('msg.Email address')}}*"   Autocomplete="NoAutocomplete" oninvalid="this.setCustomValidity('Adresse email invalide')"
  oninput="this.setCustomValidity('')" >									
									</div>
									
                                </div>
								
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">		
									<label>{{__('msg.Cell phone')}}</label>									
                                    <input type="text" class="form-control  " id="mobile" name="mobile" pattern=".{10,10}" required
                                        placeholder="{{__('msg.Cell phone')}}*"    oninvalid="this.setCustomValidity('Le format doit être 10 chiffres')"
  oninput="this.setCustomValidity('')">
									</div>	
                                    <div class="col-sm-6 mb-3 mb-sm-0">
									<label>{{__('msg.Phone')}}</label>
                                    <input type="text" class="form-control  " id="phone" name="phone"  pattern=".{0,10}"   oninvalid="this.setCustomValidity('Le format doit être 10 chiffres')"
  oninput="this.setCustomValidity('')"
                                        placeholder="{{__('msg.Phone')}}">									
									</div>
                                </div>	
								
 								 

								<div class="form-group row">

			                     <div class="col-sm-6 mb-3 mb-sm-0">
								 <label>{{__('msg.Password')}}* <small> (6 caractères au minimum)</small></label>
                                        <input type="password" class="form-control  " name="password" id="password"   pattern=".{6,30}" autocomplete="new-password"   required
                                            id="exampleInputPassword" placeholder="{{__('msg.Password')}}*"  oninvalid="this.setCustomValidity('La taille minimale est 6 caractères')"
  oninput="this.setCustomValidity('')" >
                                    </div>							
								
                                    <div class="col-sm-6">
									<label>{{__('msg.Password confirmation')}}</label>
                                        <input type="password" class="form-control  " name="confirmation" id="password_confirmation"   pattern=".{6,30}"  autocomplete="new-password"   required
                                            id="exampleRepeatPassword" placeholder="{{__('msg.Password confirmation')}}*" oninvalid="this.setCustomValidity('La taille minimale est 6 caractères')"  onchange=""
  oninput="this.setCustomValidity('')"  >
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
		 							

								 <button       type="submit"  class="pull-right btn btn-success btn-icon-split  ml-20   mt-50 mb-30">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text" style="width:200px" >{{__('msg.Add user')}}</span>
                                    </button>
									
                                 
                            </form>
                               

							   </div>
                            </div>

           

                        </div>
						
 
 			 				 
	<script>
	
	        $('#lastname').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });
		
    $('#client_id').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });
		
		
		
	$( "#lastname" ).change(function() {
	 
		var name= $('#lastname').find('option:selected').attr('title');
		if(name!=''){
			$('#name').val(name);
		}
		
		var email= $('#lastname').find('option:selected').attr('target');
		if(email!=''){
			$('#email').val(email);
		}		
		
		
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
		
	$( "#client_id" ).change(function() {
	 
		var siret= $('#client_id').find('option:selected').attr('title');
		if(siret!=''){
			$('#siret').val(siret);
		}
	});
	
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
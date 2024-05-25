@extends('layouts.back')

@section('content')
<?php

$contacts = DB::table('contact')->get();
$clients = DB::table('client')->get();

?>
<style>
    label {
        color: black;
    }

    .select2-selection--single {
        border: 1px solid #d1d3e2 !important;
    }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<div class="row">

    <!-- Content Column -->
    <div class="col-lg-9 col-sm-12 mb-4">

        <div class="card shadow mb-4">
            <div class="  ">
                <a href="#div1" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                    <h6 class="m-0 font-weight-bold text-primary">{{__('msg.View user')}}</h6>
                </a>
            </div>
            <div id="div1" class="card-body">


                <div class="text-center">
                </div>
                <form class="user" method="POST" action="{{ route('updatinguser') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="user" value="<?php echo $user->id; ?>" />
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label>{{__('msg.Client ID')}}*</label>
                            <input readonly class="form-control  " name="client_id" id="client_id" value="<?php echo $user->client_id; ?>" />
                        </div>
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label>SIRET</label>
                            <input type="text" class="form-control  " id="siret" name="siret" required pattern=".{7,14}" readonly value="<?php echo $user->siret; ?>" placeholder="SIRET* (ex : 7362 521 879 00034)" oninvalid="this.setCustomValidity('Le format doit être 14 chiffres')" oninput="this.setCustomValidity('')">
                        </div>

                    </div>

                    <div class="form-group row">

                        <div class="col-sm-6">
                            <label>{{__('msg.Last name')}}*</label>
                            <input type="text" class="form-control  " id="lastname" name="lastname" required readonly value="<?php echo $user->lastname; ?>" placeholder="{{__('msg.Last name')}}" />

                        </div>

                        <div class="col-sm-6">
                            <label>{{__('msg.Name')}}*</label>
                            <input type="text" class="form-control  " id="name" name="name" required readonly value="<?php echo $user->name; ?>" placeholder="{{__('msg.Name')}}" oninvalid="this.setCustomValidity('Champ obligatoire')" oninput="this.setCustomValidity('')" />
                        </div>

                    </div>
                    <style>
                        #activity:placeholder-shown {
                            color: darkgrey;
                        }

                    </style>
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label><?php echo __('msg.Activity'); ?></label>
                            <select class="form-control  " id="activity" name="activity" placeholder="Sélectionnez votre activité" style="font-size: 0.8rem; padding-left:15px;padding-top:10px; color:black">
                                @foreach($activites as $activite)
                                <option @if($activite->type_client_ident==$user->activity) selected="selected" @endif value="{{$activite->type_client_ident}}">{{$activite->type_client_lib}}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label>{{__('msg.Email')}}</label>
                            <input type="email" class="form-control  " id="email" name="email" required readonly placeholder="{{__('msg.Email address')}}*" Autocomplete="NoAutocomplete" oninvalid="this.setCustomValidity('Adresse email invalide')" oninput="this.setCustomValidity('')">
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label>{{__('msg.Cell phone')}}</label>
                            <input type="text" class="form-control  " id="mobile" name="mobile" pattern=".{8,16}" value="<?php echo $user->mobile; ?>" placeholder="{{__('msg.Cell phone')}}" oninvalid="this.setCustomValidity('Le format doit être 10 chiffres')" oninput="this.setCustomValidity('')">
                        </div>
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label>{{__('msg.Phone')}}</label>
                            <input type="text" class="form-control  " id="phone" name="phone" pattern=".{0,10}" oninvalid="this.setCustomValidity('Le format doit être 10 chiffres')" value="<?php echo $user->phone; ?>" oninput="this.setCustomValidity('')" placeholder="{{__('msg.Phone')}}">
                        </div>
                    </div>



                    <div class="form-group row">

                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label>{{__('msg.Password')}}<small> (6 caractères au minimum)</small></label>
                            <input type="password" class="form-control  " name="password" id="password" pattern=".{6,30}" autocomplete="new-password" id="exampleInputPassword" placeholder="{{__('msg.Password')}}" oninvalid="this.setCustomValidity('La taille minimale est 6 caractères')" oninput="this.setCustomValidity('')">
                        </div>

                        <div class="col-sm-6">
                            <label>{{__('msg.Password confirmation')}}</label>
                            <input type="password" class="form-control  " name="confirmation" id="password_confirmation" pattern=".{6,30}" autocomplete="new-password" id="exampleRepeatPassword" placeholder="{{__('msg.Password confirmation')}}" oninvalid="this.setCustomValidity('La taille minimale est 6 caractères')" onchange="" oninput="this.setCustomValidity('')">
                        </div>

                    </div>



                    <button type="submit" class="pull-right btn btn-success btn-icon-split  ml-20   mt-50 mb-30">
                        <span class="icon text-white-50">
                            <i class="fas fa-save"></i>
                        </span>
                        <span class="text" style="width:200px">{{__('msg.Update')}}</span>
                    </button>


                </form>


            </div>
        </div>



    </div>

    <?php if (auth()->user()->user_type == 'admin') { ?>
        <!-- Content Column -->
        <div class="col-lg-3 col-sm-12 mb-4">

            <div class="card shadow mb-4">
                <div class="  ">
                    <a href="#div1" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h6 class="m-0 font-weight-bold text-primary">{{__('msg.User Type')}}</h6>
                    </a>
                </div>
                <form class="user" method="POST" action="{{ route('updatingusertype') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="user" value="<?php echo $user->id; ?>" />
                    <div id="div3" class="card-body">
                        <label>{{__('msg.User Type')}}</label>
                        <select class="form-control  " id="user_type" name="user_type">
                            <option @if($user->user_type=='') selected='selected' @endif value="">Simple</option>
                            <option @if($user->user_type=='adv') selected='selected' @endif value="adv">ADV</option>
                            <option @if($user->user_type=='admin') selected='selected' @endif value="admin">Admin</option>
                        </select>

                        <label class="mt-3">Niveau de trading</label>
                        <select class="form-control" id="level" name="level" placeholder="Sélectionnez le niveau de trading" style="font-size: 0.8rem; padding-left:15px;padding-top:10px; color:black">
                            <option value="0" @selected($user->level==0) >Niveau 0 (Limité)</option>
                            <option value="1" @selected($user->level==1) >Niveau 1 (Ordre fixing)</option>
                            <option value="2" @selected($user->level==2) >Niveau 2 (Ordre fixing + Ordre limite)</option>
                            <option value="3" @selected($user->level==3) >Niveau 3 (Complet)</option>
                        </select>
                    </div>


                    <button type="submit" class="pull-right btn btn-success btn-icon-split  ml-20   mt-50 mb-30">
                        <span class="icon text-white-50">
                            <i class="fas fa-save"></i>
                        </span>
                        <span class="text" style="width:200px">{{__('msg.Update')}}</span>
                    </button>

                </form>
            </div>
        </div>
    <?php  } ?>
    <script>
        $('#lastname').select2({
            filter: true,
            language: {
                noResults: function() {
                    return 'Pas de résultats';
                }
            }

        });

        $('#client_id').select2({
            filter: true,
            language: {
                noResults: function() {
                    return 'Pas de résultats';
                }
            }

        });



        $("#lastname").change(function() {

            var name = $('#lastname').find('option:selected').attr('title');
            if (name != '') {
                $('#name').val(name);
            }

            var email = $('#lastname').find('option:selected').attr('target');
            if (email != '') {
                $('#email').val(email);
            }


        });

        $("#password_confirmation").change(function() {
            password = $('#password').val();
            confirm = $('#password_confirmation').val();
            if (password != confirm) {
                $.notify({
                    message: 'Les mots de passes sont différents !',
                    icon: 'glyphicon glyphicon-remove'
                }, {
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

        $("#client_id").change(function() {

            var siret = $('#client_id').find('option:selected').attr('title');
            if (siret != '') {
                $('#siret').val(siret);
            }
        });

        $("#siret").keypress(function(evt) {

            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        });

        $("#phone").keypress(function(evt) {

            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        });

        $("#mobile").keypress(function(evt) {

            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        });

        $("#username").keypress(function(evt) {

            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode >= 65 && ASCIICode <= 120 && ASCIICode > 57 && ASCIICode != 32 && ASCIICode != 0)
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
@extends('layouts.back')

@section('content')

<b?php

?>

<style>
    .pointer{
        cursor: pointer;
    }

</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Create an appointment')}}</h6>
            </div>

            <div class="card-body" style="min-height:500px">

                <div class="row mt-2 mb-2">
                    @if(!$userToken)
                    <div class="col-md-12 float-right">
                        <a   href="{{ route('google.auth.redirect') }}" class="btn btn-primary float-right"><img width="50" style="width:50" src="{{  URL::asset('img/calendar.png') }}"/> Lier les rendez-vous à mon Agenda Google</a>
                    </div>
                    @endif
                </div>
                <form action="{{ route('rendezvous.store') }}" method="post"   enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="AccountId" value="{{$client->id ?? 0}}" id="AccountId"  >
                    <input type="hidden" name="mycl_id" value="{{$client->id ?? 0}}"  id="mycl_id">
                    <input type="hidden" name="created_by" value="{{auth()->user()->id}}" >
                    @if($client== null) 
                    <div class="row pt-1 pb-2" style="padding-left:30px!important;padding-top:15px!important">
                        <label for="hors_clientele" class="pr-3 pointer"><input type="radio" id="hors_clientele" name="type_rv" value="1" class="" onchange="$('#customer_name').show(); $('#customers_list').hide();initClient();filterTypeOptions(true)" checked> <b>Hors clientèle</b></label>
                        <label for="clientele" class="pl-3 pointer"><input type="radio" id="clientele" name="type_rv" value="2" class="" onchange="$('#customer_name').hide(); $('#customers_list').show();filterTypeOptions(false) " >  <b>Clientèle</b></label>
                    </div>
                    @endif
                    <div class="row pt-1">
                        @if($client== null) 
                        <div class="col-md-2 col-sm-6" id="customers_list" style="display:none;">
                            <div class="">
                                <label for="Account_Name">{{__('msg.Account name')}}*:</label>
                                <select   class="form-control" id="clients_list" style="width:100%" onchange="setClient()"  >
                                    <option></option>
                                    @foreach($clients as $cl)
                                    <option value="{{ $cl->id }}"   >{{ $cl->Nom}} ( {{ $cl->cl_ident}}) </option>
                                    @endforeach
                                </select><br><br>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-2 col-sm-6" id="customer_name" @if($client==null) style="display:none;" @endif>
                            <div class="">
                                <label for="Account_Name">{{__('msg.Account name')}}*:</label>
                                <input type="text" id="Account_Name" class="form-control" name="Account_Name" @if($client!= null)  readonly @endif value="{{$client->Nom ?? '' }}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="">
                                <label for="Started_at">{{__('msg.Start date')}}*:</label>
                                <input type="text" id="Started_at" class="form-control datepicker" name="Started_at"  required value="{{old('Started_at')}}">
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-6">
                            <div class="">
                                <label for="heure_debut">Heure*:</label>
                                <input type="time" id="heure_debut" class="form-control" name="heure_debut" required value="{{old('heure_debut')}}">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="">
                                <label for="End_AT">{{__('msg.End date')}}*:</label>
                                <input type="text" id="End_AT" class="form-control datepicker" name="End_AT" required value="{{old('End_AT')}}">
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-6">                            
                            <div class="">
                                <label for="heure_fin">Heure*:</label>
                                <input type="time" id="heure_fin" class="form-control" name="heure_fin" required value="{{old('heure_fin')}}">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="">
                                <label for="Subject">{{__('msg.Subject')}}:</label>
                                <input type="text" id="Subject" class="form-control" name="Subject"  value="{{old('Subject')}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">

                        <div class="col-md-2 col-sm-6">
                            <div class="">
                            <label for="Type">{{__('msg.Type')}}*:</label>
                                <select    id="Type" class="  form-control" name="Type" required  >
                                    <option  value=""></option>
                                    @if($client==null)
                                        <option class="hors-clientele" value="Administratif">Administratif</option>
                                        <option class="hors-clientele" value="Déplacement-Trajet">Déplacement-Trajet</option>      
                                        <option class="hors-clientele" value="Déplacement en Agence">Déplacement en Agence</option>
                                        <option class="hors-clientele" value="Interne">Interne</option>
                                        <option class="hors-clientele" value="Visite sur salon">Visite sur salon</option>                                                                                
                                    @endif                                        
                                        <option class="clientele" value="Prospection">Prospection</option>
                                        <option class="clientele" value="Fidélisation">Fidélisation</option>
                                        <option class="clientele" value="Reconquête">Reconquête</option>
                                        <option class="clientele" value="Courtoisie">Courtoisie</option>
                                        <option class="clientele" value="Suite à une réclamation">Suite à une réclamation</option>
                                        <option class="clientele" value="Suite à une Offre de prix">Suite à une Offre de prix</option>
                                        <option class="clientele" value="Suite à une commande">Suite à une commande</option>
                                        <option class="clientele" value="Formation">Formation</option>
                                        <option class="clientele" value="Enlèvement">Enlèvement</option>
                                        <option class="clientele" value="Livraison">Livraison</option>
                                        <option class="clientele" value="Dépôt">Dépôt</option>
                                        <option class="clientele" value="Pour une ouverture de compte">Pour une ouverture de compte</option>                                
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6">
                        <div class="">
                                <label for="Location">{{__('msg.Place')}}*:</label>
                                <input type="text" id="Location" class="form-control" name="Location" required value="{{old('Location')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6">
                            <div class="">
                                <label for="Location">Mode*:</label>
                                <select    id="mode_de_rdv" class="form-control" name="mode_de_rdv" required  >
                                    <option  value=""></option>
                                    <option  value="Déplacement">Déplacement</option>
                                    <option  value="En agence">En agence</option>
                                    <option value="Home Office">Home Office</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6">
                            <div class="">
                                <label for="Location">Statut*:</label>
                                <select    id="statut" class="form-control" name="statut" required  >
                                    <option  value="1">Planifié</option>
                                    <option  value="2">Réalisé</option>
                                    <option  value="3">Annulé</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div >
                                <label for="Description">{{__('msg.Description')}} :</label>
                                <textarea id="Description" class="form-control" name="Description" style="min-height:150px">{{old('Description')}}</textarea><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row ">

                        <div class="col-md-2 col-sm-6">
                            <div class="">
                                <label for="Date_creation">{{__('msg.Attributed to')}}*:</label>
                                <select    id="user_id" class="  form-control" name="user_id" required  >
                                    <option></option>
                                    @foreach($users as $user)
                                        <option @selected($user->id==auth()->user()->id) value="{{$user->id}}">{{$user->name}} {{$user->lastname}}</option>
                                    @endforeach
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="">
                                <label for="file">{{__('msg.File(s)')}}:</label>
                                <input type="file" id="fichier" class="form-control" name="files[]"  multiple     /><br><br>
                            </div>
                        </div>

                    </div>

                    
                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right" >{{__('msg.Add')}}</button>
                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>

    <script>
    function filterTypeOptions(isHorsClientele) {
        const select = document.getElementById('Type');
        const allOptions = select.querySelectorAll('option');
        
        // Cacher toutes les options sauf la première (vide)
        allOptions.forEach(option => {
            if (option.value !== "") {
                option.style.display = 'none';
            }
        });
        
        // Afficher les options appropriées
        if (isHorsClientele) {
            // Afficher les options hors clientèle
            const horsClienteleOptions = select.querySelectorAll('.hors-clientele');
            horsClienteleOptions.forEach(option => {
                option.style.display = 'block';
            });
        } else {
            // Afficher les options clientèle
            const clienteleOptions = select.querySelectorAll('.clientele');
            clienteleOptions.forEach(option => {
                option.style.display = 'block';
            });
        }
        
        // Réinitialiser la sélection
        select.value = "";
    }
    @if($client==null)
    // Initialiser les options au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        filterTypeOptions(true); // Par défaut, "Hors clientèle" est cochée
    });
    @endif
        function setClient(){
            let cl_id=$('#clients_list').val();            
            $('#AccountId').val(cl_id);
            $('#mycl_id').val(cl_id);
           
        }

        function initClient(){
            $('#AccountId').val(0);
            $('#mycl_id').val(0);
            $('#customer_name').hide();            
        }

        $(function () {

            
            $('#clients_list').select2({
                filter: true,
                language: {
                    noResults: function() {
                        return 'Pas de résultats';
                    }
                }
            });
            $( ".datepicker" ).datepicker({

                altField: "#datepicker",
                closeText: 'Fermer',
                prevText: 'Précédent',
                nextText: 'Suivant',
                currentText: 'Aujourd\'hui',
                monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
                dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
                dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                weekHeader: 'Sem.',
                buttonImage: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAATCAYAAAB2pebxAAABGUlEQVQ4jc2UP06EQBjFfyCN3ZR2yxHwBGBCYUIhN1hqGrWj03KsiM3Y7p7AI8CeQI/ATbBgiE+gMlvsS8jM+97jy5s/mQCFszFQAQN1c2AJZzMgA3rqpgcYx5FQDAb4Ah6AFmdfNxp0QAp0OJvMUii2BDDUzS3w7s2KOcGd5+UsRDhbAo+AWfyU4GwnPAYG4XucTYOPt1PkG2SsYTbq2iT2X3ZFkVeeTChyA9wDN5uNi/x62TzaMD5t1DTdy7rsbPfnJNan0i24ejOcHUPOgLM0CSTuyY+pzAH2wFG46jugupw9mZczSORl/BZ4Fq56ArTzPYn5vUA6h/XNVX03DZe0J59Maxsk7iCeBPgWrroB4sA/LiX/R/8DOHhi5y8Apx4AAAAASUVORK5CYII=",
                firstDay: 1,
                dateFormat: "yy-mm-dd",
                //minDate:0
            });
        });

    </script>
    @endsection
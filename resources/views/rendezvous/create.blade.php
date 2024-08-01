@extends('layouts.back')

@section('content')

<?php

?>

<style>


</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Créer un Rendez Vous </h6>
            </div>

            <div class="card-body" style="min-height:500px">

                <form action="{{ route('rendezvous.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="AccountId" value="{{$client->id}}" >
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}" >
                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div class="">
                                <label for="Account_Name">Nom du client:</label>
                                <input type="text" id="Account_Name" class="form-control" name="Account_Name"  readonly value="{{$client->Nom}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="Started_at">Date de début:</label>
                                <input type="text" id="Started_at" class="form-control datepicker" name="Started_at"  value="{{old('Started_at')}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="End_AT">Date de fin:</label>
                                <input type="text" id="End_AT" class="form-control datepicker" name="End_AT"  value="{{old('End_AT')}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-4">
                            <label for="ID_Contact">Contact:</label>
                            <select  id="ID_Contact" class="form-control" name="ID_Contact" required  >
                                @foreach($contacts as $contact)
                                    <option value="{{$contact->id}}">{{$contact->Prenom}} {{$contact->Nom}} - {{$contact->Title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <div class="">
                            <label for="Type">Type:</label>
                                <select    id="Type" class="  form-control" name="Type"   >
                                    <option  value=""></option>
                                    <option  value="Rdv téléphonique / visio">Rdv téléphonique / visio</option>
                                    <option  value="Rdv Fidélisation">Rdv Fidélisation</option>
                                    <option  value="Rdv Prospection">Rdv Prospection</option>
                                    <option  value="Rdv Reconquête">Rdv Reconquête</option>
                                    <option  value="Rdv Règlement Réclamation">Rdv Règlement Réclamation</option>
                                    <option  value="Rdv suite envoi offre de prix">Rdv suite envoi offre de prix</option>
                                    <option  value="Rdv suivi de commande">Rdv suivi de commande</option>
                                    <option  value="Visite du client en agence">Visite du client en agence</option>
                                    <option  value="Visite sur salon">Visite sur salon</option>
                                    <option  value="Formation">Formation</option>
                                    <option  value="Meeting">Meeting</option>
                                    <option  value="Other">Other</option>
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-4">
                        <div class="">
                                <label for="Location">Lieu:</label>
                                <input type="text" id="Location" class="form-control" name="Location"  value="{{old('Location')}}"><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div class="">
                                <label for="Subject">Sujet:</label>
                                <input type="text" id="Subject" class="form-control" name="Subject"  value="{{old('Subject')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="">
                                <label for="Date_creation">Attribué à:</label>
                                <select    id="user_id" class="  form-control" name="user_id" required  >
                                    <option></option>
                                    @foreach($users as $user)
                                        <option @selected($user->id==auth()->user()->id) value="{{$user->id}}">{{$user->name}} {{$user->lastname}}</option>
                                    @endforeach
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div >
                                <label for="Description">Description :</label>
                                <textarea id="Description" class="form-control" name="Description" style="min-height:150px">{{old('Description')}}</textarea><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right" >Ajouter</button>
                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>

    <script>
        $(function () {

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
                minDate:0
            });
        });

    </script>
    @endsection
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
                <h6 class="m-0 font-weight-bold text-primary">Réclamation {{$retour->id}} </h6>
            </div>

            <div class="card-body" style="min-height:500px">

                <form action="{{ route('retours.update', $retour->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Name">Nom:</label>
                                <input type="text" id="Name" class="form-control" name="Name"  value="{{$retour->Name}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div >
                                <label for="Type_retour">Type de retour:</label>
                                <select  style="color:white"  id="Type_retour" class="bg-{{$class}} form-control" name="Type_retour"   >
                                    <option></option>
                                    <option @selected($retour->Type_retour=="Information générale") value="Information générale">Information générale</option>
                                    <option @selected($retour->Type_retour=="Négatif") value="Négatif">Négatif</option>
                                    <option @selected($retour->Type_retour=="Positif") value="">Positif</option>
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Motif_retour">Motif de retour:</label>
                                <input type="text" id="Motif_retour" class="form-control" name="Motif_retour"  value="{{$retour->Motif_retour}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Responsable_de_resolution">Responsable de résolution:</label>
                                <input type="text" id="Responsable_de_resolution" class="form-control" name="Responsable_de_resolution"  value="{{$retour->Responsable_de_resolution}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_ouverture">Date d'ouverture:</label>
                                <input type="text" id="Date_ouverture" class="form-control" name="Date_ouverture"  value="{{$retour->Date_ouverture}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_cloture">Date de clôture:</label>
                                <input type="text" id="Date_cloture" class="form-control" name="Date_cloture"  value="{{$retour->Date_cloture}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-6">
                            <div class="">
                                <label for="Details_des_causes">Détails des causes:</label>
                                <textarea  id="Details_des_causes" class="form-control" name="Details_des_causes"  style="min-height:150px">{{$retour->Details_des_causes}}</textarea><br><br>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                <h3>Contact</h3>
                                @if(isset($contact))
                                <table class="table table-bordered">
                                <tr><td><b>Nom:</b> {{$contact->Nom}} </td><td> <b>Prénom:</b> {{$contact->Prenom}}</td></tr>
                                <tr><td colspan="2"><b>Titre:</b> {{$contact->Title}}</td></tr>
                                <tr><td  ><b>Mobile:</b> {{$contact->MobilePhone}}</td><td> <b>Tél:</b> {{$contact->Phone}}</td></tr>
                                <tr><td colspan="2"><b>Email:</b> {{$contact->Email}}</td></tr>
                                <tr><td colspan="2"><b>Compte:</b> {{$contact->Compte}}</td></tr>
                                <tr><td colspan="2"><b>Description:</b> {{$contact->Description}}</td></tr>
                                </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right">Modifier</button>
                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>


    @endsection
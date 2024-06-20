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
                <h6 class="m-0 font-weight-bold text-primary">Tâche {{$tache->id}} </h6>
            </div>

            <div class="card-body" style="min-height:500px">

                <form action="{{ route('taches.update', $tache->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div class="">
                                <label for="Subject">Sujet:</label>
                                <input type="text" id="Subject" class="form-control" name="Subject"  value="{{$tache->Subject}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="DateTache">Date :</label>
                                <input type="text" id="DateTache" class="form-control datepicker" name="DateTache"  value="{{date('Y-m-d', strtotime($tache->DateTache))}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div >
                                <label for="Type">Type:</label>
                                <select    id="Type" class="  form-control" name="Type"   >
                                    <option></option>
                                    <option   @selected($tache->Type=="Acompte / Demande de paiement") value="Acompte / Demande de paiement">Acompte / Demande de paiement</option>
                                    <option   @selected($tache->Type=="Appel téléphonique") value="Appel téléphonique">Appel téléphonique</option>
                                    <option   @selected($tache->Type=="Avoir") value="Avoir">Avoir</option>
                                    <option   @selected($tache->Type=="Bordereau achat") value="Bordereau achat">Bordereau achat</option>
                                    <option   @selected($tache->Type=="Call") value="Call">Call</option>
                                    <option   @selected($tache->Type=="Compte poids") value="Compte poids">Compte poids</option>
                                    <option   @selected($tache->Type=="Création compte AS400") value="Création compte AS400">Création compte AS400</option>
                                    <option   @selected($tache->Type=="Demande tarif") value="Demande tarifs">Demande tarifs</option>
                                    <option   @selected($tache->Type=="Dépôt métal") value="Dépôt métal">Dépôt métal</option>
                                    <option   @selected($tache->Type=="Envoyer email") value="Envoyer email">Envoyer email</option>
                                    <option   @selected($tache->Type=="Envoyer courrier") value="Envoyer courrier">Envoyer courrier</option>
                                    <option   @selected($tache->Type=="Facture") value="Facture">Facture</option>
                                    <option   @selected($tache->Type=="Offre de prix") value="Offre de prix">Offre de prix</option>
                                    <option   @selected($tache->Type=="Ordre de Bourse sur le Fixin") value="Ordre de Bourse sur le Fixing">Ordre de Bourse sur le Fixing</option>
                                    <option   @selected($tache->Type=="Prise de command") value="Prise de commande">Prise de commande</option>
                                    <option   @selected($tache->Type=="Remise de commande") value="Remise de commande">Remise de commande</option>
                                    <option   @selected($tache->Type=="Suivi client") value="Suivi client">Suivi client</option>
                                    <option   @selected($tache->Type=="Vérification comptable interne") value="Vérification comptable interne">Vérification comptable interne</option>
                                    <option   @selected($tache->Type=="Virement") value="Virement">Virement</option>
                                </select><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">

                        <div class="col-md-2">
                            <div class="">
                                <label for="Priority">Priorité:</label>
                                <select    id="Priority" class="form-control" name="Priority"   >
                                    <option></option>
                                    <option @selected($tache->Priority=="Normal")  value="Normal">Normal</option>
                                    <option @selected($tache->Priority=="High")  value="High">High</option>
                                    <option @selected($tache->Priority=="Low")  value="Low">Low</option>
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="Status">Status:</label>
                                <select    id="Status" class="form-control" name="Status"   >
                                    <option></option>
                                    <option  @selected($tache->Status=="Not Started") value="Not Started">Not Started</option>
                                    <option  @selected($tache->Status=="Waiting on someone e") value="Waiting on someone e">Waiting on someone e</option>
                                    <option  @selected($tache->Status=="In Progress") value="In Progress">In Progress</option>
                                    <option  @selected($tache->Status=="Deferred") value="Deferred">Deferred</option>
                                    <option  @selected($tache->Status=="Completed") value="Completed">Completed</option>
                                </select><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-6">
                            <div class="">
                                <label for="Description">Description:</label>
                                <textarea  id="Description" class="form-control" name="Description"  style="min-height:150px">{{$tache->Description}}</textarea><br><br>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                @if(isset($contact))
                                <label for="Nom_contact">Nom de contact:</label>
                                <input type="text" class="form-control" name="Nom_contact" value="{{$tache->Nom_contact}}" >
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
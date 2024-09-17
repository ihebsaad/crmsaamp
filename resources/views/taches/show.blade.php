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
                <h6 class="m-0 font-weight-bold text-primary">Prise de contact {{$tache->id}} </h6>
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

                        <div class="col-md-2">
                            <div class="">
                                <label for="DateTache">Date :</label>
                                <input type="text" id="DateTache" class="form-control datepicker" name="DateTache"  value="{{date('Y-m-d', strtotime($tache->DateTache))}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="heure_debut">Heure :</label>
                                <input type="text" id="heure_debut" class="form-control" name="heure_debut"  value="{{$tache->heure_debut}}"><br><br>
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div class="">
                                <label for="Subject">Client ID:</label>
                                <input type="text" id="mycl_id" class="form-control" name="mycl_id" readonly value="{{$tache->mycl_id}}"><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div >
                                <label for="Type">Type:</label>
                                <select    id="Type" class="  form-control" name="Type"   >
                                    <option></option>
                                    <option   @selected($tache->Type=="Acompte / Demande de paiement") value="Acompte / Demande de paiement">Acompte / Demande de paiement</option>
                                    <option   @selected($tache->Type=="Appel téléphonique") value="Appel téléphonique">Appel téléphonique</option>
                                    <option   @selected($tache->Type=="Avoir") value="Avoir">Avoir</option>
                                    <option   @selected($tache->Type=="Bordereau achat") value="Bordereau achat">Bordereau achat</option>
                                    <option   @selected($tache->Type=="Bon de réception") value="Bon de réception">Bon de réception</option>
                                    <option   @selected($tache->Type=="Call") value="Call">Call</option>
                                    <option   @selected($tache->Type=="Compte poids") value="Compte poids">Compte poids</option>
                                    <option   @selected($tache->Type=="Création compte AS400") value="Création compte AS400">Création compte AS400</option>
                                    <option   @selected($tache->Type=="Demande tarif") value="Demande tarifs">Demande tarifs</option>
                                    <option   @selected($tache->Type=="Dépôt métal") value="Dépôt métal">Dépôt métal</option>
                                    <option   @selected($tache->Type=="Envoyer email") value="Envoyer email">Envoyer email</option>
                                    <option   @selected($tache->Type=="Envoyer courrier") value="Envoyer courrier">Envoyer courrier</option>
                                    <option   @selected($tache->Type=="Facture") value="Facture">Facture</option>
                                    <option   @selected($tache->Type=="Fonte") value="Fonte">Fonte</option>
                                    <option   @selected($tache->Type=="Offre de prix") value="Offre de prix">Offre de prix</option>
                                    <option   @selected($tache->Type=="Ordre de Bourse sur le Fixin") value="Ordre de Bourse sur le Fixing">Ordre de Bourse sur le Fixing</option>
                                    <option   @selected($tache->Type=="Prise de commande") value="Prise de commande">Prise de commande</option>
                                    <option   @selected($tache->Type=="Remise de commande") value="Remise de commande">Remise de commande</option>
                                    <option   @selected($tache->Type=="Suivi client") value="Suivi client">Suivi client</option>
                                    <option   @selected($tache->Type=="Vérification comptable interne") value="Vérification comptable interne">Vérification comptable interne</option>
                                    <option   @selected($tache->Type=="Virement") value="Virement">Virement</option>
                                    <option   @selected($tache->Type=="Autre") value="Autre">Autre</option>
                                </select><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Priority">Priorité:</label>
                                <select    id="Priority" class="form-control" name="Priority"   >
                                    <option></option>
                                    <option @selected($tache->Priority=="Normal")  value="Normal">Normale</option>
                                    <option @selected($tache->Priority=="High")  value="High">Haute</option>
                                    <option @selected($tache->Priority=="Low")  value="Low">Basse</option>
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="Status">Status:</label>
                                <select    id="Status" class="form-control" name="Status"   >
                                    <option></option>
                                    <option  @selected($tache->Status=="Not Started") value="Not Started">Pas commencée</option>
                                    <option  @selected($tache->Status=="Waiting on someone e") value="Waiting on someone e">En attente de quelqu'un</option>
                                    <option  @selected($tache->Status=="In Progress") value="In Progress">En cours</option>
                                    <option  @selected($tache->Status=="Deferred") value="Deferred">Reportée</option>
                                    <option  @selected($tache->Status=="Completed") value="Completed">Terminée</option>
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="Agence">Agence:</label>
                                <select    id="Agence" class="form-control" name="Agence" required  >
                                    @foreach($agences as $agence)
                                        <option @selected($tache->Agence==$agence->agence_lib) value="{{$agence->agence_lib}}">{{$agence->agence_lib}}</option>
                                    @endforeach
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
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right">Modifier</button>
                            @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                <a title="Supprimer" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('taches.destroy', $tache->id )}}" class="btn btn-danger btn-sm btn-responsive mr-2 float-right" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                    <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                                </a>
                            @endif
                        </div>
                    </div>

                </form>

            </div>

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
                //minDate:0
            });
        });

    </script>

    @endsection
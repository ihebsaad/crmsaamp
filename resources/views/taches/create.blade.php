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
                <h6 class="m-0 font-weight-bold text-primary">Créer une prise de contact </h6>
            </div>

            <div class="card-body" style="min-height:500px">

                <form action="{{ route('taches.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="ID_Compte" value="{{$client->id}}" >
                    <input type="hidden" name="ID_Compte" value="{{$client->id}}" >

                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}" >
                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div class="">
                                <label for="Subject">Sujet:</label>
                                <input type="text" id="Subject" class="form-control" name="Subject"  value="{{old('Subject')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="DateTache">Date :</label>
                                <input type="text" id="DateTache" class="form-control datepicker" name="DateTache"  value="{{old('DateTache')}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="heure_debut">Heure :</label>
                                <input type="time" id="heure_debut" class="form-control" name="heure_debut"  value="{{old('heure_debut')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="mycl_id">Client ID:</label>
                                <input type="text" id="mycl_id" class="form-control" name="mycl_id"  readonly value="{{$client->cl_ident}}"><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div >
                                <label for="Type">Type:</label>
                                <select    id="Type" class="  form-control" name="Type"   >
                                    <option></option>
                                    <option  value="Acompte / Demande de paiement">Acompte / Demande de paiement</option>
                                    <option  value="Appel téléphonique">Appel téléphonique</option>
                                    <option  value="Avoir">Avoir</option>
                                    <option  value="Bordereau achat">Bordereau achat</option>
                                    <option  value="Bon de réception">Bon de réception</option>
                                    <option  value="Call">Call</option>
                                    <option  value="Compte poids">Compte poids</option>
                                    <option  value="Création compte AS400">Création compte AS400</option>
                                    <option  value="Demande tarifs">Demande tarifs</option>
                                    <option  value="Dépôt métal">Dépôt métal</option>
                                    <option  value="Envoyer email">Envoyer email</option>
                                    <option  value="Envoyer courrier">Envoyer courrier</option>
                                    <option  value="Facture">Facture</option>
                                    <option  value="Fonte">Fonte</option>
                                    <option  value="Offre de prix">Offre de prix</option>
                                    <option  value="Ordre de Bourse sur le Fixing">Ordre de Bourse sur le Fixing</option>
                                    <option  value="Prise de commande">Prise de commande</option>
                                    <option  value="Remise de commande">Remise de commande</option>
                                    <option  value="Suivi client">Suivi client</option>
                                    <option  value="Vérification comptable interne">Vérification comptable interne</option>
                                    <option  value="Virement">Virement</option>
                                    <option  value="Autre">Autre</option>
                                </select><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Priority">Priorité:</label>
                                <select    id="Priority" class="form-control" name="Priority"   >
                                    <option></option>
                                    <option  value="Normal">Normale</option>
                                    <option  value="High">Haute</option>
                                    <option  value="Low">Basse</option>
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="Status">Status:</label>
                                <select    id="Status" class="form-control" name="Status"   >
                                    <option></option>
                                    <option  value="Not Started">Pas commencée</option>
                                    <option  value="Waiting on someone e">En attente de quelqu'un</option>
                                    <option  value="In Progress">En cours</option>
                                    <option  value="Deferred">Reportée</option>
                                    <option  value="Completed">Terminée</option>
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="Agence">Agence:</label>
                                <select    id="Agence" class="form-control" name="Agence" required  >
                                    <option></option>
                                    @foreach($agences as $agence)
                                        <option value="{{$agence->agence_lib}}">{{$agence->agence_lib}}</option>
                                    @endforeach
                                </select><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-6">
                            <div class="">
                                <label for="Description">Description:</label>
                                <textarea  id="Description" class="form-control" name="Description"  style="min-height:150px">{{old('Description')}}</textarea><br><br>
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
                //minDate:0
            });
        });

    </script>
    @endsection
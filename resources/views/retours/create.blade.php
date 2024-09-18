@extends('layouts.back')

@section('content')

<?php

?>

<style>
    .table,.table td,.table th{
        border:none!important;
    }

</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Créer une réclamation </h6>
            </div>

            <div class="card-body" style="min-height:500px">

                <form action="{{ route('retours.store') }}" method="post">
                    @csrf
                    <div class="row pt-1">
                        <!--
                        <div class="col-md-3">
                            <div class="">
                                <label for="Name">Nom:</label>
                                <input type="text" id="" readonly class="form-control" name="Name"  value="{{old('Name')}}"><br><br>
                            </div>
                        </div>-->
                        <div class="col-md-3">
                            <div >
                                <label for="Type_retour">Type de retour:</label>
                                <select    id="Type_retour" class="  form-control" name="Type_retour"   >
                                    <option></option>
                                    <option  value="Information générale">Information générale</option>
                                    <option  value="Négatif">Négatif</option>
                                    <option  value="Positif">Positif</option>
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Motif_retour">Motif de retour:</label>
                                <select    id="Motif_retour" class="  form-control" name="Motif_retour"   >
                                    <option></option>
                                    <option  value="Apprêts">Apprêts</option>
                                    <option  value="Autre">Autre</option>
                                    <option  value="Bijouterie">Bijouterie</option>
                                    <option  value="Concurrent">Concurrent</option>
                                    <option  value="Contestation des titres">Contestation des titres</option>
                                    <option  value="Délai de livraison produit/ service">Délai de livraison produit/ service</option>
                                    <option  value="Demi-produits">Demi-produits</option>
                                    <option  value="Erreur de modèle livré">Erreur de modèle livré</option>
                                    <option  value="Livraison">Livraison</option>
                                    <option  value="Marché">Marché</option>
                                    <option  value="Problème administratif">Problème administratif</option>
                                    <option  value="Produit défectueux">Produit défectueux</option>
                                    <option  value="Qualité de service">Qualité de service</option>
                                    <option  value="Réactivité">Réactivité</option>
                                    <option  value="Service en agence">Service en agence</option>
                                    <option  value="Traitement d'une réclamation">Traitement d'une réclamation</option>
                                </select><br><br>
                            </div>

                        </div>
                        <!--
                        <div class="col-md-3">
                            <div class="">
                                <label for="Responsable_de_resolution">Agence assignée:</label>
                                <select    id="Responsable_de_resolution" class="form-control" name="Responsable_de_resolution" required  >
                                    <option></option>
                                    @foreach($agences as $agence)
                                        <option value="{{$agence->agence_lib}}">{{$agence->agence_lib}}</option>
                                    @endforeach
                                </select><br><br>
                            </div>
                        </div>-->

                        <div class="col-md-3">
                            <div class="">
                                <label for="Nom_du_compte">Nom du client:</label>
                                <input type="text" id="Nom_du_compte" class="form-control" name="Nom_du_compte" readonly value="{{$client->Nom}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Division">Division:</label>
                                <select    id="Division" class="  form-control" name="Division"   >
                                    <option></option>
                                    <option  value="accueil et relation client ">accueil et relation client </option>
                                    <option  value="Affinage">Affinage</option>
                                    <option  value="Apprêts">Apprêts</option>
                                    <option  value="Autre">Autre</option>
                                    <option  value="bijouterie">Bijouterie</option>
                                    <option  value="Concurrent">Concurrent</option>
                                    <option  value="compte poids">Compte poids</option>
                                    <option  value="Demi-produits">Demi-produits</option>
                                    <option  value="facturation">Facturation</option>
                                    <option  value="investissement">Investissement</option>
                                    <option  value="laboratoire">Laboratoire</option>
                                    <option  value="Marché">Marché</option>
                                    <option  value="Qualité de service">Qualité de service</option>
                                    <option  value="Qualité des produits">Qualité des produits</option>
                                    <option  value="Rachat métaux">Rachat métaux</option>
                                    <option  value="règlement/virement">Règlement/virement</option>
                                    <option  value="Traitement d'une réclamation">Traitement d'une réclamation</option>
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_ouverture">Date d'ouverture:</label>
                                <input type="text" id="Date_ouverture" class="form-control datepicker" name="Date_ouverture"  value="{{old('Date_ouverture')}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_cloture">Date de clôture:</label>
                                <input type="text" id="Date_cloture" class="form-control datepicker" name="Date_cloture"  value="{{old('Date_cloture')}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Title">Client ID:</label>
                                <input type="text" id="Title" class="form-control" name="cl_id"  readonly value="{{$client->cl_ident}}"><br><br>
                            </div>
                        </div>
                    </div>


                    <div class="row pt-1">

                        <div class="col-md-6">
                            <div class="">
                                <label for="Details_des_causes">Détails des causes:</label>
                                <textarea  id="Details_des_causes" class="form-control" name="Details_des_causes"  style="min-height:150px">{{old('Details_des_causes')}}</textarea><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div >
                                <label for="Type_retour">Référence du lot:</label>
                                <input type="text" id="Ref_produit_lot_commande_facture" class="form-control" name="Ref_produit_lot_commande_facture"  value="{{old('Ref_produit_lot_commande_facture')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Depot_concerne ">Dépôt concerné:</label>
                                <input type="text" id="Depot_concerne" class="form-control" name="Depot_concerne"  value="{{old('Depot_concerne')}}"><br><br>
                            </div>

                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-6">
                            <div class="">
                                <label for="Une_reponse_a_ete_apportee_au_client">Réponse au client:</label>
                                <textarea  id="Une_reponse_a_ete_apportee_au_client" class="form-control" name="Une_reponse_a_ete_apportee_au_client"  style="min-height:150px">{{old('Une_reponse_a_ete_apportee_au_client')}}</textarea><br><br>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-6">
                            <div class="">
                                <label for="ID_Contact">Contact:</label>
                                <select  id="mycontact_id" class="form-control" name="mycontact_id" required  >
                                    @foreach($contacts as $contact)
                                        <option value="{{$contact->id}}">{{$contact->Prenom}} {{$contact->Nom}} - {{$contact->Title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        -->
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right">Ajouter</button>
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
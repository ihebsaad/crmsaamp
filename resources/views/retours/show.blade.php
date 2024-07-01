@extends('layouts.back')

@section('content')

<?php

?>

<style>
    h6{
        color:black;
        font-weight:bold;
    }
    .table,.table td,.table th{
        border:none!important;
    }

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
                                <label for="Name">Réference:</label>
                                <h6>{{$retour->Name}}</h6>
                                <!--<input type="text" id="Name" class="form-control" name="Name"  value="{{$retour->Name}}"><br><br>-->

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div >
                                <label for="Type_retour">Type de retour:</label><br>
                                <b  class="bg-{{$class}}" style="color:white;padding: 5px 10px;border-radius:5px">{{$retour->Type_retour}}</b>
                                <!--
                                <select  style="color:white"  id="Type_retour" class="bg-{{$class}} form-control" name="Type_retour"   >
                                    <option></option>
                                    <option @selected($retour->Type_retour=="Information générale") value="Information générale">Information générale</option>
                                    <option @selected($retour->Type_retour=="Négatif") value="Négatif">Négatif</option>
                                    <option @selected($retour->Type_retour=="Positif") value="Positif">Positif</option>
                                </select><br><br>
                                -->
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Motif_retour">Motif de retour:</label>
                                <h6>{{$retour->Motif_retour}}</h6>
                                <!--
                                <select    id="Motif_retour" class="  form-control" name="Motif_retour"   >
                                    <option></option>
                                    <option @selected($retour->Motif_retour=="Apprêts")   value="Apprêts">Apprêts</option>
                                    <option @selected($retour->Motif_retour=="Autre")  value="Autre">Autre</option>
                                    <option @selected($retour->Motif_retour=="Bijouterie") value="Bijouterie">Bijouterie</option>
                                    <option @selected($retour->Motif_retour=="Concurrent") value="Concurrent">Concurrent</option>
                                    <option @selected($retour->Motif_retour=="Contestation des titres") value="Contestation des titres">Contestation des titres</option>
                                    <option @selected($retour->Motif_retour=="Délai de livraison produit/ service") value="Délai de livraison produit/ service">Délai de livraison produit/ service</option>
                                    <option @selected($retour->Motif_retour=="Demi-produits") value="Demi-produits">Demi-produits</option>
                                    <option @selected($retour->Motif_retour=="Erreur de modèle livré") value="Erreur de modèle livré">Erreur de modèle livré</option>
                                    <option @selected($retour->Motif_retour=="Livraison") value="Livraison">Livraison</option>
                                    <option @selected($retour->Motif_retour=="Marché") value="Marché">Marché</option>
                                    <option @selected($retour->Motif_retour=="Problème administratif") value="Problème administratif">Problème administratif</option>
                                    <option @selected($retour->Motif_retour=="Produit défectueux") value="Produit défectueux">Produit défectueux</option>
                                    <option @selected($retour->Motif_retour=="Qualité de service") value="Qualité de service">Qualité de service</option>
                                    <option @selected($retour->Motif_retour=="Réactivité") value="Réactivité">Réactivité</option>
                                    <option @selected($retour->Motif_retour=="Service en agence") value="Service en agence">Service en agence</option>
                                    <option @selected($retour->Motif_retour=="Traitement d'une réclamation") value="Traitement d'une réclamation">Traitement d'une réclamation</option>
                                </select><br><br>
-->
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Responsable_de_resolution">Responsable de résolution:</label>
                                <h6>{{$retour->Responsable_de_resolution}}</h6>
                                <!--
                                <input type="text" id="Responsable_de_resolution" class="form-control" name="Responsable_de_resolution"  value="{{$retour->Responsable_de_resolution}}"><br><br>
                                -->
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Division">Client:</label>
                                <h6>{{$retour->Nom_du_compte}}</h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Division">Division:</label>
                                <h6>{{$retour->Division}}</h6>
                                <!--
                                <select    id="Division" class="  form-control" name="Division"   >
                                    <option></option>
                                    <option  @selected($retour->Division=="accueil et relation client") value="accueil et relation client">accueil et relation client </option>
                                    <option  @selected($retour->Division=="Affinage")  value="Affinage">Affinage</option>
                                    <option  @selected($retour->Division=="Apprêts")  value="Apprêts">Apprêts</option>
                                    <option  @selected($retour->Division=="Autre")  value="Autre">Autre</option>
                                    <option  @selected($retour->Division=="bijouterie")  value="bijouterie">Bijouterie</option>
                                    <option  @selected($retour->Division=="Concurrent")  value="Concurrent">Concurrent</option>
                                    <option  @selected($retour->Division=="compte poids")  value="compte poids">Compte poids</option>
                                    <option  @selected($retour->Division=="Demi-produits")  value="Demi-produits">Demi-produits</option>
                                    <option  @selected($retour->Division=="facturation")  value="facturation">Facturation</option>
                                    <option  @selected($retour->Division=="investissement")  value="investissement">Investissement</option>
                                    <option  @selected($retour->Division=="laboratoire")  value="laboratoire">Laboratoire</option>
                                    <option  @selected($retour->Division=="Marché")  value="Marché">Marché</option>
                                    <option  @selected($retour->Division=="Qualité de service")  value="Qualité de service">Qualité de service</option>
                                    <option  @selected($retour->Division=="Qualité des produits")  value="Qualité des produits">Qualité des produits</option>
                                    <option  @selected($retour->Division=="Rachat métaux")  value="Rachat métaux">Rachat métaux</option>
                                    <option  @selected($retour->Division=="règlement/virement")  value="règlement/virement">Règlement/virement</option>
                                    <option  @selected($retour->Division=="Traitement d'une réclamation")  value="Traitement d'une réclamation">Traitement d'une réclamation</option>
                                </select><br><br>
-->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_ouverture">Date d'ouverture:</label>
                                <h6>{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</h6>
                                <!--<input type="text" id="Date_ouverture" class="form-control" name="Date_ouverture"  value="{{$retour->Date_ouverture}}"><br><br>-->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_cloture">Date de clôture:</label>
                                <h6>{{date('d/m/Y', strtotime($retour->Date_cloture))}}</h6>
                                <!--<input type="text" id="Date_cloture" class="form-control datepicker" name="Date_cloture"  disabled value="{{$retour->Date_cloture}}"><br><br>-->
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
                            <div class="mt-2">
                                <h4>Contact</h4>
                                @if(isset($contact))
                                <table class="table">
                                <tr><td colspan="2"><i class="fas fa-user mr-2"></i>  {{$contact->Prenom}} {{$contact->Nom}}  {{$contact->Prenom}}</td></tr>
                                <tr><td colspan="2"><i class="fas fa-briefcase  mr-2"></i> {{$contact->Title}}</td></tr>
                                <tr><td  ><i class="fas fa-mobile  mr-2"></i> {{$contact->MobilePhone}}</td><td> <i class="fas fa-phone mr-2"></i> {{$contact->Phone}}</td></tr>
                                <tr><td colspan="2"><i class="fas fa-envelope  mr-2"></i> {{$contact->Email}}</td></tr>
                                <tr><td colspan="2"><i class="fas fa-store mr-2"></i> {{$contact->Compte}}</td></tr>
                                <tr><td colspan="2"><i class="fas fa-info  mr-2"></i> {{$contact->Description}}</td></tr>
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
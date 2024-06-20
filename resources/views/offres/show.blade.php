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
                <h6 class="m-0 font-weight-bold text-primary">Offre {{$offre->id}} </h6>
            </div>

            <div class="card-body" style="min-height:500px">

                <form action="{{ route('offres.update', $offre->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div class="">
                                <label for="Nom_offre">Nom:</label>
                                <input type="text" id="Nom_offre" class="form-control" name="Nom_offre"  value="{{$offre->Nom_offre}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_creation">Date :</label>
                                <input type="text" id="Date_creation" class="form-control datepicker" name="Date_creation"  value="{{$offre->Date_creation}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div >
                                <label for="Produit_Service">Produit Service:</label>
                                <select    id="Produit_Service" class="  form-control" name="Produit_Service"   >
                                    <option></option>
                                    <option   @selected($offre->Produit_Service=="AFFINAGE - OFFRE GLOBALE") value="AFFINAGE - OFFRE GLOBALE">AFFINAGE - OFFRE GLOBALE</option>
                                    <option   @selected($offre->Produit_Service=="AFFINAGE - OFFRE GLOBALE;BIJOUTERIE - OFFRE GLOBAL;DP - OFFRE GLOBALE") value="AFFINAGE - OFFRE GLOBALE;BIJOUTERIE - OFFRE GLOBAL;DP - OFFRE GLOBALE">AFFINAGE - OFFRE GLOBALE;BIJOUTERIE - OFFRE GLOBAL;DP - OFFRE GLOBALE</option>
                                    <option   @selected($offre->Produit_Service=="AFFINAGE - Balayures et déchets Industriels;AFFINAGE - Broutilles;AFFINAGE - Limaille") value="AFFINAGE - Balayures et déchets Industriels;AFFINAGE - Broutilles;AFFINAGE - Limaille">AFFINAGE - Balayures et déchets Industriels;AFFINAGE - Broutilles;AFFINAGE - Limaille</option>
                                    <option   @selected($offre->Produit_Service=="AFFINAGE - Balayures et déchets Industriels") value="AFFINAGE - Balayures et déchets Industriels">AFFINAGE - Balayures et déchets Industriels</option>
                                    <option   @selected($offre->Produit_Service=="AFFINAGE - Broutilles") value="AFFINAGE - Broutilles">AFFINAGE - Broutilles</option>
                                    <option   @selected($offre->Produit_Service=="AFFINAGE - Limaille") value="AFFINAGE - Limaille">AFFINAGE - Limaille</option>
                                    <option   @selected($offre->Produit_Service=="APPRET - Tiges montées") value="APPRET - Tiges montées">APPRET - Tiges montées</option>
                                    <option   @selected($offre->Produit_Service=="BIJOUTERIE - Chaînages terminés;BIJOUTERIE - Créoles;BIJOUTERIE - Joncs massifs;BIJOUTERIE - Mailles creuses classiques") value="BIJOUTERIE - Chaînages terminés;BIJOUTERIE - Créoles;BIJOUTERIE - Joncs massifs;BIJOUTERIE - Mailles creuses classiques">BIJOUTERIE - Chaînages terminés;BIJOUTERIE - Créoles;BIJOUTERIE - Joncs massifs;BIJOUTERIE - Mailles creuses classiques</option>
                                    <option   @selected($offre->Produit_Service=="BIJOUTERIE - Chaînages terminés") value="BIJOUTERIE - Chaînages terminés">BIJOUTERIE - Chaînages terminés</option>
                                    <option   @selected($offre->Produit_Service=="DP - Tube") value="DP - Tube">DP - Tube</option>
                                    <option   @selected($offre->Produit_Service=="DP - Fil rond") value="DP - Fil rond">DP - Fil rond</option>
                                    <option   @selected($offre->Produit_Service=="INVEST - Lingots titrés;INVEST - Pièces monétaire") value="INVEST - Lingots titrés;INVEST - Pièces monétaire">INVEST - Lingots titrés;INVEST - Pièces monétaire</option>
                                    <option   @selected($offre->Produit_Service=="INVEST - Lingots titrés") value="INVEST - Lingots titrés;">INVEST - Lingots titrés</option>
                                    <option   @selected($offre->Produit_Service=="INVEST - Pièces monétaire") value="INVEST - Pièces monétaire">INVEST - Pièces monétaire</option>
                                </select><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">

                        <div class="col-md-2">
                            <div class="">

                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">

                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-6">
                            <div class="">
                                <label for="Description">Description:</label>
                                <textarea  id="Description" class="form-control" name="Description"  style="min-height:150px">{{$offre->Description}}</textarea><br><br>
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
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
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Create an offer')}}</h6>
            </div>

            <div class="card-body" style="min-height:500px">

                <form action="{{ route('offres.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="cl_id" value="{{$client->cl_ident}}" >
                    <input type="hidden" name="mycl_id" value="{{$client->id}}" >
                    <input type="hidden" name="nom_compte" value="{{$client->Nom}}" >
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}" >
                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Nom_offre">{{__('msg.Name')}}:</label>
                                <input type="text" id="Nom_offre" class="form-control" name="Nom_offre"  value="{{old('Nom_offre')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_creation">{{__('msg.Date')}} :</label>
                                <input type="text" id="Date_creation" class="form-control datepicker" name="Date_creation"  value="{{old('Date_creation')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Type">{{__('msg.Type')}}:</label>
                                <select    id="type" class="form-control" name="type" required  >
                                    <option  value="TG">TG</option>
                                    <option  value="Hors TG - Affinage">Hors TG - Affinage</option>
                                    <option  value="Hors TG - Apprêts/Bij/DP">Hors TG - Apprêts/Bij/DP</option>
                                </select><br><br>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-3">
                            <div >
                                <label for="Produit_Service">{{__('msg.Service product')}}:</label>
                                <select    id="Produit_Service" class="  form-control" name="Produit_Service"   >
                                    <option></option>
                                    <option  value="AFFINAGE">{{__('msg.Refining')}}</option>
                                    <option  value="APPRET">APPRET{{__('msg.Primer')}}</option>
                                    <option  value="BIJOUTERIE">{{__('msg.Jewelry')}}</option>
                                    <option  value="DP">DP</option>
                                    <option  value="INVEST">INVEST</option>
                                </select><br><br>
                            </div>
                        </div>-->

                    </div>

                    <div class="row pt-1">


                        <div class="col-md-4">
                            <div class="">
                                <label for="Description">{{__('msg.Description')}}:</label>
                                <textarea  id="Description" class="form-control" name="Description"  style="min-height:150px">{{old('Description')}}</textarea><br><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="Nom_offre">{{__('msg.File(s)')}}:</label>
                                <input type="file" id="fichier" class="form-control" name="files[]"  multiple required  accept="application/pdf" /><br><br>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="">
                                <label for="date_relance">Date de relance :</label>
                                <input type="text" id="date_relance" class="form-control datepicker" name="date_relance"  value="{{date('Y-m-d', time() + 604800)}}"><br><br>
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
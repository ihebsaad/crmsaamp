@extends('layouts.back')

@section('content')

<?php

?>


    <style>


    </style>
    <div class="row">

    <!-- Content Column -->
    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">État financier du client {{$client->Nom}} - {{$client->cl_ident}} </h6>
            </div>
            <div class="card-body">
                <a href="{{route('fiche',['id'=>$client->id])}}"  class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-user-circle"></i> Fiche</a><!-- <a href="{{route('phone',['id'=>$client->id])}}"  class="btn btn-primary mb-3 float-right"><i class="fas fa-phone-alt"></i> Télephonie</a>-->
                <div class="clearfix"></div>
                <form id="">
                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Code_Siren">SIREN:</label>
                                <input type="text" id="Code_Siren" class="form-control" name="Code_siren"  value="{{$client->Code_siren}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_Ouverture_AS400">Date de création:</label>
                                <input type="text" id="date_ouverture_AS400" class="form-control" name="date_ouverture_AS400" value="{{$client->date_ouverture_AS400}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Nom_du_dirigeant">Nom du dirigeant:</label>
                                <input type="text" id="Nom_du_dirigeant" class="form-control" name="Nom_du_dirigeant" value="{{$client->Nom_du_dirigeant}}"><br><br>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="siret">SIRET:</label>
                                <input type="text" id="Code_siret" class="form-control" name="siret" value="{{$client->Code_siret}}" ><br><br>
                            </div>
                        </div>
<!--
                        <div class="col-md-3">
                            <div class="">
                                <label for="Statut_juridique">Statut juridique:</label>
                                <input type="text" id="Statut_juridique" class="form-control" name="Statut_juridique" value="{{$client->Statut_juridique}}">
                            </div>
                        </div>-->
                        <div class="col-md-3">
                            <div class="">
                                <label for="Siege_social">Siège social:</label>
                                <input type="text" id="Siege_social" class="form-control" name="Siege_social" value="{{$client->Siege_social}}">
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">

                        <div class="col-md-3">
                            <div class="">
                                <label for="N_Tva">N° TVA:</label>
                                <input type="text" id="num_tva" class="form-control" name="num_tva" value="{{$client->num_tva}}">
                            <br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="NAF">NAF:</label>
                                <input type="text" id="NAF" class="form-control" name="NAF" value="{{$client->NAF}}">
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Score de solvabilité:</label>
                                <input type="text" id="" class="form-control" name="" value="{{$client->score_solvabilite}}" ><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Limite_credit">Limite crédit "SAAMP":</label>
                                <input  id="Limite_credit" class="form-control" name="Limite_credit"  value="{{$client->Limite_credit}}">
                                   <br><br>
                            </div>
                        </div>

                    </div>


                </form>


            </div>
        </div>
    </div>


     <script>
    </script>
@endsection
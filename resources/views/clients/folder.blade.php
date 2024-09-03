@extends('layouts.back')

@section('content')

<?php

?>

<div class="row">

    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Dossier du client {{$client->id}} - {{$client->Nom}}</h6>
            </div>
            <div class="card-body" style="min-height:300px">

                <form action="{{route('ouverture')}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row pt-1">
                        <div class="col-md-4">
                            <label for="files">Sélectionnez des fichiers :</label>
                            <input class="form-control" type="file" id="files" name="files[]" multiple required>
                        </div>

                        <div class="col-md-4">
                            <label>Type de depôt :</label>
                            <select  class="form-control" name="type">
                                <option  value="1">DOCUMENTS OUVERTURE DE COMPTE POIDS</option>
                                <option  value="2">PRINCIPES ET CODE DES PRATIQUES DU RJC ET DE SAAMP</option>
                                <option  value="3">DECLARATION : DUE DILIGENCE</option>
                                <option  value="4">CNI OU PASSEPORT</option>
                                <option  value="5">KBIS DE MOINS DE 3 MOIS OU REPERTOIRE DES METIERS</option>
                                <option  value="6">DECLARATION D'EXISTENCE AUPRES DE LA GARANTIE</option>
                                <option  value="7">LETTRE DE FUSION</option>
                                <option  value="8">RIB</option>
                            </select>
                        </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn-primary btn mt-4 ml-5">Ajouter</button>
                            </div>
                    </div>




                </form>

            </div>
        </div>
    </div>

</div>

@endsection
@extends('layouts.back')

@section('content')

<?php

// Traitement d'un appel
if (isset($_GET['call'])) {
    $number = $_GET['number'];
    $autoanswer = isset($_GET['autoanswer']) ? 'false' : 'true';

    $callUrl = "https://api.telavox.se/dial/{$number}?autoanswer={$autoanswer}";

    $callCh = curl_init($callUrl);
    curl_setopt($callCh, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $client->token_phone));
    curl_setopt($callCh, CURLOPT_RETURNTRANSFER, true);

    $callResponse = curl_exec($callCh);
    $callResult = json_decode($callResponse, true);
    curl_close($callCh);

    if ($callResult && $callResult['message'] === 'OK') {
        echo '<p class="text-success">Appel initié avec succès !</p>';
    } else {
        echo '<p class="text-danger">Échec de l\'initiation de l\'appel.</p>';
    }
}

?>


    <style>


    </style>
    <div class="row">

    <!-- Content Column -->
    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Fiche du client {{$client->id}} - {{$client->Nom}} - {{$client->cl_ident}} </h6>
            </div>
            <div class="card-body">
            <a href="{{route('compte_client.show',['id'=>$client->id])}}"  class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-user-edit"></i> Modifier</a><a href="{{route('finances',['id'=>$client->id])}}"  class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-money-bill-wave"></i> Finances</a> <a href="{{route('taches.client_list',['id'=>$client->id])}}"  class="btn btn-primary mb-3 float-right"><i class="fas fa-tasks"></i> Tâches</a> <a href="{{route('offres.client_list',['id'=>$client->id])}}"  class="btn btn-primary mb-3 mr-3 float-right"><i class="fas fa-gift"></i> Offres</a>
                    <div class="clearfix"></div>
                <form id="">
                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div class="">
                                <label for="Nom">Nom:</label>
                                <input type="text" id="Nom" class="form-control" name="Nom"  value="{{$client->Nom}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="BillingAddress_city">Ville:</label>
                                <input type="text" id="BillingAddress_city" class="form-control" name="BillingAddress_city" value="{{$client->BillingAddress_city}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="">
                                <label for="postalCode">CP:</label>
                                <input type="text" id="postalCode" class="form-control" name="postalCode" value="{{$client->postalCode}}" ><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="Pays">Pays:</label>
                                <input type="text" id="Pays" class="form-control" name="Pays" value="{{$client->Pays}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Rue">Adresse:</label>
                                <input type="text" id="Rue" class="form-control" name="Rue" value="{{$client->Rue}}"><br><br>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">

                        <div class="col-md-2">
                            <div class="">
                                <label for="agence">Agence:</label>
                                <select  type="text" id="Agence" class="form-control" name="Agence"  >
                                    <option @if($client->Agence=="") selected="selected" @endif value=""></option>
                                    <option @if($client->Agence=="Aubagne") selected="selected" @endif value="Aubagne" >Aubagne</option>
                                    <option @if($client->Agence=="BORDEAUX") selected="selected" @endif value="BORDEAUX">Bordeaux</option>
                                    <option @if($client->Agence=="De Gaulle") selected="selected" @endif value="De Gaulle">De Gaulle</option>
                                    <option @if($client->Agence=="Galmot") selected="selected" @endif value="Galmot">Galmot</option>
                                    <option @if($client->Agence=="Lyon") selected="selected" @endif value="Lyon">Lyon</option>
                                    <option @if($client->Agence=="Marseille") selected="selected" @endif value="Marseille">Marseille</option>
                                    <option @if($client->Agence=="NICE") selected="selected" @endif value="NICE">Nice</option>
                                    <option @if($client->Agence=="PARIS145") selected="selected" @endif value="PARIS145">Paris</option>
                                    <option @if($client->Agence=="Toulouse") selected="selected" @endif value="Toulouse">Toulouse</option>
                                    <option @if($client->Agence=="Varsovie") selected="selected" @endif value="Varsovie">Varsovie</option>
                                    <option @if($client->Agence=="OUTRE MER") selected="selected" @endif value="OUTRE MER">Outre Mer</option>
                                </select><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Proprietaire">Propriétaire:</label>
                                <input type="text" id="Proprietaire" class="form-control" name="Proprietaire" value="{{$client->Proprietaire}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Commercial">Commercial:</label>
                                <input type="text" id="Commercial" class="form-control" name="Commercial" value="{{$client->Commercial}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="ADV">ADV:</label>
                                <input type="text" id="ADV" class="form-control" name="ADV" value="{{$client->ADV}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Client_Prospect">Clientèle:</label>
                                <input  id="Client_Prospect" class="form-control" name="Fidelite_du_client_c"  value="{{$client->Fidelite_du_client_c}}">
                                   <br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Client_Prospect">Type:</label>
                                <select  id="Client_Prospect" class="form-control" name="Client_Prospect">
                                    <option  @if($client->Client_Prospect=="") selected="selected" @endif value="" ></option>
                                    <option  @if($client->Client_Prospect=="CLIENT SAAMP") selected="selected" @endif value="CLIENT SAAMP">Client SAAMP</option>
                                    <option  @if($client->Client_Prospect=="COMPTE PROSPECT") selected="selected" @endif value="COMPTE PROSPECT">Prospect</option>
                                    <option  @if($client->Client_Prospect=="ETABLISSEMENT FERME / COMPTE INACTIF") selected="selected" @endif value="ETABLISSEMENT FERME / COMPTE INACTIF">Fermé / Inactif</option>
                                    <option  @if($client->Client_Prospect=="CLIENT LFMP") selected="selected" @endif value="CLIENT LFMP">Client LFMP</option>
                                </select><br><br>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-2">
                            <div class="">
                                <label for="Phone">Télephone:</label>
                                <input type="text" id="Phone" class="form-control" name="Phone" value="{{$client->Phone}}">
                                <br><br>
                            </div>

                        </div>
                        <!--
                        <div class="col-md-1 pt-4">
                            <form method="get" class="phone-form">
                                <input type="hidden" id="number" name="number" value="{{str_replace(' ', '', $client->Phone)}}">
                                <button type="submit" name="call" class="btn btn-success" title="Appeler"><i class="fa-2x mt-2 fas fa-phone-square"></i></button>
                            </form>
                        </div>-->
                        <div class="col-md-2">
                            <div class="">
                                <label for="">Email:</label>
                                <input type="email" id="email" class="form-control" name="email" value="{{$client->email}}" ><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="">Site Web:</label>
                                <input type="url" id="site" class="form-control" name="url" value="{{$client->url}}"><br><br>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-4">
                            <button type="submit" class="btn-primary btn">Ajouter</button>
                        </div>
                        -->
                    </div>

                </form>


            </div>
        </div>
    </div>


    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statistiques </h6>
            </div>

            <div class="card-body" style="min-height:500px">

            </div>
        </div>

    </div>

    <div class="col-lg-4 col-sm-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Réclamations </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">
                <a href="{{route('retours.create',['id'=>$client->id])}}"  class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-plus"></i> Ajouter</a>

                <table class="table table-striped mb-40">
                    <thead >
                        <tr><th>Titre</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @foreach($retours as $retour)
                            <tr><td><a href="{{route('retours.show',['id'=>$retour->id])}}">{{$retour->Name}}</a></td><td>{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="col-lg-4 col-sm-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Évènements </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">
                <table class="table table-striped mb-40">
                    <thead >
                        <tr><th>Date</th><th>Num</th></tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach($appels as $appel)
                            @if( str_replace(' ', '', $appel['number']) ==  str_replace(' ', '', $client->Phone ) )
                                @php $i++; $date= htmlspecialchars(date('d/m/Y H:i', strtotime($appel['datetime']))); @endphp
                                <tr><td>{{$date}}</td><td><i class="fas fa-phone-square-alt"></i> {{ htmlspecialchars($appel['number']) }}</td></tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>

    <div class="col-lg-4 col-sm-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Contacts </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">
                <a href="{{route('contacts.create',['id'=>$client->id])}}"  class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-plus"></i> Ajouter</a>

                <table class="table table-striped mb-40">
                    <thead >
                        <tr><th>Nom</th><th>Prénom</th><th>Tél</th></tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                            <tr><td><a href="{{route('contacts.show',['id'=>$contact->id])}}">{{$contact->Nom}}</td><td>{{$contact->Prenom}}</td><td>{{$contact->MobilePhone}}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
    </script>
@endsection
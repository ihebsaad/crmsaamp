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
        h6{
            color:black;
            font-weight:bold;
        }

    </style>
    <div class="row">

    <!-- Content Column -->
    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Fiche du client {{$client->id}} - {{$client->Nom}} - {{$client->cl_ident}} </h6>
            </div>
            <div class="card-body">
            <a href="{{route('compte_client.show',['id'=>$client->id])}}"  class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-user-edit"></i> Modifier</a><a href="{{route('finances',['id'=>$client->id])}}"  class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-money-bill-wave"></i> Finances</a> <a href="#"  class="btn btn-primary mb-3 float-right"><i class="fas fa-calendar-day"></i> Prise de rendez-vous</a> <a href="{{route('offres.client_list',['id'=>$client->id])}}"  class="btn btn-primary mb-3 mr-3 float-right"><i class="fas fa-gift"></i> Offres</a>
                    <div class="clearfix"></div>
                <form id="">
                    <div class="row pt-1">
                        <div class="col-md-2">
                            <div class="">
                                <label for="Nom">Nom:</label>
                                <h6>{{$client->Nom}}</h6>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="postalCode">CP:</label>
                                <h6>{{$client->postalCode}}</h6>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="BillingAddress_city">Ville:</label>
                                <h6>{{$client->BillingAddress_city}}</h6>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="Pays">Pays:</label>
                                <h6>{{$client->Pays}}</h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="Rue">Adresse:</label>
                                <h6>{{$client->Rue}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">

                        <div class="col-md-2">
                            <div class="">
                                <label for="agence">Agence:</label>
                                <h6>{{$client->Agence}}</h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Proprietaire">Propriétaire:</label>
                                <h6>{{$client->Proprietaire}}</h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Commercial">Commercial:</label>
                                <h6>{{$client->Commercial}}</h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="ADV">ADV:</label>
                                <h6>{{$client->ADV}}</h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Client_Prospect">Clientèle:</label>
                                <h6>{{$client->Fidelite_du_client_c}}</h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Client_Prospect">Type:</label>
                                <h6>{{$client->Client_Prospect}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-2">
                            <div class="">
                                <label for="Phone">Télephone:</label>
                                <h6>{{$client->Phone}}</h6>
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
                                <h6>{{$client->email}}</h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="">Site Web:</label>
                                <h6>{{$client->url}}</h6>
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
        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statistiques </h6>
            </div>

            <div class="card-body" style="min-height:500px">

            </div>
        </div>

    </div>

    <div class="col-lg-4 col-sm-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Réclamations </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">
                <a href="{{route('retours.create',['id'=>$client->id])}}"  class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-plus"></i> Ajouter</a>
                <div class="table-container">
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

    </div>

    <div class="col-lg-4 col-sm-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Évènements </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">
                <div class="table-container">
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

    </div>

    <div class="col-lg-4 col-sm-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Contacts </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">
                <a href="{{route('contacts.create',['id'=>$client->id])}}"  class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-plus"></i> Ajouter</a>
                <div class="table-container">
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

    </div>

    <script>
    </script>
@endsection
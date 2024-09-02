@extends('layouts.back')

@section('content')

<?php

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" integrity="sha512-h9FcoyWjHcOcmEVkxOfTLnmZFWIH0iZhZT1H2TbOq55xssQGEJHEaIm+PgoUaZbRvQTNTluNOEfb1ZRy6D3BOw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="row">

    <!-- Content Column -->
    <div class="col-lg-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recherche des clients</h6>
            </div>
            <div class="card-body">
                <a href="{{route('compte_client.create')}}"  class="btn btn-primary  ml-3 float-right"><i class="fas fa-user-plus"></i> Ajouter un prospect</a><div class="clearfix"></div>
                <form>
                    <div class="row pt-1">
                        <div class="col-md-6">
                            <div class="">
                                <label for="">Partie du nom</label>
                                <input type="" class="form-control" id="" placeholder="" name="Nom" value="{{ $request->Nom ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-6 pt-1">
                            <div class="form-check form-check-inline mb-3 mt-4">
                                <input class="form-check-input mt-2" type="radio" id="clientsUniquement" value="0" name="type" @if($request->type==0 || $request->type=='' ) checked @endif  >
                                <label class="form-check-label mt-2" for="clientsUniquement">Tous</label>
                            </div>
                            <div class="form-check form-check-inline mb-3 mt-4">
                                <input class="form-check-input mt-2" type="radio" id="clientsUniquement" value="1" name="type" @if($request->type==1) checked @endif  >
                                <label class="form-check-label mt-2" for="clientsUniquement">Clients uniquement</label>
                            </div>

                            <div class="form-check form-check-inline mb-3 mt-4">
                                <input class="form-check-input mt-2" type="radio" id="prospectUniquement" value="2" name="type" @if($request->type==2) checked @endif >
                                <label class="form-check-label mt-2" for="prospectUniquement">Prospect uniquement</label>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Adresse</label>
                                <input type="" class="form-control" id="" placeholder="" name="Rue" value="{{ $request->Rue ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Liste triée par</label>
                                <select  class="form-control" id=""  name="tri">
                                    <option value="1" @if($request->tri==1) selected="selected" @endif >Nom</option>
                                    <option value="2"  @if($request->tri==2) selected="selected" @endif>Distance</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row pt-1">
                        <div class="col-md-2">
                            <div class="">
                                <label for="">Ville</label>
                                <input type="" class="form-control" id="" placeholder="" name="BillingAddress_city" value="{{ $request->BillingAddress_city ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="">Département</label>
                                <input type="number" class="form-control" id="" placeholder="" name="Departement" value="{{ $request->Departement ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="">Pays</label>
                                <input type="" class="form-control" id="" placeholder="" name="Pays" value="{{ $request->Pays ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3 pt-1">
                            <button type="submit" class="btn btn-primary float-right mt-4">Recherche</button>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-6">
                            <!-- Liste des résultats -->
                            <div style="height: 400px;  margin-top: 20px; overflow:  auto;width:100%  ">
                                <table class="table table-striped mb-40">
                                    <thead >
                                        <tr><th>Nom</th><th>Ville</th><th>Agence</th><th>Type</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($clients as $client)
                                            @php $color='gray';
                                            switch ($client->Client_Prospect) {
                                            case 'CLIENT SAAMP' :  $color='#2660c3'; break;
                                            case 'COMPTE PROSPECT' : $color='#2ab62c'; break;
                                            case 'ETABLISSEMENT FERME / COMPTE INACTIF' : $color='#ff2e36';  break;
                                            }
                                              @endphp
                                            <tr><td><a href="{{route('fiche',['id'=>$client->id])}}">{{$client->Nom}}</a></td><td>{{$client->BillingAddress_city}}</td><td>{{$client->Agence}}</td><td style="color:{{$color}}">{{$client->Client_Prospect}}</td></tr>
                                        @endforeach
                                    <tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Carte (à intégrer avec une API de carte si nécessaire) -->
                            <div id="map" style="height: 400px; border: 1px solid #000; margin-top: 20px;">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>



    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js" integrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/kvn9pG1NpYfqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('map').setView([46.603354, 1.888334], 6); // Centrer la carte sur la France avec un zoom plus élevé

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var clients = {!! json_encode($clients) !!};

        // Define different icons for each client type
        var iconOptions = {
            'CLIENT SAAMP': 'blue',
            'COMPTE PROSPECT': 'green',
            'ETABLISSEMENT FERME / COMPTE INACTIF': 'red'
        };

        // Helper function to create an icon
        function createIcon(color) {
            return new L.Icon({
                iconUrl: 'https://crm.mysaamp.com/img/marker-'+color+'.png',

                iconSize: [32, 32],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
        }

        clients.forEach(function (client) {
            var clientType = client.Client_Prospect;
            var color = iconOptions[clientType] || 'gray'; // Default to gray if type is unknown
            var marker = L.marker([client.latitude, client.longitude], { icon: createIcon(color) }).addTo(map);
            marker.bindPopup('<b>' + client.Nom + '</b><br>' + client.Rue);
        });
    });
</script>
@endsection


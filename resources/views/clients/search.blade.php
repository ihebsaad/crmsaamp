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
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Search customers')}}</h6>
            </div>
            <div class="card-body">
                <a href="{{route('compte_client.create')}}"  class="btn btn-primary  ml-3 float-right"><i class="fas fa-user-plus"></i> {{__('msg.Add')}} {{__('msg.a prospect')}}</a><div class="clearfix"></div>
                <form action="{{route('search')}}">
                    <input type="hidden" name="sort" value="{{isset(request()->sort) ? request()->sort :'Nom'}}"/>
                    <input type="hidden" name="direction"  value="{{isset(request()->direction) ? request()->direction :'asc'}}"/>
                    <div class="row pt-1">
                        <div class="col-lg-3">
                            <div class="">
                                <label for="">{{__('msg.Part of the name')}}</label>
                                <input type="" class="form-control" id="" placeholder="" name="Nom" value="{{ $request->Nom ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="">
                                <label for="">{{__('msg.Client ID')}}</label>
                                <input type="number" class="form-control" id="client_id" placeholder="" name="client_id" value="{{ $request->client_id ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-6 pt-1">
                            <div class="form-check form-check-inline mb-3 mt-4">
                                <input class="form-check-input mt-2" type="radio" id="tous" value="0" name="type" @if($request->type==0 || $request->type=='' ) checked @endif  >
                                <label class="form-check-label mt-2" for="tous">{{__('msg.All')}}</label>
                            </div>
                            <div class="form-check form-check-inline mb-3 mt-4">
                                <input class="form-check-input mt-2" type="radio" id="clientsUniquement" value="2" name="type" @if($request->type==2) checked @endif  >
                                <label class="form-check-label mt-2" for="clientsUniquement">{{__('msg.Customers only')}}</label>
                            </div>

                            <div class="form-check form-check-inline mb-3 mt-4">
                                <input class="form-check-input mt-2" type="radio" id="prospectUniquement" value="1" name="type" @if($request->type==1) checked @endif >
                                <label class="form-check-label mt-2" for="prospectUniquement">{{__('msg.Prospects only')}}</label>
                            </div>
                        </div>

                    </div>
                    <div class="row pt-1">
                        <div class="col-lg-3 col-sm-6">
                            <div class="">
                                <label for="">{{__('msg.Address')}}</label>
                                <input type="" class="form-control" id="" placeholder="" name="adresse1" value="{{ $request->adresse1 ?? '' }}">
                            </div>
                        </div>
                        <!--
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Liste triée par</label>
                                <select  class="form-control" id=""  name="tri">
                                    <option value="1" @if($request->tri==1) selected="selected" @endif >Nom</option>
                                    <option value="2"  @if($request->tri==2) selected="selected" @endif>Distance</option>
                                </select>
                            </div>
                        </div>-->




                        <div class="col-lg-2 col-sm-6">
                            <div class="">
                                <label for="">{{__('msg.City')}}</label>
                                <input type="" class="form-control" id="" placeholder="" name="ville" value="{{ $request->ville ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6">
                            <div class="">
                                <label for="">{{__('msg.Department')}}</label>
                                <input type="text" class="form-control" id="" placeholder="" name="zip" value="{{ $request->zip ?? '' }}">
                            </div>
                        </div>
                        <!--
                        <div class="col-md-2">
                            <div class="">
                                <label for="">Pays</label>
                                <input type="" class="form-control" id="" placeholder="" name="Pays" value="{{ $request->Pays ?? '' }}">
                            </div>
                        </div>
                        -->
                        <div class="col-lg-2 col-md-12 col-sm-12 pt-1">
                            <button type="submit" class="btn btn-primary float-right mt-4"><i class="fa fa-search"></i> {{__('msg.Search')}}</button>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 pt-1">
                            <button type="submit" name="print" value="true" class="btn btn-secondary float-right mt-4"  ><i class="fa fa-print"></i> {{__('msg.Print')}}</button>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-6">
                            <!-- Liste des résultats -->
                            <div style="height: 400px;  margin-top: 20px; overflow:  auto;width:100%  ">
                                <table class="table table-striped mb-40">
                                <thead>
                                    <tr>
                                        <th>
                                            <a href="{{ route('search', array_merge(request()->all(), ['sort' => 'Nom', 'direction' => request()->direction == 'asc' ? 'desc' : 'asc'])) }}">
                                                {{__('msg.Name')}} @if(request()->sort == 'Nom')<i class="fa fa-sort-{{ request()->direction == 'asc' ? 'up' : 'down' }}"></i>@endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('search', array_merge(request()->all(), ['sort' => 'ville', 'direction' => request()->direction == 'asc' ? 'desc' : 'asc'])) }}">
                                                {{__('msg.City')}} @if(request()->sort == 'ville')<i class="fa fa-sort-{{ request()->direction == 'asc' ? 'up' : 'down' }}"></i>@endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('search', array_merge(request()->all(), ['sort' => 'agence', 'direction' => request()->direction == 'asc' ? 'desc' : 'asc'])) }}">
                                                {{__('msg.Agency')}} @if(request()->sort == 'agence')<i class="fa fa-sort-{{ request()->direction == 'asc' ? 'up' : 'down' }}"></i>@endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('search', array_merge(request()->all(), ['sort' => 'etat_id', 'direction' => request()->direction == 'asc' ? 'desc' : 'asc'])) }}">
                                                {{__('msg.Type')}} @if(request()->sort == 'etat_id')<i class="fa fa-sort-{{ request()->direction == 'asc' ? 'up' : 'down' }}"></i>@endif
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        @foreach($clients as $client)

                                            @php $color='';
                                            //$agenceLib = $client->agence ? $client->agence->agence_lib : '';
                                            $agenceLib = $agences[$client->agence_ident] ?? '';
                                            $type_c='';
                                            switch ($client->etat_id) {
                                            case 2 : /* $color='#2660c3';*/ $type_c='Client' ; break;
                                            case 1 : /*$color='#2ab62c';*/ $type_c='Prospect' ;break;
                                            case 3 : /*$color='#ff2e36';*/ $type_c='Fermé' ; break;
                                            case 4 : /*$color='#ff2e36'; */ $type_c='Inactif' ; break;

                                            }
                                            $color= $client->couleur_html ?? '#2660c3';

                                              @endphp
                                            <tr><td><a href="{{route('fiche',['id'=>$client->id])}}">{{$client->Nom}}</a></td><td>{{$client->ville}}</td><td>{{$agenceLib}}</td><td style="color:{{$color}}">{{$type_c}}</td></tr>
                                        @endforeach
                                    <tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Carte (à intégrer avec une API de carte si nécessaire)  text-shadow:1px 1px black  -->
                            <div id="map" style="height: 400px; border: 1px solid #000; margin-top: 20px;">
                            </div>
                            <div class="row bg-grey" style="font-size:12px;background-color:#fff">
                                <div class="col-md-3">
                                    <span style="color:#ff2e36">Inactif / Fermé</span>
                                </div>
                                <div class="col-md-3">
                                    <span style="color:#2ab62c">Prospect</span>
                                </div>
                                <div class="col-md-6">
                                    <span style="color:#2261c4">Client récemment actif entre 0 et 2 mois</span>
                                </div>
                                <div class="col-md-6">
                                    <span style="color:#fffa40;text-shadow:1px 1px grey">Client inactif depuis 2 mois à 4 mois</span>
                                </div>
                                <div class="col-md-6">
                                    <span style="color:#fe9f2b;">Client inactif depuis 4 mois à 6 mois</span>
                                </div>
                                <div class="col-md-6">
                                    <span style="color:#a1058d;">Client inactif depuis 4 mois à 6 mois</span>
                                </div>
                                <div class="col-md-6">
                                    <span style="color:#000;">Client inactif depuis 12 mois ou plus</span>
                                </div>

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
            '2': 'blue',
            '1': 'green',
            '3': 'red',
            '4': 'red'
        };

        // Helper function to create an icon
        function createIcon(color) {
            return new L.Icon({
                iconUrl: 'https://crm.mysaamp.com/img/'+color+'.png',

                iconSize: [32, 32],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
        }

        clients.forEach(function (client) {
            if (client.latitude && client.longitude) {
                var clientType = client.etat_id;
                var color =  'gray' ;
                if (client.couleur_html!=null){
                    var color =  client.couleur_html.slice(1);
                }
                // iconOptions[clientType] || 'gray'; // Default to gray if type is unknown
                var marker = L.marker([client.latitude, client.longitude], { icon: createIcon(color) }).addTo(map);
                marker.bindPopup('<b>' + client.Nom + '</b><br>' + client.adresse1);
            } else {
                console.warn('Client without valid coordinates:', client);
            }
        });
    });
</script>
@endsection


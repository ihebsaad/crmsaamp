@extends('layouts.back')

@section('content')

<?php

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js" integrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/kvn9pG1NpYfqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" integrity="sha512-h9FcoyWjHcOcmEVkxOfTLnmZFWIH0iZhZT1H2TbOq55xssQGEJHEaIm+PgoUaZbRvQTNTluNOEfb1ZRy6D3BOw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.0.0/Control.FullScreen.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.0.0/Control.FullScreen.js"></script>
<script>
function activites_client(cl_ident) {
        $.ajax({
            url: "{{ route('activites_client') }}",
            method: "GET",
            data: {
                cl_ident: cl_ident
            },
            success: function(data) {

                $('#activites').html(data);
                $('#ModalActivites').modal('show');

            }
        });
    }
</script>
<div class="row">

    <!-- Content Column -->
    <div class="col-lg-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Search customers')}}</h6>
            </div>
            <div class="card-body">
                <a href="{{route('compte_client.create')}}" class="btn btn-primary  ml-3 float-right"><i class="fas fa-user-plus"></i> {{__('msg.Add')}} {{__('msg.a prospect')}}</a>
                <div class="clearfix"></div>
                <form action="{{route('search')}}">
                    <input type="hidden" name="sort" value="{{isset(request()->sort) ? request()->sort :'Nom'}}" />
                    <input type="hidden" name="direction" value="{{isset(request()->direction) ? request()->direction :'asc'}}" />
                    <div class="row  pb-2">
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
                        @if(auth()->user()->role!='commercial')
                        <div class="col-lg-3">
                            <div style="min-height:32px"><span class="">{{__('msg.Commercial')}}:</span></div>
                            <select class="form-control  select2" name="representant" style="max-width:300px;">
                                <option></option>
                                @foreach ($representants as $rp)
                                <option @selected($request->representant==$rp->id) value="{{$rp->id}}" >{{$rp->nom}}  {{$rp->prenom}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <!--
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
                        </div>-->


                    </div>
                    <div class="row pt-1">
                        <div class="col-lg-2 col-sm-6">
                            <div class="">
                                <label for="">{{__('msg.Agency')}}</label>
                                <select name="agence" class="form-control">
                                    <option></option>
                                    @foreach($agences as $id => $name)
                                    <option value="{{$id}}" {{$request->agence == $id ? 'selected="selected"'  : '' }}>{{$name}}</option>
                                    @endforeach
                                </select>
                                <!--<input type="" class="form-control" id="" placeholder="" name="adresse1" value="{{ $request->adresse1 ?? '' }}">-->
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

                        <div class="col-lg-2 col-sm-3">
                            <div class="">
                                <label for="">{{__('msg.City')}}</label>
                                <input type="" class="form-control" id="" placeholder="" name="ville" value="{{ $request->ville ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-3">
                            <div class="">
                                <label for="">{{__('msg.Department')}}</label>
                                <input type="text" class="form-control" id="" placeholder="" name="zip" value="{{ $request->zip ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="">Type</label>
                                <select class="form-control" id="" placeholder="" name="type_client">
                                    <option value=""> </option>
                                    <option {{ $request->type_client=="#2660c3" ? 'selected="selected"' : '' }}  value="#2660c3">Client récemment actif</option>
                                    <option {{ $request->type_client=="#2ab62c" ? 'selected="selected"' : '' }} value="#2ab62c">Prospect</option>
                                    <option {{ $request->type_client=="#ff2e36" ? 'selected="selected"' : '' }} value="#ff2e36">Fermé</option>
                                    <option {{ $request->type_client=="#DAA06D" ? 'selected="selected"' : '' }} value="#DAA06D">Particulier</option>
                                    <option {{ $request->type_client=="#fffc33" ? 'selected="selected"' : '' }} value="#fffc33">Inactif depuis 2 à 4 mois</option>
                                    <option {{ $request->type_client=="#ff9f33" ? 'selected="selected"' : '' }} value="#ff9f33">Inactif depuis 4 à 6 mois</option>
                                    <option {{ $request->type_client=="#a0078b" ? 'selected="selected"' : '' }} value="#a0078b">Inactif depuis 6 à 12 mois</option>
                                    <option {{ $request->type_client=="#050000" ? 'selected="selected"' : '' }} value="#050000">Inactif depuis 12 mois ou plus </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-12 col-sm-12 pt-1">
                            <button type="submit" class="btn btn-primary float-right mt-4"><i class="fa fa-search"></i> {{__('msg.Search')}}</button>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 pt-1">
                            <button type="submit" name="print" value="true" class="btn btn-secondary float-right mt-4"><i class="fa fa-print"></i> {{__('msg.Print')}}</button>
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
                                            </th><!--
                                        <th>
                                            <a href="{{ route('search', array_merge(request()->all(), ['sort' => 'agence', 'direction' => request()->direction == 'asc' ? 'desc' : 'asc'])) }}">
                                                {{__('msg.Agency')}} @if(request()->sort == 'agence')<i class="fa fa-sort-{{ request()->direction == 'asc' ? 'up' : 'down' }}"></i>@endif
                                            </a>
                                        </th>-->
                                        <th>
                                            <a href="{{ route('search', array_merge(request()->all(), ['sort' => 'etat_id', 'direction' => request()->direction == 'asc' ? 'desc' : 'asc'])) }}">
                                                {{__('msg.Type')}} @if(request()->sort == 'etat_id')<i class="fa fa-sort-{{ request()->direction == 'asc' ? 'up' : 'down' }}"></i>@endif
                                            </a>
                                        </th>
                                            <th style="width:80px">
                                                <a href="#">Activité</a>
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
                                        case 2 : $color='#2660c3'; $type_c='Client' ; break;
                                        case 1 : $color='#2ab62c'; $type_c='Prospect' ;break;
                                        case 3 : $color='#ff2e36'; $type_c='Fermé' ; break;
                                        case 4 : $color='#fffa40'; $type_c='Inactif' ; break;
                                        case 5 : $color='#DAA06D'; $type_c='Particulier' ; break;

                                        }
                                        //$color= $client->couleur_html ?? '#2660c3';
                                        //$nb_tasks= \App\Models\Tache::where('mycl_id', $client->id)->count()   + \DB::table('prise_contact_as400')->where('cl_ident', $client->cl_ident)->count();
                                        @endphp
                                        <tr>
                                            <td><a href="{{route('fiche',['id'=>$client->id])}}">{{$client->Nom}}</a></td>
                                            <td>{{$client->ville}}</td>
                                            <td style="color:{{$color}}">{{$type_c}}</td><!--<td>{{$agenceLib}}</td>-->
                                            <td><a href="#" onclick="activites_client({{$client->cl_ident}})">Voir plus</a></td>
                                        </tr>
                                        @endforeach
                                    <tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Carte (à intégrer avec une API de carte si nécessaire)  text-shadow:1px 1px black  -->
                            <div id="map" style="height: 400px; border: 1px solid #000; margin-top: 20px;">
                            </div>
                            <!-- Bouton manuel pour le plein écran -->
                            <button type="button" id="fullscreen-btn" style="position: absolute; top: 10px; right: 10px; z-index: 1000; padding: 10px; background: white; cursor: pointer;border:none;font-size:12px;">
                                Plein écran
                            </button>
                            <div class="row bg-grey" style="font-size:12px;background-color:#fff">
                                <div class="col-md-2">
                                    <span style="color:#ff2e36">Fermé</span>
                                </div>
                                <div class="col-md-2">
                                    <span style="color:#2ab62c">Prospect</span>
                                </div>
                                <div class="col-md-2">
                                    <span style="color:#DAA06D">Particulier</span>
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
                                    <span style="color:#a1058d;">Client inactif depuis 6 mois à 12 mois</span>
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


<div class="modal fade" id="ModalActivites" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 75%;margin: 0 auto;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center">Activités du client</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div id="activites">
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__('msg.Close')}}</button>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation de la carte
        var map = L.map('map').setView([46.603354, 1.888334], 6);

        // Ajout du fond de carte
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.control.fullscreen().addTo(map);

        // Bouton manuel pour le plein écran
        document.getElementById('fullscreen-btn').addEventListener('click', function() {
            if (!document.fullscreenElement) {
                document.getElementById('map').requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        });

        // Liste des clients (simulation de $clients en JavaScript)
        var clients = {!!json_encode($clients) !!};

        // Fonction pour créer une icône personnalisée
        function createIcon(color) {
            return new L.Icon({
                iconUrl: 'https://crm.mysaamp.com/img/' + color + '.png',
                iconSize: [18, 18],
                iconAnchor: [12, 18],
                popupAnchor: [1, -20]
            });
        }

        // Ajout des marqueurs pour les clients
        clients.forEach(function(client) {
            if (client.latitude && client.longitude) {
                var color = client.couleur_html ? client.couleur_html.slice(1) : 'gray';
                var marker = L.marker([client.latitude, client.longitude], {
                    icon: createIcon(color)
                }).addTo(map);
                marker.bindPopup('<b>' + client.Nom + '</b><br>' + client.adresse1);
            } else {
                console.warn('Client sans coordonnées valides:', client);
            }
        });
    });


    $('.select2').select2({
        filter: true,
        language: {
            noResults: function() {
                return 'Pas de résultats';
            }
        }
    });

    function activites_client(cl_ident) {
        $.ajax({
            url: "{{ route('activites_client') }}",
            method: "GET",
            data: {
                cl_ident: cl_ident
            },
            success: function(data) {

                $('#activites').html(data);
                $('#ModalActivites').modal('show');

            }
        });
    }
</script>
@endsection
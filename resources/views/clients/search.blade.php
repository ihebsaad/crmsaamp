@extends('layouts.back')

@section('content')


<style>
    form label{
        display:block!important;
    }
</style>
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
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Search customers')}}
                    <a href="{{route('compte_client.create')}}" class="btn btn-success  ml-3 float-right"><i class="fas fa-user-plus"></i> {{__('msg.Add')}} {{__('msg.a prospect')}}</a>
                </h6>
            </div>
            <div class="card-body">
                <form action="{{route('search')}}">

                    <input type="hidden" name="sort" value="{{isset(request()->sort) ? request()->sort :'Nom'}}" />
                    <input type="hidden" name="direction" value="{{isset(request()->direction) ? request()->direction :'asc'}}" />
                    <div class="row  pb-2">
                        <div class="row col-lg-6 col-sm-12 ">
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="">
                                    <label for="">{{__('msg.Part of the name')}}</label>
                                    <input type="" class="form-control" id="" placeholder="" name="Nom" value="{{ $request->Nom ?? '' }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="">
                                    <label for="">{{__('msg.Client ID')}}</label>
                                    <select name="client_id" class="form-control select2">
                                        <option></option>
                                        @foreach($clients_ids as $id)
                                            <option value="{{$id}}" {{ $request->client_id==$id ? 'selected="selected"' : '' }} >{{$id}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(auth()->user()->user_role < 4)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div style="min-height:32px"><span class="">{{__('msg.Commercial')}}:</span></div>
                                <select class="form-control  select2" name="representant" style="max-width:300px;">
                                    <option></option>
                                    @foreach ($representants as $rp)
                                    <option @selected($request->representant==$rp->id) value="{{$rp->id}}" >{{$rp->nom}}  {{$rp->prenom}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="">
                                    <label for="">Type</label>
                                    <select class="form-control" id="" placeholder="" name="type_client">
                                        <option value=""> </option>
                                        <option {{ $request->type_client=="clients" ? 'selected="selected"' : '' }}  value="clients">Tous les clients</option>
                                        <option {{ $request->type_client=="#2660c3" ? 'selected="selected"' : '' }}  value="#2660c3">Actif entre 0 et 2 mois</option>
                                        <option {{ $request->type_client=="#2ab62c" ? 'selected="selected"' : '' }} value="#2ab62c">Prospect</option>
                                        <option {{ $request->type_client=="#ff2e36" ? 'selected="selected"' : '' }} value="#ff2e36">Fermé</option>
                                        <option {{ $request->type_client=="#DAA06D" ? 'selected="selected"' : '' }} value="#DAA06D">Particulier</option>
                                        <option {{ $request->type_client=="#fffc33" ? 'selected="selected"' : '' }} value="#fffc33">Inactif depuis 2 à 4 mois</option>
                                        <option {{ $request->type_client=="#ff9f33" ? 'selected="selected"' : '' }} value="#ff9f33">Inactif depuis 4 à 6 mois</option>
                                        <option {{ $request->type_client=="#a0078b" ? 'selected="selected"' : '' }} value="#a0078b">Inactif depuis 6 à 12 mois</option>
                                        <option {{ $request->type_client=="#ff00a2" ? 'selected="selected"' : '' }} value="#ff00a2">Inactif depuis 12 à 24 mois</option>
                                        <option {{ $request->type_client=="#050000" ? 'selected="selected"' : '' }} value="#050000">Inactif depuis 24 mois ou plus </option>
                                    </select>
                                </div>
                            </div>

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


                            <div class="col-lg-3 col-md-4 col-sm-6">
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


                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="">
                                    <label for="">{{__('msg.Department')}}</label>
                                    <input type="text" class="form-control" id="" placeholder="" name="zip" value="{{ $request->zip ?? '' }}">
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="">
                                    <label for="">{{__('msg.Country')}}</label>
                                    <select  class="form-control select2" id=""  name="pays_code">
                                        <option value="" @if($request->pays_code=='') selected="selected" @endif ></option>

                                        <option value="AU"  @if($request->pays_code=='AU') selected="selected" @endif>AU : Australie</option>
                                        <option value="B"  @if($request->pays_code=='B') selected="selected" @endif>B : Belgique </option>
                                        <option value="BE"  @if($request->pays_code=='BE') selected="selected" @endif>BE : Belgique</option>
                                        <option value="BG"  @if($request->pays_code=='BG') selected="selected" @endif>BG : Bulgarie </option>
                                        <option value="BF"  @if($request->pays_code=='BF') selected="selected" @endif>BF : Burkina Faso</option>
                                        <option value="BR"  @if($request->pays_code=='BR') selected="selected" @endif>BR : Brésil</option>
                                        <option value="BW"  @if($request->pays_code=='BW') selected="selected" @endif>BW : Botswana</option>
                                        <option value="CA"  @if($request->pays_code=='CA') selected="selected" @endif>CA : Canada</option>
                                        <option value="CH"  @if($request->pays_code=='CH') selected="selected" @endif>CH : Suisse</option>
                                        <option value="CN"  @if($request->pays_code=='CN') selected="selected" @endif>CN : Chine</option>
                                        <option value="CO"  @if($request->pays_code=='CO') selected="selected" @endif>CO : Colombie</option>
                                        <option value="CR"  @if($request->pays_code=='CR') selected="selected" @endif>CR : Costa Rica</option>
                                        <option value="CY"  @if($request->pays_code=='CY') selected="selected" @endif>CY : Chypre</option>
                                        <option value="CZ"  @if($request->pays_code=='CZ') selected="selected" @endif>CZ : République tchèque</option>
                                        <option value="DE"  @if($request->pays_code=='DE') selected="selected" @endif>DE : Allemagne</option>
                                        <option value="DK"  @if($request->pays_code=='DK') selected="selected" @endif>DK : Danemark</option>
                                        <option value="EU"  @if($request->pays_code=='EU') selected="selected" @endif>EU : États-Unis</option>
                                        <option value="F" @if($request->pays_code=='F') selected="selected" @endif >F : France</option>
                                        <option value="FR" @if($request->pays_code=='FR') selected="selected" @endif >FR : France</option>
                                        <option value="FO"  @if($request->pays_code=='FO') selected="selected" @endif>FO : Îles Féroé</option>
                                        <option value="GB"  @if($request->pays_code=='GB') selected="selected" @endif>GB : Grande-Bretagne</option>
                                        <option value="GF"  @if($request->pays_code=='GF') selected="selected" @endif>GF : Guyane française</option>
                                        <option value="GN"  @if($request->pays_code=='GN') selected="selected" @endif>GN : Guinée</option>
                                        <option value="GH"  @if($request->pays_code=='GH') selected="selected" @endif>GH : Ghana</option>
                                        <option value="GP" @if($request->pays_code=='GP') selected="selected" @endif >GP : Guadeloupe</option>
                                        <option value="GL"  @if($request->pays_code=='GL') selected="selected" @endif>GL : Groenland</option>
                                        <option value="HK"  @if($request->pays_code=='HK') selected="selected" @endif>HK : Hong Kong</option>
                                        <option value="HR"  @if($request->pays_code=='HR') selected="selected" @endif>HR : Croatie</option>
                                        <option value="I"  @if($request->pays_code=='I') selected="selected" @endif>I : Italie</option>
                                        <option value="IT"  @if($request->pays_code=='IT') selected="selected" @endif>IT : Italie</option>
                                        <option value="IN"  @if($request->pays_code=='IN') selected="selected" @endif>IN : Inde</option>
                                        <option value="LB"  @if($request->pays_code=='LB') selected="selected" @endif>LB : Liban</option>
                                        <option value="LU"  @if($request->pays_code=='LU') selected="selected" @endif>LU : Luxembourg</option>
                                        <option value="MC"  @if($request->pays_code=='MC') selected="selected" @endif>MC : Monaco</option>
                                        <option value="ML"  @if($request->pays_code=='ML') selected="selected" @endif>ML : Mali</option>
                                        <option value="MQ"  @if($request->pays_code=='MQ') selected="selected" @endif>MQ : Martinique</option>
                                        <option value="MS"  @if($request->pays_code=='MS') selected="selected" @endif>MS : Ile Maruice</option>
                                        <option value="MU"  @if($request->pays_code=='MU') selected="selected" @endif>MS : Ile Maruice</option>
                                        <option value="NC"  @if($request->pays_code=='NC') selected="selected" @endif>NC : Nouvelle-Calédonie</option>
                                        <option value="N"  @if($request->pays_code=='N') selected="selected" @endif>N : Norvège</option>
                                        <option value="ES"  @if($request->pays_code=='ES') selected="selected" @endif>ES : Espagne</option>
                                        <option value="EG"  @if($request->pays_code=='EG') selected="selected" @endif>EG : Égypte</option>
                                        <option value="EE"  @if($request->pays_code=='EE') selected="selected" @endif>EE : Estonie</option>
                                        <option value="NL"  @if($request->pays_code=='NL') selected="selected" @endif>NL : Pays-Bas (Nederland)</option>
                                        <option value="PA"  @if($request->pays_code=='PA') selected="selected" @endif>PA : Panama</option>
                                        <option value="PF"  @if($request->pays_code=='PF') selected="selected" @endif>PF : Polynésie française</option>
                                        <option value="PE"  @if($request->pays_code=='PE') selected="selected" @endif>PE : Pérou</option>
                                        <option value="PL"  @if($request->pays_code=='PL') selected="selected" @endif>PL : Pologne</option>
                                        <option value="PT"  @if($request->pays_code=='PT') selected="selected" @endif>PT : Portugal</option>
                                        <option value="RE"  @if($request->pays_code=='RE') selected="selected" @endif>RE : La Réunion</option>
                                        <option value="RN"  @if($request->pays_code=='RN') selected="selected" @endif>RN : La Réunion</option>
                                        <option value="RO"  @if($request->pays_code=='RO') selected="selected" @endif>RO : Roumanie</option>
                                        <option value="SG"  @if($request->pays_code=='SG') selected="selected" @endif>SG : Singapour (Singapore)</option>
                                        <option value="SL"  @if($request->pays_code=='SL') selected="selected" @endif>SL : Sierra Leone</option>
                                        <option value="T"  @if($request->pays_code=='T') selected="selected" @endif>T: TAPEI</option>
                                        <option value="TG"  @if($request->pays_code=='TG') selected="selected" @endif>TG : Togo</option>
                                        <option value="TR"  @if($request->pays_code=='TR') selected="selected" @endif>TR : Turquie</option>
                                        <option value="US"  @if($request->pays_code=='US') selected="selected" @endif>US : États-Unis (United States)</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="">
                                    <label for="">{{__('msg.City')}}</label>
                                    <input type="" class="form-control" id="" placeholder="" name="ville" value="{{ $request->ville ?? '' }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-6">
                            </div>
                            <div class="col-lg-9 col-md-12 col-sm-6">
                                <button type="submit" class="btn btn-primary float-right mt-2 mr-2 ml-2"><i class="fa fa-search"></i> {{__('msg.Search')}}</button>
                                @if(auth()->user()->user_role==1 || auth()->user()->user_role ==2 || auth()->user()->user_role== 5)
                                    <button type="submit" name="excel" value="true" class="btn btn-success float-right mt-2 mr-2 ml-2" style="background-color:#1cc88a"><i class="fa fa-file-excel"></i> Excel </button>
                                @endif
                                <button type="submit" name="print" value="true" class="btn btn-secondary float-right mt-2 mr-2 ml-2"><i class="fa fa-print"></i> {{__('msg.Print')}}</button>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <!-- Carte (à intégrer avec une API de carte si nécessaire)  text-shadow:1px 1px black  -->
                                <div id="map" style="height: 480px; border: 1px solid #000; margin-top: 20px;">
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
                                        <span style="color:#ff00a2;">Client inactif depuis 12 mois à 24 mois</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span style="color:#000;">Client inactif depuis 24 mois ou plus</span>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-12">
                            <!-- Liste des résultats -->
                            <div style="max-height:690px; margin-top: 20px; overflow:  auto;width:100%  ">
                                <table class="table table-striped mb-40" id="table">
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
<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}"></script>

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

<style>
    input[type="search"] {
        width: 120px!important;
    }

    #table {
        width: 100% !important;
        margin-top: 10px !important;
    }
    #table_wrapper{
      display:block!important;
      width: 97% !important;

    }
</style>

@section('footer_scripts')

<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.rowReorder.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.scroller.js') }}"></script>
<!--
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.responsive.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/pdfmake.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/vfs_fonts.js') }}"></script>
-->

<script>
    $(document).ready(function() {
        var table = $('#table').DataTable({
            //dom: 'lfrtip',
            pageLength: 20, // Affiche 10 lignes par défaut
            lengthMenu: [[20, 50,75,100, -1], [20, 50,75,100, "Tout"]],
            "responsive": true,
            //"lengthChange": false,
            "autoWidth": false,
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json" // Fichier de traduction pour le français
            },
            "columnDefs": [{
                "targets": 'no-sort',
                "orderable": false,
            }],
        });
    });

</script>
@endsection
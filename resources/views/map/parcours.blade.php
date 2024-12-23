@extends('layouts.back')

@section('content')
<!--<link href="{{ asset('css/parcours.css') }}" rel="stylesheet">-->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<style>
    .summary{
        display:none;
    }
    table {
        width: 100%;
        margin-bottom: 1rem;
        color: #000;
    }

    table td {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    table th,
    table td {
        padding-right: 0.75rem;
        padding-left: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #e3e6f0;
    }

    table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #e3e6f0;
    }

    table tbody+tbody {
        border-top: 2px solid #e3e6f0;
    }

    table th,
    table td,
    table thead th,
    table tbody+tbody {
        border: 0;
    }

    table tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    table tbody tr:hover {
        color: #858796;
        background-color: rgba(0, 0, 0, 0.075);
    }

    b,
    h2 {
        color: black;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Parcours des commerciaux</h6>
            </div>
            <div class="card-body" style="min-height:500px">
                <form method="POST" action="{{ route('map.parcours') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-4">
                            <label for="commercial_id">Commercial :</label>
                             <select class="form-control mb-20" id="commercial_id" name="commercial_id" style="max-width:300px" required>
                                <option @if($commercial_id=="" ) selected="selected" @endif value=""></option>
                                @foreach ($users as $User)
                                @if(trim($User->lastname)!=='')
                                <option @selected($commercial_id>0 && $commercial_id==$User->id) value="{{$User->id}}" >{{$User->name}}  {{$User->lastname}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-4">
                            <label for="date">Date :</label>
                            <input type="date" id="date" name="date" value="{{ old('date', $date) }}" required class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-12 mb-4 mt-4">
                            <button type="submit" class="btn btn-primary mt-2">Voir l'itinéraire</button>
                        </div>
                    </div>
                </form>

                @if (isset($rdvs) && count($rdvs)>0)
                <h2>Rendez-vous pour le {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} :</h2>
                <ul style="list-style:none">
                    @foreach ($rdvs as $rdv)
                    <li class="mb-2">
                        <i class="text-dark fas fa-map-marker"></i>  {{ $rdv->ville }}, {{ $rdv->adresse }}<br>
                        <i class="text-dark fas fa-calendar"></i>  {{ $rdv->heure_debut }} - {{ $rdv->heure_fin }}
                    </li>
                    @endforeach
                </ul>
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-2">
                        <div id="map" style="height: 500px;"></div>
                    </div>
                    <div class="col-md-12 col-sm-12 mb-2">
                        <div id="custom-instructions" class="summary"></div>
                    </div>
                </div>

                <p>Distance totale : <b>{{ number_format($total_distance, 2) }} km</b></p>
                <p>Temps total estimé :<b> {{ number_format($total_duration, 2) }} minutes</b></p>

                <script>
                    const map = L.map('map').setView([48.8566, 2.3522], 10);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    const routeCoordinates = @json($coordinates);

                    if (routeCoordinates.length > 1) {
                        const routingControl = L.Routing.control({
                            waypoints: routeCoordinates.map(coord => L.latLng(coord[1], coord[0])),
                            routeWhileDragging: false,
                            showAlternatives: false,
                            router: L.Routing.osrmv1({
                                language: 'fr',
                                suppressWarnings: true,
                            }),
                            formatter: new L.Routing.Formatter({
                                language: 'fr',
                            })
                        }).addTo(map);

                        // Instructions personnalisées
                        routingControl.on('routesfound', function() {
                            const instructions = document.querySelector('.leaflet-routing-alternatives-container');
                            const customContainer = document.getElementById('custom-instructions');
                            if (instructions && customContainer) {
                                customContainer.appendChild(instructions);
                            }


                        });
                     }
                </script>
                @else
                <p>Aucun rendez-vous trouvé pour cette date et cet utilisateur.</p>
                @endif
            </div>
        </div>
    </div>
</div>


@endsection
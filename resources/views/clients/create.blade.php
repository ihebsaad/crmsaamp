@extends('layouts.back')

@section('content')

<?php

?>
<!--
<script src="https://cdn.jsdelivr.net/npm/algoliasearch@4.23.3/dist/algoliasearch-lite.umd.js" integrity="sha256-1QNshz86RqXe/qsCBldsUu13eAX6n/O98uubKQs87UI=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4.68.1/dist/instantsearch.production.min.js" integrity="sha256-AEialBwCKmHcqym8j00nCLu/FDy3530TKG9n6okJobM=" crossorigin="anonymous"></script>
-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <style>
        #map {
            height: 350px;
            width: 100%;
        }
        #suggestions{
            height: 260px;
            overflow: hidden;
        }
    </style>
    <div class="row">

    <!-- Content Column -->
    <div class="col-lg-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ajouter un prospect</h6>
            </div>
            <div class="card-body">

                <form action="{{ route('compte_client.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="etat_id" value="1" />
                    <div class="row pt-1">
                        <div class="col-md-7">
                            <div id="map"></div>
                        </div>
                        <div class="col-md-5">
                            <div class="">
                                <label for="Rue">Adresse:</label>
                                <input type="text" id="adresse1" class="form-control" name="adresse1" required value="{{old('adresse1')}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-2">
                            <div class="">
                                <label for="Pays">Pays:</label>
                                <input type="text" id="Pays" class="form-control" name="Pays" value="France" required><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="pays_code">Code Pays:</label>
                                <input type="text" id="pays_code" class="form-control" name="pays_code" value="FR" maxlength="2" required><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="zip">Code Postal:</label>
                                <input type="text" id="zip" class="form-control" name="zip" required><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="ville">Ville:</label>
                                <input type="text" id="ville" class="form-control" name="ville" required><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="latitude">Latitude:</label>
                                <input type="text" id="latitude" class="form-control" name="latitude" required><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="longitude">Longitude:</label>
                                <input type="text" id="longitude" class="form-control" name="longitude" required><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
<!--
                        <div class="col-md-2">

                            <div class="">
                                <label for="siret">SIRET:</label>
                                <input type="number" id="siret" class="form-control" name="siret" value="{{old('siret')}}" maxlength="14" required><br><br>
                            </div>
                        </div>-->
                        <div class="col-md-2">

                            <div class="">
                                <label for="siret">Code SIRET:</label>
                                <input type="text" id="Code_siret" class="form-control" name="Code_siret" value="{{old('Code_siret')}}"    ><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div class="">
                                <label for="Nom">Raison sociale:</label>
                                <input type="text" id="Nom" class="form-control" name="Nom"  value="{{old('Nom')}}" required><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Phone">Télephone:</label>
                                <input type="text" id="Phone" class="form-control" name="Tel" value="{{old('Tel')}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Email:</label>
                                <input type="email" id="email" class="form-control" name="email" value="{{old('email')}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Site Web:</label>
                                <input type="url" id="url" class="form-control" name="url" value="{{old('url')}}"><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div class="">
                                <label for="Nom">Nom de contact:</label>
                                <input type="text" id="nom_contact" class="form-control" name="nom_contact"  value="{{old('nom_contact')}}"  ><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Phone">Prénom de contact:</label>
                                <input type="text" id="prenom_contact" class="form-control" name="prenom_contact" value="{{old('prenom_contact')}}" ><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Email de contact:</label>
                                <input type="email" id="email_contact" class="form-control" name="email_contact" value="{{old('email_contact')}}" ><br><br>
                            </div>
                        </div>

                    </div>

<!--
                    <div class="row pt-1">

                        <div class="col-md-2">
                            <div class="">
                                <label for="agence_ident">Agence:</label>
                                <select  type="text" id="agence_ident" class="form-control" name="agence_ident"  >
                                    <option  value=""></option>
                                    @foreach($agences as $agence)
                                        <option  value="{{$agence->agence_ident}}">{{$agence->agence_lib}}</option>
                                    @endforeach
                                </select><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Commercial">Commercial:</label>
                                <input type="text" id="Commercial" class="form-control" name="Commercial" value="" required><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Commercial_support">Commercial support:</label>
                                <input type="text" id="Commercial_support" class="form-control" name="Commercial_support" value=""><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="ADV">ADV:</label>
                                <input type="text" id="ADV" class="form-control" name="ADV" value=""><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Fidelite_du_client_c">Clientèle:</label>
                                <input  id="Fidelite_du_client_c" class="form-control" name="Fidelite_du_client_c"  value="" required>
                                   <br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
    -->

                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right">Ajouter</button>
                        </div>
                    </div>

                </form>


            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js" integrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/kvn9pG1NpYfqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([46.603354, 1.888334], 6); // Centrer sur la France

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([46.603354, 1.888334], {draggable: true}).addTo(map);

            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat;
                document.getElementById('longitude').value = position.lng;

                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.lat}&lon=${position.lng}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('adresse1').value = data.display_name || '';
                        document.getElementById('zip').value = data.address.postcode || '';
                        document.getElementById('ville').value = data.address.city || data.address.town || data.address.village || '';
                        document.getElementById('pays_code').value = data.address.country_code ? data.address.country_code.toUpperCase() : '';
                        document.getElementById('Pays').value = data.address.country ? data.address.country : '';
                    })
                    .catch(error => console.error('Erreur:', error));
            });

            var adresse1Input = document.getElementById('adresse1');
            adresse1Input.addEventListener('input', function() {
                var query = adresse1Input.value;
                if (query.length > 2) {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&q=${encodeURIComponent(query)}&limit=10`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                var suggestions = data.map(result => ({
                                    display_name: result.display_name,
                                    lat: result.lat,
                                    lon: result.lon,
                                    address: result.address
                                }));

                                // Suppression des anciennes suggestions
                                let suggestionsContainer = document.getElementById('suggestions');
                                if (!suggestionsContainer) {
                                    suggestionsContainer = document.createElement('div');
                                    suggestionsContainer.id = 'suggestions';
                                    adresse1Input.parentNode.appendChild(suggestionsContainer);
                                }
                                suggestionsContainer.innerHTML = '';

                                suggestions.forEach(suggestion => {
                                    let suggestionItem = document.createElement('div');
                                    suggestionItem.innerText = suggestion.display_name;
                                    suggestionItem.classList.add('suggestion-item');
                                    suggestionItem.style.cursor = 'pointer';
                                    suggestionItem.addEventListener('click', () => {
                                        adresse1Input.value = suggestion.display_name;
                                        document.getElementById('latitude').value = suggestion.lat;
                                        document.getElementById('longitude').value = suggestion.lon;
                                        document.getElementById('zip').value = suggestion.address.postcode || '';
                                        document.getElementById('ville').value = suggestion.address.city || suggestion.address.town || suggestion.address.village || '';
                                        document.getElementById('pays_code').value = suggestion.address.country_code ? suggestion.address.country_code.toUpperCase() : '';
                                        document.getElementById('Pays').value = suggestion.address.country ? suggestion.address.country : '';

                                        marker.setLatLng([suggestion.lat, suggestion.lon]);
                                        map.setView([suggestion.lat, suggestion.lon], 13);
                                    });
                                    suggestionsContainer.appendChild(suggestionItem);
                                });
                            } else {
                                // Cas où aucune suggestion n'est trouvée
                                let suggestionsContainer = document.getElementById('suggestions');
                                if (suggestionsContainer) {
                                    suggestionsContainer.innerHTML = '<div class="suggestion-item">Aucune adresse trouvée</div>';
                                }
                            }
                        })
                        .catch(error => console.error('Erreur:', error));
                }
            });
        });
    </script>
@endsection
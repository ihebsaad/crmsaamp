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
                <h6 class="m-0 font-weight-bold text-primary">Ajouter un client</h6>
            </div>
            <div class="card-body">

                <form id="">
                    <div class="row pt-1">
                        <div class="col-md-7">
                            <div id="map"></div>
                        </div>
                        <div class="col-md-5">
                            <div class="">
                                <label for="adresse1">Adresse:</label>
                                <input type="text" id="adresse1" class="form-control" name="adresse1"><br><br>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div class="">
                                <label for="siret">SIRET:</label>
                                <input type="text" id="siret" class="form-control" name="siret"><br><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="raison_sociale">Raison Sociale:</label>
                                <input type="text" id="raison_sociale" class="form-control" name="raison_sociale"><br><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="adresse2">Adresse2:</label>
                                <input type="text" id="adresse2" class="form-control" name="adresse2"><br><br>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-4">
                            <div class="">
                                <label for="pays_code">Code Pays:</label>
                                <input type="text" id="pays_code" class="form-control" name="pays_code" maxlength="2"><br><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="zip">Code Postal:</label>
                                <input type="text" id="zip" class="form-control" name="zip"><br><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="ville">Ville:</label>
                                <input type="text" id="ville" class="form-control" name="ville"><br><br>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">

                        <div class="col-md-4">
                            <div class="">
                                <label for="latitude">Latitude:</label>
                                <input type="text" id="latitude" class="form-control" name="latitude"><br><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="longitude">Longitude:</label>
                                <input type="text" id="longitude" class="form-control" name="longitude"><br><br>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-4">
                            <button type="submit" class="btn-primary btn">Ajouter</button>
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
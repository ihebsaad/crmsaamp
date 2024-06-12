@extends('layouts.back')

@section('content')

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
                <h6 class="m-0 font-weight-bold text-primary">Modifier le client</h6>
            </div>
            <div class="card-body">

                <form action="{{ route('compte_client.update', $client->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="row pt-1">
                        <div class="col-md-7">
                            <div id="map"></div>
                        </div>
                        <div class="col-md-5">
                            <div class="">
                                <label for="Rue">Adresse:</label>
                                <input type="text" id="Rue" class="form-control" name="Rue" required value="{{$client->Rue}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-2">
                            <div class="">
                                <label for="Pays">Pays:</label>
                                <input type="text" id="Pays" class="form-control" name="Pays"  required value="{{$client->Pays}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="pays_code">Code Pays:</label>
                                <input type="text" id="pays_code" class="form-control" name="CountryCode" maxlength="2" required value="{{$client->CountryCode}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="zip">Code Postal:</label>
                                <input type="text" id="zip" class="form-control" name="postalCode" required value="{{$client->postalCode}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="ville">Ville:</label>
                                <input type="text" id="ville" class="form-control" name="BillingAddress_city" required value="{{$client->BillingAddress_city}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="latitude">Latitude:</label>
                                <input type="text" id="latitude" class="form-control" name="latitude" required value="{{$client->latitude}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="longitude">Longitude:</label>
                                <input type="text" id="longitude" class="form-control" name="longitude" required value="{{$client->longitude}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Nom">Nom:</label>
                                <input type="text" id="Nom" class="form-control" name="Nom"   required value="{{$client->Nom}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="id">ID Client:</label>
                                <input type="text" id="cl_ident" class="form-control" name="" value="{{$client->cl_ident}}" readonly ><br><br>
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
                                <input type="text" id="Proprietaire" class="form-control" name="Proprietaire" value="{{$client->Proprietaire}}" required><br><br>
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
                                <label for="Fidelite_du_client_c">Clientèle:</label>
                                <input  id="Fidelite_du_client_c" class="form-control" name="Fidelite_du_client_c"  value="{{$client->Fidelite_du_client_c}}" required>
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
                        <div class="col-md-3">
                            <div class="">
                                <label for="Phone">Télephone:</label>
                                <input type="text" id="Phone" class="form-control" name="Phone" value="{{$client->Phone}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Email:</label>
                                <input type="email" id="email" class="form-control" name="email" value="{{$client->email}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Site Web:</label>
                                <input type="url" id="site" class="form-control" name="url" value="{{$client->url}}"><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right">Modifier</button>
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
            var map = L.map('map').setView([<?php echo $client->latitude; ?>, <?php echo $client->longitude; ?>], 6); // Centrer sur la France

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([<?php echo $client->latitude; ?> ,<?php echo $client->longitude; ?>], {draggable: true}).addTo(map);

            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat;
                document.getElementById('longitude').value = position.lng;

                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.lat}&lon=${position.lng}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('Rue').value = data.display_name || '';
                        document.getElementById('zip').value = data.address.postcode || '';
                        document.getElementById('ville').value = data.address.city || data.address.town || data.address.village || '';
                        document.getElementById('pays_code').value = data.address.country_code ? data.address.country_code.toUpperCase() : '';
                        document.getElementById('Pays').value = data.address.country ? data.address.country : '';
                    })
                    .catch(error => console.error('Erreur:', error));
            });

            var adresse1Input = document.getElementById('Rue');
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
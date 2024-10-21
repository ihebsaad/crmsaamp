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
                <h6 class="m-0 font-weight-bold text-primary">Modifier le @if($client->etat_id==2) client @else prospect @endif</h6>
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
                                <input type="text" id="adresse1" class="form-control" name="adresse1" required value="{{$client->adresse1}}"><br><br>
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
                                <input type="text" id="pays_code" class="form-control" name="pays_code" maxlength="2" required value="{{$client->pays_code}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="zip">Code Postal:</label>
                                <input type="text" id="zip" class="form-control" name="zip" required value="{{$client->zip}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="ville">Ville:</label>
                                <input type="text" id="ville" class="form-control" name="ville" required value="{{$client->ville}}"><br><br>
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
                        <div class="col-md-4">
                            <div class="">
                                <label for="Nom">Nom:</label>
                                <input type="text" id="Nom" class="form-control" name="Nom"   required value="{{$client->Nom}}"><br><br>
                            </div>
                        </div>
                        @if($client->etat_id==2)
                            <div class="col-md-2">
                                <div class="">
                                    <label for="id">ID Client:</label>
                                    <input type="text" id="cl_ident" class="form-control" name="cl_ident" value="{{$client->cl_ident}}" readonly  ><br><br>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3 ">
                            <div class="">
                                <label for="id">Activité:</label>
                                <select  type="text" id="activite" class="form-control" name="activite"  >
                                    <option @if($client->activite=="") selected="selected" @endif value=""></option>
                                    <option @if($client->activite=="Affineurs et Traders") selected="selected" @endif value="Affineurs et Traders">Affineurs et Traders</option>
                                    <option @if($client->activite=="Certifications") selected="selected" @endif value="Certifications">Certifications</option>
                                    <option @if($client->activite=="Divers") selected="selected" @endif value="Divers">Divers</option>
                                    <option @if($client->activite=="Fabricant") selected="selected" @endif value="Fabricant">Fabricant</option>
                                    <option @if($client->activite=="Frais Generaux") selected="selected" @endif value="Frais Generaux">Frais Generaux</option>
                                    <option @if($client->activite=="Immobilier") selected="selected" @endif value="Immobilier">Immobilier</option>
                                    <option @if($client->activite=="Industrie et Mine") selected="selected" @endif value="Industrie et Mine">Industrie et Mine</option>
                                    <option @if($client->activite=="Labo") selected="selected" @endif value="Labo">Labo</option>
                                    <option @if($client->activite=="Marketing") selected="selected" @endif value="Marketing">Marketing</option>
                                    <option @if($client->activite=="Particulier") selected="selected" @endif value="Particulier">Particulier</option>
                                    <option @if($client->activite=="Production") selected="selected" @endif value="Production">Production</option>
                                    <option @if($client->activite=="Récupération") selected="selected" @endif value="Récupération">Récupération</option>
                                    <option @if($client->activite=="Transport") selected="selected" @endif value="Transport">Transport</option>
                                    <option @if($client->activite=="Vendeur bijoux") selected="selected" @endif value="Vendeur bijoux" >Vendeur bijoux</option>
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="sous_activite">Sous activité:</label>
                                <input type="text" id="sous_activite" class="form-control" name="sous_activite"     value="{{$client->sous_activite}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">

                        <div class="col-md-2">
                            <div class="">
                                <label for="agence">Agence:</label>
                                <!--
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
    -->
                                <select  type="text" id="agence_ident" class="form-control" name="agence_ident"  >
                                    <option  value=""></option>
                                    @foreach($agences as $agence)
                                        <option @if($client->agence_ident=="$agence->agence_ident") selected="selected" @endif value="{{$agence->agence_ident}}">{{$agence->agence_lib}}</option>
                                    @endforeach
                                </select><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="commercial">Commercial:</label>
                                <select   id="commercial" class="form-control" name="commercial" value="{{$client->Commercial}}" >
                                    <option></option>
                                    @foreach($representants as $rep )
                                    <option  @if($client->commercial==$rep->id) selected="selected" @endif value="{{$rep->id}}" >{{$rep->prenom}} {{$rep->nom}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="commercial_support">Commercial support:</label>
                                <select   id="commercial_support" class="form-control" name="commercial_support" value="{{$client->commercial_support}}" >
                                    <option></option>
                                    @foreach($representants as $rep )
                                    <option  @if($client->commercial_support==$rep->id) selected="selected" @endif value="{{$rep->id}}" >{{$rep->prenom}} {{$rep->nom}}</option>
                                    @endforeach
                                </select>
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
                                <label for="etat_id">Type:</label>
                                    @foreach($etats as $etat )
                                        @if($client->etat_id==$etat->id)<h6 style="color:black"> {{$etat->etat}}</h6> @endif
                                    @endforeach
                                <br><br>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Phone">Télephone:</label>
                                <input type="text" id="Tel" class="form-control" name="Phone" value="{{$client->Tel}}"><br><br>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Email:</label>
                                <input type="email" id="email" class="form-control" name="email" value="{{$client->email}}"><br><br>
                            </div>
                        </div>-->
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Site Web:</label>
                                <input type="url" id="url" class="form-control" name="url" value="{{$client->url}}"><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right">Modifier</button>
                        </div>

                        @if(  $client->etat_id==1  )
                            <a title="Supprimer"   onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('clients.destroy', $client->id )}}" class="btn btn-danger btn-sm btn-responsive ml-3 mr-2 float-right" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                            </a>
                        @endif
                    </div>

                </form>


            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js" integrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/kvn9pG1NpYfqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var defaultLat = 46.603354; // Latitude de la France
        var defaultLng = 1.888334;  // Longitude de la France
        var lat = <?php echo $client->latitude ?? 'null'; ?>;
        var lng = <?php echo $client->longitude ?? 'null'; ?>;
        var mapCenterLat = lat !== null ? lat : defaultLat;
        var mapCenterLng = lng !== null ? lng : defaultLng;

        var map = L.map('map').setView([mapCenterLat, mapCenterLng], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marker = L.marker([mapCenterLat, mapCenterLng], {draggable: true}).addTo(map);

        // Si la latitude et la longitude sont nulles, rechercher par l'adresse
        if (lat === null || lng === null) {
            var adresse = '<?php echo $client->adresse1; ?>';
            if (adresse) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&q=${encodeURIComponent(adresse)}&limit=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            var result = data[0];
                            map.setView([result.lat, result.lon], 13);
                            marker.setLatLng([result.lat, result.lon]);
                            document.getElementById('latitude').value = result.lat;
                            document.getElementById('longitude').value = result.lon;
                            document.getElementById('zip').value = result.address.postcode || '';
                            document.getElementById('ville').value = result.address.city || result.address.town || result.address.village || '';
                            document.getElementById('pays_code').value = result.address.country_code ? result.address.country_code.toUpperCase() : '';
                            document.getElementById('Pays').value = result.address.country ? result.address.country : '';
                        }
                    })
                    .catch(error => console.error('Erreur lors de la recherche de l\'adresse:', error));
            }
        }

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
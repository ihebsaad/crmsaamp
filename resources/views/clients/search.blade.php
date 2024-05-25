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
                <form>
                    <div class="row pt-1">
                        <div class="col-md-6">
                            <div class="">
                                <label for="">Partie du nom</label>
                                <input type="" class="form-control" id="" placeholder="">
                            </div>
                        </div>

                        <div class="col-md-6 pt-1">
                            <div class="form-check form-check-inline mb-3 mt-4">
                                <input class="form-check-input mt-2" type="radio" id="clientsUniquement" value="option1" name="show">
                                <label class="form-check-label mt-2" for="clientsUniquement">Clients uniquement</label>
                            </div>

                            <div class="form-check form-check-inline mb-3 mt-4">
                                <input class="form-check-input mt-2" type="radio" id="prospectUniquement" value="option2" name="show">
                                <label class="form-check-label mt-2" for="prospectUniquement">Prospect uniquement</label>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-6">
                            <div class="">
                                <label for="">Adresse</label>
                                <input type="" class="form-control" id="" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                <label for="">Liste triée par</label>
                                <select  class="form-control" id="" >
                                    <option value="1">Nom</option>
                                    <option value="2">Distance</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-6">
                            <div class="">
                                <label for="">Ville</label>
                                <input type="" class="form-control" id="" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Département</label>
                                <input type="" class="form-control" id="" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="">Région</label>
                                <input type="" class="form-control" id="" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-right">Recherche</button>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-6">
                            <!-- Liste des résultats -->
                            <div style="height: 400px; border: 1px solid #000; margin-top: 20px; padding: 10px;">Liste des résultats
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

        clients.forEach(function (client) {
            var marker = L.marker([client.latitude, client.longitude]).addTo(map);
            marker.bindPopup('<b>' + client.raison_sociale + '</b><br>' + client.adresse1);
        });
    });
</script>
@endsection


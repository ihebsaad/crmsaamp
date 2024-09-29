@extends('layouts.back')
<style>
    table td:not(.text) {
        text-align: right;
    }

    .mn5 {
        min-height: 500px !important;
    }

    .table-container {
        position: relative;
        height: 400px;
        /* Ajustez cette hauteur selon vos besoins */
        overflow-y: auto;
    }

    .table-container thead th {
        position: sticky;
        top: 0;
        background-color: #e6d685;
        /* Couleur de fond pour l'en-tête */
        z-index: 10;
        /* S'assurer que l'en-tête reste au-dessus des autres éléments */
    }

    #stats tr:first-child,
    #stats2 tr:first-child,
    #stats3 tr:first-child,
    #stats4 tr:first-child,
    #stats5 tr:first-child,
    #stats6 tr:first-child {
        background-color: cornsilk;
    }

    table td:first-child {
        font-weight: bold;
    }

    /* Mobiles
@media (max-width: 767px) {
    .table td{
        font-size:9px!important;
    }
}
*/
</style>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

@section('content')

<div class="row">

    <input type="hidden" id="user_id" value="{{ auth()->user()->id }}" />

    @if($commercial)
    <input type="hidden" id="commercial" value="{{ \DB::table('representant')->where('users_id',auth()->user()->id)->first()->id ?? 0 }}" />
    @else
    @if( auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv' )
    <div class="col-lg-4">
        <span class=" mr-2">Commercial:</span>
        <select class="form-control mb-20" id="commercial" onchange="update_stats();" style="max-width:300px">
            @foreach ($representants as $rp)
            <option @selected(auth()->user()->id==$rp->id) value="{{$rp->users_id}}" data-id="{{$rp->id}}">{{$rp->nom}}  {{$rp->prenom}}</option>
            @endforeach
        </select>
    </div>

    @else
    <input type="hidden" id="commercial" value="{{ \DB::table('representant')->where('users_id',auth()->user()->id)->first()->id ?? 0 }}">
    @endif
    @endif

    <div class="col-lg-4 mt-4">
        <input id="mois" type="checkbox" value="1" onchange="update_stats();">
        <label class="mt-2" for="mois">Afficher les années pleines</label>
        </input>
    </div>
</div>

<div class="row">

    <div class="col-lg-6 col-sm-12  mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Par Métier</h6>
            </div>
            <div class="card-body">
                <div id="chart_metier"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Par Client</h6>
            </div>
            <div class="card-body">
                <div id="chart_client"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <span class=" mr-2">Agence:</span><select class="form-control mb-20" id="agence" onchange="update_stats();" style="max-width:300px">
            <option></option>
            @foreach ($agences as $agence)
            <option @selected(auth()->user()->agence_ident==$agence->agence_ident) value="{{$agence->agence_ident}}">{{$agence->agence_lib}}    |  <small>{{$agence->adresse1}}</small></option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">

    <div class="col-lg-6 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Par Métier</h6>
            </div>
            <div class="card-body">
                <div id="chart_agence"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Par Client</h6>
            </div>
            <div class="card-body">
                <div id="chart_agence_client"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Toutes les agences</h6>
            </div>
            <div class="card-body">
                <div id="chart_agences"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Clients inactifs</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <span class=" mr-2">Inactif depuis :</span><input type="number" class="form-control mb-20" id="nb_mois" onchange="update_stats();" style="max-width:100px" value="2" />
                    </div>
                </div>
                <div id="chart_clients_inactifs"></div>
            </div>
        </div>
    </div>
</div>


<script>
    // Load the Google Charts library
    google.charts.load('current', {
        packages: ['corechart', 'bar','pie']
    });
    google.charts.setOnLoadCallback(update_stats);

    function update_stats() {
    var _token = $('input[name="_token"]').val();
    var agence = $('#agence').val();
    var user = $('#user_id').val();
    var representant = $('#commercial').find(':selected').data('id');
    var nb_mois = $('#nb_mois').val();
    if (typeof representant === 'undefined') {
        representant = $('#commercial').val();
    }
    var mois = $('#mois').is(':checked') ? 0 : 1;

    // Par Métier
    $.ajax({
        url: "{{ route('stats_commercial') }}",
        method: "get",
        data: {
            _token: _token,
            agence: agence,
            mois: mois,
            user: representant,
        },
        success: function(data) {
            var chartData = [
                ['Métier', '2024', '2023', '2022', '2021']
            ];
            data.forEach(function(item) {
                let n = Number(item.N.replace(/\s/g, ''));
                let n1 = Number(item.N_1.replace(/\s/g, ''));
                let n2 = Number(item.N_2.replace(/\s/g, ''));
                let n3 = Number(item.N_3.replace(/\s/g, ''));

                // Vérifier si au moins une des valeurs est supérieure à 0
                if (n > 0 || n1 > 0 || n2 > 0 || n3 > 0) {
                    chartData.push([
                        item.metier,
                        Number(item.N.replace(/\s/g, '')),
                        Number(item.N_1.replace(/\s/g, '')),
                        Number(item.N_2.replace(/\s/g, '')),
                        Number(item.N_3.replace(/\s/g, ''))
                    ]);
                }
            });

            var metierData = google.visualization.arrayToDataTable(chartData);
            var metierOptions = {
                title: 'Statistiques Par Métier',
                hAxis: { title: 'Métier' },
                vAxis: { title: 'Chiffre d\'affaires' },
                bars: 'vertical',
                height: 400,
                colors: ['#e5e7e6', '#EEE6D8', '#DAAB3A', '#B67332','#93441A'], // Couleurs personnalisées

            };
            var chartMetier = new google.visualization.ColumnChart(document.getElementById('chart_metier'));
            chartMetier.draw(metierData, metierOptions);
        }
    });

    // Par Client
    $.ajax({
        url: "{{ route('stats_commercial_client') }}",
        method: "get",
        data: {
            _token: _token,
            agence: agence,
            mois: mois,
            user: representant,
        },
        success: function(data) {
            var chartData = [
                ['Client', '2024', '2023', '2022', '2021']
            ];
            data.forEach(function(item) {
                let n = Number(item.N.replace(/\s/g, ''));
                let n1 = Number(item.N_1.replace(/\s/g, ''));
                let n2 = Number(item.N_2.replace(/\s/g, ''));
                let n3 = Number(item.N_3.replace(/\s/g, ''));

                // Vérifier si au moins une des valeurs est supérieure à 0
                if (n > 0 || n1 > 0 || n2 > 0 || n3 > 0) {
                chartData.push([
                    item.nom,
                    Number(item.N.replace(/\s/g, '')),
                    Number(item.N_1.replace(/\s/g, '')),
                    Number(item.N_2.replace(/\s/g, '')),
                    Number(item.N_3.replace(/\s/g, ''))
                ]);
                }
            });

            var clientData = google.visualization.arrayToDataTable(chartData);
            var clientOptions = {
                title: 'Statistiques Par Client',
                hAxis: { title: 'Client' },
                vAxis: { title: 'Chiffre d\'affaires' },
                bars: 'vertical',
                height: 400,
                colors: ['#e5e7e6', '#EEE6D8', '#DAAB3A', '#B67332','#93441A'], // Couleurs personnalisées
            };
            var chartClient = new google.visualization.ColumnChart(document.getElementById('chart_client'));
            chartClient.draw(clientData, clientOptions);
        }
    });

    // Par Agence
    $.ajax({
        url: "{{ route('stats_agence') }}",
        method: "get",
        data: {
            _token: _token,
            agence: agence,
            mois: mois,
            user: user,
        },
        success: function(data) {
            var chartData = [
                ['Agence', '2024', '2023', '2022', '2021']
            ];
            data.forEach(function(item) {
                let n = Number(item.N.replace(/\s/g, ''));
                let n1 = Number(item.N_1.replace(/\s/g, ''));
                let n2 = Number(item.N_2.replace(/\s/g, ''));
                let n3 = Number(item.N_3.replace(/\s/g, ''));

                // Vérifier si au moins une des valeurs est supérieure à 0
                if (n > 0 || n1 > 0 || n2 > 0 || n3 > 0) {
                chartData.push([
                    item.agence,
                    Number(item.N.replace(/\s/g, '')),
                    Number(item.N_1.replace(/\s/g, '')),
                    Number(item.N_2.replace(/\s/g, '')),
                    Number(item.N_3.replace(/\s/g, ''))
                ]);
                }
            });

            var agenceData = google.visualization.arrayToDataTable(chartData);
            var agenceOptions = {
                title: 'Statistiques Par Agence',
                hAxis: { title: 'Agence' },
                vAxis: { title: 'Chiffre d\'affaires' },
                bars: 'vertical',
                height: 400,
                colors: ['#e5e7e6', '#EEE6D8', '#DAAB3A', '#B67332','#93441A'], // Couleurs personnalisées
            };
            var chartAgence = new google.visualization.ColumnChart(document.getElementById('chart_agence'));
            chartAgence.draw(agenceData, agenceOptions);
        }
    });

    // Clients Inactifs
    $.ajax({
        url: "{{ route('stats_clients_inactifs') }}",
        method: "get",
        data: {
            _token: _token,
            mois: nb_mois,
            user: representant,
        },
        success: function(data) {
            var chartData = [
                ['Client Inactif', '2024', '2023', '2022', '2021']
            ];
            data.forEach(function(item) {
                let n = Number(item.N.replace(/\s/g, ''));
                let n1 = Number(item.N_1.replace(/\s/g, ''));
                let n2 = Number(item.N_2.replace(/\s/g, ''));
                let n3 = Number(item.N_3.replace(/\s/g, ''));

                // Vérifier si au moins une des valeurs est supérieure à 0
                if (n > 0 || n1 > 0 || n2 > 0 || n3 > 0) {
                chartData.push([
                    item.nom,
                    Number(item.N.replace(/\s/g, '')),
                    Number(item.N_1.replace(/\s/g, '')),
                    Number(item.N_2.replace(/\s/g, '')),
                    Number(item.N_3.replace(/\s/g, ''))
                ]);
                }
            });

            var clientInactifData = google.visualization.arrayToDataTable(chartData);
            var clientInactifOptions = {
                title: 'Clients Inactifs',
                hAxis: { title: 'Client Inactif' },
                vAxis: { title: 'Chiffre d\'affaires' },
                bars: 'vertical',
                height: 400,
                colors: ['#e5e7e6', '#EEE6D8', '#DAAB3A', '#B67332','#93441A'], // Couleurs personnalisées
            };
            var chartClientsInactifs = new google.visualization.ColumnChart(document.getElementById('chart_clients_inactifs'));
            chartClientsInactifs.draw(clientInactifData, clientInactifOptions);
        }
    });

    // Toutes les agences
    $.ajax({
        url: "{{ route('stats_agences') }}",
        method: "get",
        data: {
            _token: _token,
            agence: agence,
            mois: mois,
            user: user,
        },
        success: function(data) {
            var chartData = [
                ['Agence', '2024', '2023', '2022', '2021']
            ];
            data.forEach(function(item) {
                let n = Number(item.N.replace(/\s/g, ''));
                let n1 = Number(item.N_1.replace(/\s/g, ''));
                let n2 = Number(item.N_2.replace(/\s/g, ''));
                let n3 = Number(item.N_3.replace(/\s/g, ''));

                // Vérifier si au moins une des valeurs est supérieure à 0
                if (n > 0 || n1 > 0 || n2 > 0 || n3 > 0) {
                chartData.push([
                    item.agence,
                    Number(item.N.replace(/\s/g, '')),
                    Number(item.N_1.replace(/\s/g, '')),
                    Number(item.N_2.replace(/\s/g, '')),
                    Number(item.N_3.replace(/\s/g, ''))
                ]);
                }
            });

            var toutesAgencesData = google.visualization.arrayToDataTable(chartData);
            var toutesAgencesOptions = {
                title: 'Toutes les Agences',
                hAxis: { title: 'Agence' },
                vAxis: { title: 'Chiffre d\'affaires' },
                bars: 'vertical',
                height: 400,
                colors: ['#e5e7e6', '#EEE6D8', '#DAAB3A', '#B67332','#93441A'], // Couleurs personnalisées
            };
            var chartToutesAgences = new google.visualization.ColumnChart(document.getElementById('chart_agences'));
            chartToutesAgences.draw(toutesAgencesData, toutesAgencesOptions);
        }
    });
}

</script>
@endsection
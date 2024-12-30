@extends('layouts.back')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<style>

form{
    display:inherit;
    width:100%;
}
</style>
<div class="row">
    <div class="col-lg-12 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Récapitulatif</h6>
            </div>
            <div class="card-body" style="min-height:500px">
                <div class="row mb-5">

                    <form method="get" action="{{route('recap')}}">
                        @if( auth()->user()->role=='admin' || auth()->user()->role=='respAG'  )

                        <div class="col-lg-2 col-md-6">
                            <span class=" mr-2">{{__('msg.User')}}:</span>
                            <select class="form-control mb-20 select2" id="commercial" name="user" style="max-width:300px" onchange="update_user();this.form.submit();">
                                <option @if($user=="" ) selected="selected" @endif value=""></option>
                                @foreach ($users as $User)
                                @if(trim($User->lastname)!=='')
                                <option @selected($user>0 && $user==$User->id) value="{{$User->id}}" >{{$User->name}} {{$User->lastname}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        @else
                        <input type="hidden" name="user" value="{{$user ?? auth()->user()->id}}" id="user">
                        @endif

                        <div class="col-lg-2 col-md-6">
                            <span class="mr-2">{{__('msg.Start date')}}:</span><br>
                            <input type="date" class="form-control mr-2" id="date_debut" name="date_debut" value="{{$date_debut ?? date('Y-m-01')}}" style="width:150px">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <span class="ml-3 mr-2">{{__('msg.End date')}}:</span><br>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{$date_fin ?? date('Y-m-t')}}" style="width:150px">
                        </div>
                        <div class="col-lg-2 col-md-6 mt-3"><!--
                            <span><input type="checkbox" @if( $date_debut=="{{date('Y-m-t')}}" && $date_fin=="{{date('Y-m-t')}}" ) checked="checked" @endif/>Mois courant</span>
                            <span><input type="checkbox" @if( $date_debut=="{{date('Y-m-t')}}" && $date_fin=="{{date('Y-m-t')}}" ) checked="checked" @endif/>Année courante</span>-->
                        </div>
                        <div class="col-lg-2 col-md-6 mt-2 mb-2">
                            <button type="submit" class="btn btn-primary  mr-3 mt-3">
                                Voir
                            </button>
                        </div>

                    </form>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <h5>Offres</h5>
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Offres : {{count($offres)}}</th><th> </th><th></th>
                            </tr>
                            <tr>
                                <td>TG : {{$offres_tg}} </td><td>Hors TG : {{$offres_hors_tg}} </td><td>Apprêts/Bij/DP : {{$offres_apprets}}</td>
                            </tr>
                            <tr>
                                <td>Validés : {{$offres_ok}}</td><td>En attente : {{$offres_attente}} </td><td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Statistiques des Offres</h5>
                        <div>
                            <button class="btn btn-primary" onclick="showChart('offresPieChart', 'offresBarChart')">Par Type</button>
                            <button class="btn btn-secondary" onclick="showChart('offresBarChart', 'offresPieChart')">Par Validation</button>
                        </div>
                        <div id="offresPieChart" class="chart-container" style="width: 100%; height: 300px;"></div>
                        <div id="offresBarChart" class="chart-container" style="width: 100%; height: 300px; display: none;"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <h5>Rendez vous</h5>
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Rendez vous : {{count($rendezvous)}}</th><th> </th>
                            </tr>
                            <td>Déplacements : {{$rdvs_deplacement}}</td><td>À distance: {{$rdvs_a_distance}}</td>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Statistiques des Rendez-vous</h5>
                        <div>
                            <!--
                            <button class="btn btn-primary" onclick="showChart('rdvsPieChart', 'rdvsBarChart')">Cercles</button>
                            <button class="btn btn-secondary" onclick="showChart('rdvsBarChart', 'rdvsPieChart')">Barres</button>
                            -->
                        </div>
                        <div id="rdvsPieChart" class="chart-container" style="width: 100%; height: 300px;"></div>
                        <div id="rdvsBarChart" class="chart-container" style="width: 100%; height: 300px; display: none;"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <h5>Réclamations</h5>
                        <table class="table table-striped table-hover">
                            <tr>
                                <th colspan="3">Réclamations initiées : {{count($retours)}}</th>
                            </tr>
                            <td>Positifs : {{$retours_positifs}}</td><td>Négatifs: {{$retours_negatifs}}</td><td>Infos : {{$retours_infos}}</td>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Statistiques des Réclamations</h5>
                        <div><!--
                            <button class="btn btn-primary" onclick="showChart('retoursPieChart', 'retoursBarChart')">Cercles</button>
                            <button class="btn btn-secondary" onclick="showChart('retoursBarChart', 'retoursPieChart')">Barres</button>
                            -->
                        </div>
                        <div id="retoursPieChart" class="chart-container" style="width: 100%; height: 300px;"></div>
                        <div id="retoursBarChart" class="chart-container" style="width: 100%; height: 300px; display: none;"></div>
                    </div>
                </div>

                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                    google.charts.load('current', { packages: ['corechart'] });
                    google.charts.setOnLoadCallback(drawAllCharts);

                    const chartColors = ['#e5e7e6', '#EEE6D8', '#DAAB3A', '#B67332', '#93441A'];

                    function drawAllCharts() {
                        drawOffresCharts();
                        drawRdvsCharts();
                        drawRetoursCharts();
                    }

                    function drawOffresCharts() {
                        const pieData = google.visualization.arrayToDataTable([
                            ['Catégorie', 'Nombre'],
                            ['TG', {{$offres_tg}}],
                            ['Hors TG', {{$offres_hors_tg}}],
                            ['Apprêts/Bij/DP', {{$offres_apprets}}],
                        ]);

                        const barData = google.visualization.arrayToDataTable([
                            ['Catégorie', 'Nombre', { role: 'style' }],
                            ['Validés', {{$offres_ok}}, chartColors[0]],
                            ['En attente', {{$offres_attente}}, chartColors[1]],
                        ]);

                        const options = { colors: chartColors ,  bars: 'vertical' ,
                            hAxis: {title: 'Catégories',  },  vAxis: { title: 'Nombre', } };

                        new google.visualization.PieChart(document.getElementById('offresPieChart')).draw(pieData, options);
                        new google.visualization.BarChart(document.getElementById('offresBarChart')).draw(barData, options);
                    }

                    function drawRdvsCharts() {
                        const pieData = google.visualization.arrayToDataTable([
                            ['Type', 'Nombre'],
                            ['Déplacements', {{$rdvs_deplacement}}],
                            ['À distance', {{$rdvs_a_distance}}],
                        ]);

                        const options = { colors: chartColors , bars: 'vertical' };
                        new google.visualization.PieChart(document.getElementById('rdvsPieChart')).draw(pieData, options);
                        new google.visualization.BarChart(document.getElementById('rdvsBarChart')).draw(pieData, options);
                    }

                    function drawRetoursCharts() {
                        const pieData = google.visualization.arrayToDataTable([
                            ['Type', 'Nombre'],
                            ['Positifs', {{$retours_positifs}}],
                            ['Négatifs', {{$retours_negatifs}}],
                            ['Infos', {{$retours_infos}}],
                        ]);

                        const options = { colors: chartColors , bars: 'vertical' };
                        new google.visualization.PieChart(document.getElementById('retoursPieChart')).draw(pieData, options);
                        new google.visualization.BarChart(document.getElementById('retoursBarChart')).draw(pieData, options);
                    }

                    function showChart(showId, hideId) {
                        document.getElementById(showId).style.display = 'block';
                        document.getElementById(hideId).style.display = 'none';
                    }
                </script>


            </div>
        </div>
    </div>
</div>







<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $('.select2').select2({
        filter: true,
        language: {
            noResults: function() {
                return 'Pas de résultats';
            }
        }
    });
</script>

@endsection
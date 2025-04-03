@extends('layouts.back')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<style>

form{
    display:inherit;
    width:100%;
}
.bold{
    font-weight:bold;
}
</style>
<div class="row">
    <div class="col-lg-12 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Récapitulatif</h6>
            </div>
            <div class="card-body" style="min-height:500px">
                <div class="row">
                    <div class="col-lg-9 col-sm-12">
                        <div class="row mb-5">
                            <form method="get" action="{{route('recap')}}">
                                @if( auth()->user()->role=='admin' || auth()->user()->role=='respAG'  )

                                <div class="col-lg-3 col-md-6">
                                    <span class=" mr-2">{{__('msg.User')}}:</span>
                                    <select class="form-control mb-20 select2" id="commercial" name="user" style="max-width:300px" onchange="update_user();this.form.submit();">
                                        <option @if($user=="" ) selected="selected" @endif value=""></option>
                                        @foreach ($users as $User)
                                        @if(trim($User->lastname)!=='')
                                        <option @selected($user>0 && $user==$User->id) value="{{$User->id}}" > {{$User->lastname}} {{$User->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                @else
                                <input type="hidden" name="user" value="{{$user ?? auth()->user()->id}}" id="user">
                                @endif

                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <span class="mr-2">Dates:</span><br>
                                    <select class="form-control" name="affichage" id="affichage">
                                        <option value="1" >Mois courant</option>
                                        <option value="2"  {{ $affichage== 2 ? 'selected="selected"' : '' }}>Année courante</option>
                                        <!--<option value="3"  {{ $affichage== 3 ? 'selected="selected"' : '' }}>Personnalisé</option>-->
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 hidden"  @if($affichage!= 3 ) style="display:none" @endif id="debut">
                                    <span class="mr-2">{{__('msg.Start date')}}:</span><br>
                                    <input type="date" class="form-control mr-2" id="date_debut" name="date_debut" value="{{$date_debut ?? date('Y-m-01')}}" style="width:150px">
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 hidden"  @if($affichage!= 3 ) style="display:none" @endif id="fin">
                                    <span class="ml-3 mr-2">{{__('msg.End date')}}:</span><br>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{$date_fin ?? date('Y-m-t')}}" style="width:150px">
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 mt-3 hidden "><!--
                                    <span><input type="checkbox" @if( $date_debut=="{{date('Y-m-t')}}" && $date_fin=="{{date('Y-m-t')}}" ) checked="checked" @endif/>Mois courant</span>
                                    <span><input type="checkbox" @if( $date_debut=="{{date('Y-m-t')}}" && $date_fin=="{{date('Y-m-t')}}" ) checked="checked" @endif/>Année courante</span>-->
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 mt-2 mb-2">
                                    <button type="submit" class="btn btn-primary  mr-3 mt-3">
                                        Voir
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12 ">
                        <h3 @if($clients >0 ) class="text-success" @endif>Clients crées : <strong>{{ $clients }}</strong></h3>
                    </div>
                </div>

                <div class="row pl-3 pr-3">
                    <!-- Offres -->
                    <div class="col-md-6">
                        <h5 class="text-primary">Offres</h5>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Statistique</th>
                                    <th>Actuelle</th>
                                    <th>Précédente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bold">
                                    <td>Total</td>
                                    <td>{{ count($offres) }}</td>
                                    <td>{{ count($prev_offres) }}</td>
                                </tr>
                                <tr>
                                    <td>TG</td>
                                    <td>{{ $offres_tg }}</td>
                                    <td>{{ $prev_offres_tg }}</td>
                                </tr>
                                <tr>
                                    <td>Hors TG</td>
                                    <td>{{ $offres_hors_tg }}</td>
                                    <td>{{ $prev_offres_hors_tg }}</td>
                                </tr>
                                <tr>
                                    <td>Apprêts</td>
                                    <td>{{ $offres_apprets }}</td>
                                    <td>{{ $prev_offres_apprets }}</td>
                                </tr>
                                <tr>
                                    <td>Validés</td>
                                    <td>{{ $offres_ok }}</td>
                                    <td>{{ $prev_offres_ok }}</td>
                                </tr>
                                <tr>
                                    <td>En attente</td>
                                    <td>{{ $offres_attente }}</td>
                                    <td>{{ $prev_offres_attente }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 hidden">
                        <h5>Statistiques des Offres</h5>
                        <div id="offresComparisonChart" style="width: 100%; height: 400px;"></div>
                    </div>

                    <!-- Rendez-vous -->
                    <div class="col-md-6">
                        <h5 class="text-primary">Rendez-vous</h5>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Statistique</th>
                                    <th>Actuelle</th>
                                    <th>Précédente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bold">
                                    <td>Total</td>
                                    <td>{{ count($rendezvous) }}</td>
                                    <td>{{ count($prev_rendezvous) }}</td>
                                </tr>
                                <tr>
                                    <td>Déplacements</td>
                                    <td>{{ $rdvs_deplacement }}</td>
                                    <td>{{ $prev_rdvs_deplacement }}</td>
                                </tr>
                                <tr>
                                    <td>À distance</td>
                                    <td>{{ $rdvs_a_distance }}</td>
                                    <td>{{ $prev_rdvs_a_distance }}</td>
                                </tr>
                                <tr>
                                    <td>En agence</td>
                                    <td>{{ $rdvs_agence }}</td>
                                    <td>{{ $prev_rdvs_agence }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 hidden">
                        <h5>Statistiques des Rendez-vous</h5>
                        <div id="rendezvousComparisonChart" style="width: 100%; height: 400px;"></div>
                    </div>

                    <!-- Retours -->
                    <div class="col-md-6">
                        <h5 class="text-primary">Interactions</h5>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Statistique</th>
                                    <th>Actuelle</th>
                                    <th>Précédente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bold">
                                    <td>Total</td>
                                    <td>{{ count($taches) }}</td>
                                    <td>{{ count($prev_taches) }}</td>
                                </tr>
                                <tr>
                                    <td>Appels téléphoniques</td>
                                    <td>{{ $appels }}</td>
                                    <td>{{ $prev_appels }}</td>
                                </tr>
                                <tr>
                                    <td>Remises de commandes</td>
                                    <td>{{ $remises }}</td>
                                    <td>{{ $prev_remises }}</td>
                                </tr>
                                <tr>
                                    <td>Suivis clients</td>
                                    <td>{{ $suivis }}</td>
                                    <td>{{ $prev_suivis }}</td>
                                </tr>
                                <tr>
                                    <td>Autres</td>
                                    <td>{{ $autres }}</td>
                                    <td>{{ $prev_autres }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Retours -->
                    <div class="col-md-6">
                        <h5 class="text-primary">Réclamations</h5>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Statistique</th>
                                    <th>Actuelle</th>
                                    <th>Précédente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bold">
                                    <td>Total</td>
                                    <td>{{ count($retours) }}</td>
                                    <td>{{ count($prev_retours) }}</td>
                                </tr>
                                <tr>
                                    <td>Positifs</td>
                                    <td>{{ $retours_positifs }}</td>
                                    <td>{{ $prev_retours_positifs }}</td>
                                </tr>
                                <tr>
                                    <td>Négatifs</td>
                                    <td>{{ $retours_negatifs }}</td>
                                    <td>{{ $prev_retours_negatifs }}</td>
                                </tr>
                                <tr>
                                    <td>Infos</td>
                                    <td>{{ $retours_infos }}</td>
                                    <td>{{ $prev_retours_infos }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 hidden">
                        <h5>Statistiques des Réclamations</h5>
                        <div id="retoursComparisonChart" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>

                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                    google.charts.load('current', { packages: ['corechart'] });
                    google.charts.setOnLoadCallback(drawAllCharts);

                    function drawAllCharts() {
                        drawComparisonChart('offresComparisonChart', [
                            ['Catégorie', 'Actuelle', 'Précédente'],
                            ['TG', {{ $offres_tg }}, {{ $prev_offres_tg }}],
                            ['Hors TG', {{ $offres_hors_tg }}, {{ $prev_offres_hors_tg }}],
                            ['Apprêts', {{ $offres_apprets }}, {{ $prev_offres_apprets }}],
                            ['Validés', {{ $offres_ok }}, {{ $prev_offres_ok }}],
                            ['En attente', {{ $offres_attente }}, {{ $prev_offres_attente }}],
                        ]);

                        drawComparisonChart('rendezvousComparisonChart', [
                            ['Type', 'Actuelle', 'Précédente'],
                            ['Déplacements', {{ $rdvs_deplacement }}, {{ $prev_rdvs_deplacement }}],
                            ['À distance', {{ $rdvs_a_distance }}, {{ $prev_rdvs_a_distance }}],
                            ['En agence', {{ $rdvs_agence }}, {{ $prev_rdvs_agence }}],
                        ]);

                        drawComparisonChart('retoursComparisonChart', [
                            ['Type', 'Actuelle', 'Précédente'],
                            ['Positifs', {{ $retours_positifs }}, {{ $prev_retours_positifs }}],
                            ['Négatifs', {{ $retours_negatifs }}, {{ $prev_retours_negatifs }}],
                            ['Infos', {{ $retours_infos }}, {{ $prev_retours_infos }}],
                        ]);
                    }

                    function drawComparisonChart(elementId, data) {
                        const chartData = google.visualization.arrayToDataTable(data);
                        const options = {
                            bars: 'vertical',
                            hAxis: { title: 'Catégories' },
                            vAxis: { title: 'Quantité' },
                            isStacked: true,
                            colors: ['#4285F4', '#EA4335'],
                            responsive: true,
                        };
                        const chart = new google.visualization.ColumnChart(document.getElementById(elementId));
                        chart.draw(chartData, options);
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

    $("#affichage").change(function(){
        var option = $(this).find("option:selected").val();
        if(option==3){
            $("#debut").show('slow');
            $("#fin").show('slow');
            $("#date_debut").val("{{date('Y-m-d')}}");
            $("#date_fin").val("{{date('Y-m-t')}}");
        }

        if(option==1){
            $("#debut").hide('slow');
            $("#fin").hide('slow');
            $("#date_debut").val("{{date('Y-m-01')}}");
            $("#date_fin").val("{{date('Y-m-t')}}");
        }
        if(option==2){
            $("#debut").hide('slow');
            $("#fin").hide('slow');
            $("#date_debut").val("{{date('Y-01-01')}}");
            $("#date_fin").val("{{date('Y-12-31')}}");
        }

    });
</script>

@endsection
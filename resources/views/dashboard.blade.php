
@extends('layouts.back')

@section('content')

<?php

?>
<style>
	h5,b{
		width:100%;
	}
	h5{
		color:black;
	}
	.circle {
    padding:1%;
    background-color:#2e3e4e;
    width:150px;
    height:150px;
    border-radius:100%;

    text-align:center;
    font-size:50px;
    line-height:1em;
    color:white;
	font-weight:100;
    margin-left:auto;
    margin-right:auto;
    margin-top:5%;
    margin-bottom:5%;
  /*Want to add some cut-out lines? Uncomment to view.
    border:2px #F2F2DF dashed; */
}


</style>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
			['Client', 'Chiffre d\'affaire'],
			<?php
			foreach($clients as $cl){
                echo '[' . json_encode($cl->nom) . ', ' . intval($cl->CA) . '],';
			}
			?>

        ]);

        var options = {
          //title: 'TOP CLIENTS',
		  colors: ['#e5e7e6', '#EEE6D8', '#DAAB3A', '#B67332', '#93441A'],
		  is3D: true,
		  titleTextStyle: {
                    color: 'black',
                    fontName: 'Nunito',
                    fontSize: 18
                },
                legendTextStyle: {
                    color: 'black',
                    fontName: 'Nunito'
                },
                pieSliceTextStyle: {
                    color: 'black',
                    fontName: 'Nunito'
                },
                backgroundColor: 'transparent',
                //chartArea: {width: '90%', height: '90%'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>

<div class="" style="padding-left:5%;padding-right:5%;padding-top:2%;padding-bottom:2%">

	<div class="row">
		<div class="col-md-12 text-center">
			<span class="text-center mb-5" style="color:black">Bienvenue  <b>{{ auth()->user()->name }} {{ auth()->user()->lastname }}</b> sur votre nouvel outil CRM !</span><br><br><br>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12 text-center  mb-5">
			<h4>Nombre de clients</h4>
			<div class="circle">
				<p style="margin-top:revert">{{ $total_clients }}</p>
			</div>
			<!--<h1><b>{{ $total_clients }}</b></h1>-->
		</div>
		<div class="col-md-6 col-lg-6 col-sm-12">
			<h4 class="text-center">Top clients</h4>
			<div id="piechart" style="width:100%!important; height: 300px;"></div>
		</div>
		<div class="col-md-6 col-lg-6 col-sm-12">
			<h4 class="text-center">Prochains rendez vous</h4>
			<table id="" class="table table-striped" style="width:100%!important">
                <thead>
                    <tr style="background-color:#2e3e4e;color:white;" id="">
                        <th>ID</th>
                        <th>Client</th>
                        <th>Sujet</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($rendezvous as $rv)
	  					<tr>
						<td><a href="{{route('rendezvous.show',['id'=>$rv->id])}}">{{ $rv->id }}</a></td>
						<td>{{ $rv->Account_Name }}</td>
						<td>{{ $rv->Subject }}</td>
						<td>{{ date('d/m/Y', strtotime($rv->Started_at)) }} {{$rv->heure_debut}}</td>
						</tr>
					  @endforeach
				</tbody>
			</table>
		</div>
		<div class="col-md-6 col-lg-6 col-sm-12 pl-5">
    <h4  > </h4>
			<table id="" class="table table-striped" style="width:100%!important">
                <thead>
                    <tr style="background-color:#2e3e4e;color:white;" id="">
                        <th>Client</th>
                        <th>Chiffre d'affaire</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($clients as $cl)
                  <tr><td>{{$cl->nom}}</td><td>{{$cl->CA}}</td></tr>
                @endforeach
				</tbody>
			</table>

		</div>
	</div>

</div>


	<!-- maintenance Modal-->
	<div class="modal fade" id="maintenance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel">Bienvenue sur le CRM </h6>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body  "  >
			<div style="font-size:14px;">
				<h5> NOUVEAUTÉ !</h5>
 1. Ajout de rendez-vous hors clientèle :<br>
 - Un nouvel onglet dans le menu permet désormais d'ajouter des rendez-vous qui ne concernent pas les clients.<br><br>
2.  Remontée automatique des prises de contact AS400 :<br>
- Les prises de contact AS400 sont maintenant automatiquement remontées dans le système.<br><br>
3. Possibilité d'entrer des prises de contact hors AS400 :<br>
- Vous pouvez toujours entrer manuellement des prises de contact hors AS400 via la fiche client.<br><br>
4. Nouveau tableau de statistiques :<br>
- Un tableau permet de visualiser les clients inactifs en fonction du nombre de mois sélectionné.
				</div>
		</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>


	<script>
		$(document).ready(function() {
			setTimeout(function() {
				$('#maintenance').modal('show');
			}, 5000); // 5000 millisecondes = 5 secondes
		});
	</script>


@endsection

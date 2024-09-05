
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
	#calendar{
		margin-left:5%;
		margin-right:5%;
		margin-bottom:2%;
		margin-top:2%;
	}
</style>
<div class="" style="padding-left:5%;padding-right:5%;padding-top:2%">
	<span style="color:black">Bienvenue  {{ auth()->user()->name }} {{ auth()->user()->lastname }} sur votre nouvel outil CRM !</span><br><br>

	<div class="row">
		<div class="col-md-4 col-lg-4 text-center">
			<h4>Nombre de clients</h4> <h1><b>{{ $total_clients }}</b></h1>
		</div>
		<div class="col-md-8 col-lg-8">
			<table id="" class="table table-striped" style="width:80%!important">
                <thead>
                    <tr style="background-color:#2e3e4e;color:white;" id="">
                        <th>Nom</th>
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
		<div class="col-md-12">
			<div id="calendar"></div><br>
		</div>
	</div>


	<!-- maintenance Modal-->
	<div class="modal fade" id="maintenance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Développement en cours </h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body text-center"  style="">
			<div style="font-size:16px;">
				<h5>Bienvenue sur le CRM</h5><br>
				<b style="color:red">
				Certaines données ou certains modules peuvent être manquants ou présenter des bugs.<br>
				Merci de nous en informer.</b><br>
				</div>
		</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.min.js" integrity="sha512-xCMh+IX6X2jqIgak2DBvsP6DNPne/t52lMbAUJSjr3+trFn14zlaryZlBcXbHKw8SbrpS0n3zlqSVmZPITRDSQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.global.js" integrity="sha512-3I+0zIxy2IkeeCvvhXUEu+AFT3zAGuHslHLDmM8JBv6FT7IW6WjhGpUZ55DyGXArYHD0NshixtmNUWJzt0K32w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.global.min.js" integrity="sha512-PneTXNl1XRcU6n5B1PGTDe3rBXY04Ht+Eddn/NESwvyc+uV903kiyuXCWgL/OfSUgnr8HLSGqotxe6L8/fOvwA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.js" integrity="sha512-bBl4oHIOeYj6jgOLtaYQO99mCTSIb1HD0ImeXHZKqxDNC7UPWTywN2OQRp+uGi0kLurzgaA3fm4PX6e2Lnz9jQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
        document.addEventListener('DOMContentLoaded', function() {
      	var calendarEl = document.getElementById('calendar');

		// Récupération des données des rendez-vous en PHP
		var events = <?php echo json_encode(array_map(function($rv) {
        return [
          'title' => $rv['Nom'] . ' ' . $rv['Subject'],
          'start' => date('c', strtotime($rv['Started_at'])),
          'end' => date('c', strtotime($rv['End_at'])),
		  //'url'=>  "{{route('rendezvous.show',['id'=>$rv['id']])}}"
		  'url'=> "https://crm.mysaamp.com/rendezvous/show/".$rv['id']
        ];
      }, $rendezvous->toArray())); ?>;


		var calendar = new FullCalendar.Calendar(calendarEl, {
			themeSystem: 'bootstrap',
			locale: 'fr', // Set locale to French
			initialView: 'dayGridMonth',
			headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay'
			},
			events: events,
			eventColor: '#378006', // Optional: Customize event color
		});

		calendar.render();
    });
  </script>

	<script>
		$(document).ready(function() {
			setTimeout(function() {
				$('#maintenance').modal('show');
			}, 5000); // 5000 millisecondes = 5 secondes
		});
	</script>

</div>

@endsection

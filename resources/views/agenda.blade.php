@extends('layouts.back')

@section('content')

<?php

?>
<style>
	h2{
		width:100%;
		margin-bottom:20px;
		color:black;

	}
	h5{
		color:black;
		margin-top:20px;
	}
	h4{
		color:black;
	}
	#calendar{
		margin-left:5%;
		margin-right:5%;
		margin-bottom:2%;
		margin-top:2%;
	}
</style>


<div class="row">

	<!-- Content Column -->
	<div class="col-lg-12 mb-4">

		<!-- Project Card Example -->
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Mon calendrier</h6>
			</div>
			<div class="card-body">

				<div id="calendar"></div>

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

		  var events = <?php echo json_encode(array_map(function($rv) {
        // Récupération de la date de début et de fin
        $start_date = date('Y-m-d', strtotime($rv['Started_at']));
        $end_date = date('Y-m-d', strtotime($rv['End_at']));

        // Récupération des heures de début et de fin
        $start_time = $rv['heure_debut']; // Format: 'HH:mm'
        $end_time = $rv['heure_fin'];     // Format: 'HH:mm'

        // Combinaison des dates et heures
        $startDateTime = $start_date . ' ' . $start_time;
        $endDateTime = $end_date . ' ' . $end_time;

        // Retourne l'événement pour FullCalendar
        return [
          'title' => $rv['Account_Name'] . ' ' . $rv['Subject'],
          'start' => date('c', strtotime($startDateTime)), // Combinaison de la date et heure de début
          'end' => date('c', strtotime($endDateTime)),     // Combinaison de la date et heure de fin
          'url' => "https://crm.mysaamp.com/rendezvous/show/".$rv['id']
        ];
    }, $rendezvous->toArray())); ?>;



		var calendar = new FullCalendar.Calendar(calendarEl, {
			themeSystem: 'bootstrap',
			locale: 'fr', // Set locale to French
			initialView: 'dayGridMonth',
			headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
			},
			events: events,

			eventColor: '#378006', // Optional: Customize event color
		});

		calendar.render();
    });
  </script>

</div>




@endsection
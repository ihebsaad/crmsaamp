@extends('layouts.back')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
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
		/*
		margin-left:5%;
		margin-right:5%;
		margin-top:2%;
		*/
		margin-bottom:2%;
      	width: 100%;
      	max-width: 100%;
      	margin: 0 auto;
  	}

	.fc .fc-view-container {
		width: 100%;
	}

	@media (min-width: 481px) and (max-width: 767px) {
		.fc-toolbar-title{
			font-size:13px!important;
		}
		.fc-button{
			padding:3px 3px !important;
		}
		.fc-dayGridMonth-button,.fc-timeGridWeek-button{
			display:none!important;
		}
		.fc-daygrid-day,td [role="gridcell"]{
			width:100%!important;
		}
		.fc-today-button{
			margin-top:8px!important;
		}
	}



	@media (min-width: 320px) and (max-width: 480px) {
		.fc-toolbar-title{
			font-size:13px!important;
		}
		.fc-button{
			padding:3px 3px !important;
		}
		.fc-dayGridMonth-button,.fc-timeGridWeek-button{
			display:none!important;
		}
		.fc-daygrid-day{
			width:100%!important;
		}
		.fc-today-button{
			margin-top:8px!important;
		}
	}
</style>


<div class="row">

	<!-- Content Column -->
	<div class="col-lg-12 mb-4">

		<!-- Project Card Example -->
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">{{__('msg.My')}} {{__('msg.Diary')}}</h6>
			</div>
			<div class="card-body">
				<div class="row">

						@if( auth()->user()->role=='admin' || auth()->user()->role=='respAG' || auth()->user()->role=='adv' || auth()->user()->role=='compta' || auth()->id() == 334)
							<div class="col-lg-4">
								<form  method="get"  action="{{route('agenda')}}"  >
									<div class="row">
										<div class="col-lg-12">
											<span class=" mr-2">{{__('msg.User')}}:</span>
											<select class="form-control select2 mb-20" id="commercial" name="user"    style="max-width:300px"  onchange="update_user();this.form.submit();" >
												<option  @if($user=="") selected="selected" @endif value=""></option>
												@if(auth()->id() == 334)
												<option @selected($user >0 && $user==334) value="334"  >Stéphane Devès</option>
												<option @selected($user >0 && $user==141) value="141"  >Patricia Delmas</option>
												@else
												@foreach($users as $User)
													@if(trim($User->lastname)!=='')
														<option @selected($user >0 && $user==$User->id) value="{{$User->id}}"  >{{$User->lastname}}  {{$User->name}}</option>
													@endif
												@endforeach
												@endif
											</select>
										</div>
									</div>
								</form>
							</div>
						@else
							@if(request()->is('exterieurs'))
								<!--<a href="{{route('rendezvous.create',['id'=>0])}}" class="btn btn-primary mb-3 mr-3 float-right"><i class="fas fa-calendar-day"></i>  Créer un Rendez-vous Extérieur</a>-->
							@endif
						@endif


					<div class="col-lg-8 col-md-12">
						<form action="{{route('print_agenda')}}" id="agendaForm">
							<input type="hidden" name="user" value="{{$user ?? auth()->user()->id}}" id="user">
							<div class="row mb-2" style="border:1px solid lightgray;border-radius:20px">
								<div class="col-lg-9 col-md-12 col-sm-12 float-right mt-2 mb-2">
									<span class="mr-2">{{__('msg.Start date')}}:</span>
									<input type="date" class="form-control mr-2" id="date_debut" name="date_debut" value="{{date('Y-m-01')}}" style="width:150px">
									<span class="ml-3 mr-2">{{__('msg.End date')}}:</span>
									<input type="date" class="form-control" id="date_fin" name="date_fin" value="{{date('Y-m-t')}}" style="width:150px">
								</div>
								<div class="col-lg-3 col-md-12 col-sm-4 mt-2 mb-2">
									<button type="submit" class="btn btn-primary  mr-3" title="Impression">
										<i class="fas fa-print"></i>
									</button>
									<button type="button" id="btnPdf" class="btn mr-3 btn-danger" title="Liste PDF">
										<i class="fas fa-file-pdf"></i>
									</button>
									<button type="button" id="btnSynthese" class="btn btn-success" title="Synthèse par type">
										<i class="fas fa-list-alt"></i>
									</button>
								</div>
							</div>
						</form>
					</div>

				<div id="calendar" class="container-fluid"></div>

			</div>
		</div>

	</div>
	<script>
	document.getElementById('btnPdf').addEventListener('click', function() {
		let form = document.getElementById('agendaForm');
		form.action = "{{ route('pdf_agenda') }}";
		form.submit();
	});
	
	document.getElementById('btnSynthese').addEventListener('click', function() {
		let form = document.getElementById('agendaForm');
		form.action = "{{ route('pdf_synthese') }}";
		form.submit();
	});
	</script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.min.js" integrity="sha512-xCMh+IX6X2jqIgak2DBvsP6DNPne/t52lMbAUJSjr3+trFn14zlaryZlBcXbHKw8SbrpS0n3zlqSVmZPITRDSQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.global.js" integrity="sha512-3I+0zIxy2IkeeCvvhXUEu+AFT3zAGuHslHLDmM8JBv6FT7IW6WjhGpUZ55DyGXArYHD0NshixtmNUWJzt0K32w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.global.min.js" integrity="sha512-PneTXNl1XRcU6n5B1PGTDe3rBXY04Ht+Eddn/NESwvyc+uV903kiyuXCWgL/OfSUgnr8HLSGqotxe6L8/fOvwA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.js" integrity="sha512-bBl4oHIOeYj6jgOLtaYQO99mCTSIb1HD0ImeXHZKqxDNC7UPWTywN2OQRp+uGi0kLurzgaA3fm4PX6e2Lnz9jQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
		function update_user(){
			$('#user').val( $('#commercial').val());
		}

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


		$heure_debut = date('H', strtotime($startDateTime)); // Récupérer l'heure (format 24h)

		// Appliquer une couleur selon l'heure de début
		if ($heure_debut < 12) {
			$color = '#378006'; // Couleur pour le matin
		} else if ($heure_debut >= 12 && $heure_debut < 18) {
			$color = '#33C3FF'; // Couleur pour le midi
		} else {
			$color = '#FF33A2'; // Couleur pour le soir
		}
		$location = $rv['Location']!='' ? ' ('.$rv['Location'].')' : ' ';

		// Retourne l'événement pour FullCalendar
		return [
			'title' => $rv['Account_Name'] . ' ' . $rv['Subject'].' '. mb_strimwidth($rv['Description'], 0, 100, "..."),
			'start' => date('c', strtotime($startDateTime)), // Combinaison de la date et heure de début
			'end' => date('c', strtotime($endDateTime)),     // Combinaison de la date et heure de fin
			'url' => "https://crm.mysaamp.com/rendezvous/show/".$rv['id'],
			'color' => $color, // Attribuer la couleur en fonction de l'heure
			'location' => $location,
			];
    }, $rendezvous->toArray())); ?>;

		function getInitialView() {
        	return window.innerWidth < 768 ? 'timeGridDay' : 'dayGridMonth';
    	}

		var calendar = new FullCalendar.Calendar(calendarEl, {
			themeSystem: 'bootstrap',
			locale: 'fr', // Set locale to French
			initialView: getInitialView(),
			headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
			},
			events: events,

			//eventColor: '#378006', // Optional: Customize event color

			eventDidMount: function(info) {
				if (info.view.type.startsWith('list')) {
					// Cible la classe correcte pour la vue liste
					var titleEl = info.el.querySelector('.fc-list-event-title');
					if (titleEl) {
						titleEl.innerHTML = info.event.title + ' <strong>' + info.event.extendedProps.location + '</strong>';
					}
				} else {
					// Dans les vues de grille, on peut garder le texte simple
					var titleEl = info.el.querySelector('.fc-event-title');
					if (titleEl) {
						titleEl.textContent = info.event.title;
					}
				}
			},

			/*
			eventContent: function(arg) {
				return {
					html: '<b>' + arg.event.title + '</b>' // Ici vous pouvez formater l'affichage du titre complet
				};
			},*/
			//aspectRatio: 1.8, // Adjust this ratio for responsiveness

			windowResize: function(view) {
				// Ajuste la vue lors du redimensionnement
				var newView = getInitialView();
				if (calendar.view.type !== newView) {
					calendar.changeView(newView);
				}
				calendar.updateSize(); // Force le recalcul de la taille du calendrier
        	}
		});

		calendar.render();
		calendar.updateSize();
		window.dispatchEvent(new Event('resize'));

    });


	$('.select2').select2({
        filter: true,
        language: {
            noResults: function() {
                return 'Pas de résultats';
            }
        }
    });
  </script>

</div>




@endsection
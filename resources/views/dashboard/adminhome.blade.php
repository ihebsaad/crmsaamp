@extends('layouts.back')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<?php

?>
<style>
	h5,
	b {
		width: 100%;
	}

	h5 {
		color: black;
	}

	.circle {
		padding: 1%;
		background-color: #2e3e4e;
		width: 75px;
		height: 75px;
		border-radius: 100%;
		text-align: center;
		font-size: 25px;
		line-height: 25px;
		color: white;
		font-weight: 100;
		/*
		margin-left: auto;
		margin-right: auto;*/
		margin-left: 50px;
		margin-right: 50px;
		margin-top: 1%;
		margin-bottom: 3%;
		display: inline-block;
		cursor: pointer;
	}

	table td {
		font-size: 12px;

	}

	.table-container {
		position: relative;
		height: 400px;
		overflow: scroll;
	}

	.table-container thead th {
		position: sticky;
		top: 0;
		z-index: 10;
		background-color: #2e3e4e;
	}

	/*
	p[data-title]:hover::after {
		content: attr(title);
		position: absolute;
		top: -20px;
		left: 0;
		background-color: #2e3e4e;
		color: white;
		padding: 5px 5px;
		font-size: 15px;
	}
*/

	[data-title]:hover:after {
		opacity: 1;
		transition: all 0.1s ease 0.5s;
		visibility: visible;
	}

	[data-title]:after {
		content: attr(data-title);
		position: absolute;
		/*bottom: -1.6em;*/
		left: 100%;
		padding: 4px 4px 4px 8px;
		white-space: nowrap;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		-moz-box-shadow: 0px 0px 4px #222;
		-webkit-box-shadow: 0px 0px 4px #222;
		box-shadow: 0px 0px 4px #222;
		opacity: 0;
		z-index: 99999;
		visibility: hidden;
		background-color: #2e3e4e;
		color: white;
		padding: 5px 5px;
		font-size: 15px;
	}

	[data-title] {
		position: relative;
	}

	#commercial {
		width: 160px;
		font-size: 13px;
		padding: 3px 6px 3px 6px;
		margin-left: 10px;
	}

	.sortable,
	th {
		cursor: pointer;
	}
</style>


<div class="" style="padding-left:5%;padding-right:5%;padding-top:2%;padding-bottom:2%">

	<div class="row">
		<div class="col-md-12 text-center">
			<span class="text-center mb-2" style="color:black">{{__('msg.Welcome')}} <b>{{ auth()->user()->name }} {{ auth()->user()->lastname }}</b> </span><br><br>
		</div>
	</div>
	@if(auth()->user()->role=='admin')
		<div class="row">
			@if(session()->get('hasClonedUser') == 1)
				<div class="col-md-6">
					<div class="alert alert-info">
						Connecté en tant que : <b>{{ auth()->user()->name }} {{ auth()->user()->lastname }}</b>
						<a href="{{ route('revert.login', session('previoususer')) }}" class="btn btn-warning btn-sm float-right">Revenir à l'utilisateur précédent</a>
					</div>
				</div>
			@else
				<div class="col-md-6"></div>
				<div class="col-md-6">
					<form action="{{ route('loginas') }}" method="POST" class="alert alert-info">
						@csrf
						<select name="user_id" class="form-control select2" style="width:220px">
							@foreach($users as $user)
								<option value="{{ $user->id }}">{{ $user->lastname }} {{ $user->name }}</option>
							@endforeach
						</select>
						<button type="submit" class="btn btn-success btn-sm">Se connecter en tant que</button>
					</form>
				</div>
			@endif
		</div>
	@endif
	<div class="row mt-2 mb-2">
		@if(!$userToken)
		<div class="col-md-12 float-right ml-2 mr-2">
			<a href="{{ route('google.auth.redirect') }}" class="btn btn-primary float-right"><img width="40" style="width:40" src="{{  URL::asset('img/calendar.png') }}" /> Lier les rendez-vous à mon Agenda</a>
		</div>
		@endif
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<h4>{{__('msg.Number of customers')}}</h4>
		</div>
	</div>
	<div class="row mb-5">
		<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
			<h5>Paris</small></h5>
			<div class="circle">
				<p style="margin-top:revert" data-title="Total des clients">{{ $total_clients_1 }}</p>
			</div>
			<div class="circle">
				<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $total_1 }}</p>
			</div>
		</div>

		<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
			<h5>Lyon</h5>
			<div class="circle">
				<p style="margin-top:revert" data-title="Total des clients">{{ $total_clients_2 }}</p>
			</div>
			<div class="circle">
				<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $total_2 }}</p>
			</div>
		</div>

		<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
			<h5> Marseille</h5>
			<div class="circle">
				<p style="margin-top:revert" data-title="Total des clients">{{ $total_clients_3 }}</p>
			</div>
			<div class="circle">
				<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $total_3 }}</p>
			</div>
		</div>

		<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
			<h5> Aubagne</h5>
			<div class="circle">
				<p style="margin-top:revert" data-title="Total des clients">{{ $total_clients_4 }}</p>
			</div>
			<div class="circle">
				<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $total_4 }}</p>
			</div>
		</div>

		<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
			<h5> Varsovie</h5>
			<div class="circle">
				<p style="margin-top:revert" data-title="Total des clients">{{ $total_clients_5 }}</p>
			</div>
			<div class="circle">
				<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $total_5 }}</p>
			</div>
		</div>
		<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
			<h5> Cayenne</h5>
			<div class="circle">
				<p style="margin-top:revert" data-title="Total des clients">{{ $total_clients_6 }}</p>
			</div>
			<div class="circle">
				<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $total_6 }}</p>
			</div>
		</div>
		<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
			<h5> Nice</h5>
			<div class="circle">
				<p style="margin-top:revert" data-title="Total des clients">{{ $total_clients_7 }}</p>
			</div>
			<div class="circle">
				<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $total_7 }}</p>
			</div>
		</div>
		<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
			<h5> Toulouse</h5>
			<div class="circle">
				<p style="margin-top:revert" data-title="Total des clients">{{ $total_clients_8 }}</p>
			</div>
			<div class="circle">
				<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $total_8 }}</p>
			</div>
		</div>
		<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
			<h5> Bordeaux</h5>
			<div class="circle">
				<p style="margin-top:revert" data-title="Total des clients">{{ $total_clients_9 }}</p>
			</div>
			<div class="circle">
				<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $total_9 }}</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12 mb-5">
			<h4 class="text-center">{{__('msg.Unclosed complaints')}}</h4>
			<div class="table-container" style="margin-top:36px">
				<table id="" class="table table-striped" style="width:90%!important;margin-left:5%">
					<thead>
						<tr style="background-color:#2e3e4e;color:white;" id="">
							<th>{{__('msg.Title')}}</th>
							<th>{{__('msg.Open date')}}</th>
							<th>{{__('msg.Customer')}}</th>
							<th>{{__('msg.Contact')}}</th>
							<th>{{__('msg.Reason')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($retours as $retour)
						<tr>
							<td><a href="{{route('retours.show',['id'=>$retour->id])}}">{{$retour->Name}}</a></td>
							<td data-order="{{ $retour->Date_ouverture ? date('Y-m-d', strtotime($retour->Date_ouverture)) : '' }}">{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</td>
							<td>{{$retour->Nom_du_compte}}</td>
							<td>{{$retour->Nom_du_contact}}</td>
							<td>{{$retour->Motif_retour}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>

		@if(auth()->user()->role=='admin')
<!--
		<div class="col-md-6 col-lg-6 col-sm-12 mb-5">
			<h4 class="text-center">{{__('msg.Today appointments')}}</h4>
			<div class="table-container" style="margin-top:36px">
				<table id="appointments-table" class="table table-striped" style="width:90%!important;margin-left:5%">
					<thead>
						<tr style="background-color:#2e3e4e;color:white;">
							<th class="sortable" data-column="heure_debut">   {{__('msg.Subject')}}   </th>
							<th class="sortable" data-column="Nom_de_compte">Client</th>
							<th class="sortable" data-column="Agence">{{__('msg.Agency')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($taches as $tache)
						<tr>
							<td>
								@if($tache->heure_debut){{ $tache->heure_debut }}  @endif
								@if(isset($tache->id))<a href="{{route('taches.show',['id'=>$tache->id])}}">{{ $tache->Subject }}</a>
								@else
								{{$tache->Description}}
								@endif
							</td>
							<td><small>{{ $tache->Nom_de_compte }}</small></td>
							<td><small>{{ $tache->Agence }}</small></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
--->

		<section class="col-md-6 col-lg-6 col-sm-12 mb-3">
			<h4 class="text-center">{{__('msg.Coming appointments')}}</h4>

			<div class="text-center" style="color:#2e3e4e">{{__('msg.Commercial')}} <select id="commercial" onchange="filter_comm()" class="form-control"></div>
			<option>Tous</option>
			@foreach( $representants as $rep )
			<option value="{{$rep->users_id}}">{{$rep->prenom}} {{$rep->nom}}</option>
			@endforeach
			</select>
			<div class="table-container">
				<table id="" class="table table-striped" style="width:90%!important;margin-left:5%">
					<thead>
						<tr style="background-color:#2e3e4e;color:white;" id="">
							<th>ID</th>
							<th>{{__('msg.Customer')}}</th>
							<th>{{__('msg.Subject')}}</th>
							<th>{{__('msg.Date')}}</th>
							<th>{{__('msg.Attributed to')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($rendezvous as $rv)
						<tr class="users user-{{$rv->user_id}}">
							<td><a href="{{route('rendezvous.show',['id'=>$rv->id])}}">{{ $rv->id }}</a></td>
							<td>{{ $rv->Account_Name }}</td>
							<td>{{ $rv->Subject }}</td>
							<td>{{ date('d/m/Y', strtotime($rv->Started_at)) }} {{$rv->heure_debut}}</td>
							<td>
								@if($rv->user_id > 0 )
								<?php $user = \App\Models\User::find($rv->user_id); ?>
								<h6>{{ $user->name}} {{ $user->lastname}}</h6>
								@else
								<h6>{{ $rv->Attribue_a}}</h6>
								@endif
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</section>
		@endif
		@if(auth()->user()->role=='admin')
		<div class="col-md-6 col-lg-6 col-sm-12 mb-3">
			<h4 class="text-center">{{__('msg.Price offers to be validated')}}</h4>
			<h4 class=""> </h4>
			<div class="table-container">

				<table id="" class="table table-striped" style="width:90%!important;margin-left:5%">
					<thead>
						<tr style="background-color:#2e3e4e;color:white;" id="">
							<th>ID</th>
							<th>{{__('msg.Creation')}}</th>
							<th>{{__('msg.Name')}}</th>
							<th>{{__('msg.Customer')}}</th>
							<th>{{__('msg.By')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($offres as $offre)
						@php $user=\App\Models\User::find($offre->user_id); @endphp
						<tr>
							<td><a href="{{route('offres.show',['id'=>$offre->id])}}">{{$offre->id}}</a></td>
							<td>{{ date('d/m/Y', strtotime($offre->Date_creation))}}</td>
							<td>{{$offre->Nom_offre}}</td>
							<td>{{$offre->nom_compte}}</td>
							<td>{{$user->name ?? ''}} {{$user->lastname ?? ''}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>

		</div>
		@endif

	</div>

</div>


<script>
	function filter_comm() {
		var user = $('#commercial').find(":selected").val();
		if (user > 0) {
			toggle('users', 'none');
			toggle('user-' + user, 'table-row');
		} else {
			toggle('users', 'table-row');
		}
	}

	function toggle(className, displayState) {
		var elements = document.getElementsByClassName(className);
		for (var i = 0; i < elements.length; i++) {
			elements[i].style.display = displayState;
		}
	}

	$(document).ready(function() {
		setTimeout(function() {
			$('#maintenance').modal('show');
		}, 5000); // 5000 milliseconds = 5 seconds

		// Function to detect and parse dates in dd/mm/yyyy format
		function parseDate(dateString) {
			const [day, month, year] = dateString.split('/');
			return new Date(year, month - 1, day); // JavaScript months are 0-based
		}

		// Add click event to each table header for sorting
		$('th').on('click', function() {
			const $header = $(this);
			const $table = $header.closest('table');
			const $tbody = $table.find('tbody');
			const rows = $tbody.find('tr').toArray();
			const columnIndex = $header.index();
			const order = $header.hasClass('asc') ? 'desc' : 'asc';

			// Remove sorting classes from other headers in the same table
			$header.siblings().removeClass('asc desc');
			$header.addClass(order);

			// Sort rows with date handling
			rows.sort(function(rowA, rowB) {
				const cellA = $(rowA).find('td').eq(columnIndex).text().trim();
				const cellB = $(rowB).find('td').eq(columnIndex).text().trim();

				// Detect if the column contains dates by checking the format dd/mm/yyyy
				const isDateColumn = /^\d{2}\/\d{2}\/\d{4}$/.test(cellA) && /^\d{2}\/\d{2}\/\d{4}$/.test(cellB);

				let valA, valB;

				if (isDateColumn) {
					// Parse and compare dates
					valA = parseDate(cellA);
					valB = parseDate(cellB);
				} else if ($.isNumeric(cellA) && $.isNumeric(cellB)) {
					// Parse and compare numeric values
					valA = parseFloat(cellA);
					valB = parseFloat(cellB);
				} else {
					// Compare strings alphabetically
					valA = cellA.toLowerCase();
					valB = cellB.toLowerCase();
				}

				if (order === 'asc') {
					return valA > valB ? 1 : -1;
				} else {
					return valA < valB ? 1 : -1;
				}
			});

			// Append sorted rows to the table
			$.each(rows, function(index, row) {
				$tbody.append(row);
			});
		});
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
@endsection
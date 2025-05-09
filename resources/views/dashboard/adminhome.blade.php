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
		width: 50px;
		height: 50px;
		border-radius: 100%;
		text-align: center;
		font-size: 15px;
		line-height: 15px;
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
	.tab-pane{
        padding-top:25px;
        padding-bottom:25px;
        padding-left:15px;
        padding-right:15px;
    }
    .nav-link{
        color:#4e73df;width:250px;text-align:center;font-weight:bold;
    }
    #myTab1 .nav-link i {
        color: #ed1b24 !important;
    }
    .nav-link.active{
        color:#4e73df!important;
    }
</style>


<div class="" style="padding-left:5%;padding-right:5%;padding-top:2%;padding-bottom:2%">

	<div class="row">
		<div class="col-md-12">
			<span class="  mb-2" style="color:black">{{__('msg.Welcome')}} <b> {{ auth()->user()->lastname }} {{ auth()->user()->name }}</b> </span><br><br>
		</div>
		@if(auth()->user()->user_role==1 || auth()->user()->user_role==2 )

		<div class="col-lg-5 col-md-12">
				<div class="row">
					@if(session()->get('hasClonedUser') == 1)
						<div class="col-md-6-">
							<div class="alert alert-info">
								Connecté en tant que : <b> {{ auth()->user()->lastname }} {{ auth()->user()->name }}</b>
								<a href="{{ route('revert.login', session('previoususer')) }}" class="btn btn-warning btn-sm float-right">Revenir à l'utilisateur précédent</a>
							</div>
						</div>
					@else
						<div class="col-md-6-">
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
						<p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_1']  }}</p>
					</div>
					<div class="circle">
						<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_1']   }}</p>
					</div>
				</div>

				<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
					<h5>Lyon</h5>
					<div class="circle">
						<p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_2']   }}</p>
					</div>
					<div class="circle">
						<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_2']   }}</p>
					</div>
				</div>

				<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
					<h5> Marseille</h5>
					<div class="circle">
						<p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_3']   }}</p>
					</div>
					<div class="circle">
						<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_3']   }}</p>
					</div>
				</div>

				<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
					<h5> Aubagne</h5>
					<div class="circle">
						<p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_4']   }}</p>
					</div>
					<div class="circle">
						<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_4']   }}</p>
					</div>
				</div>

				<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
					<h5> Varsovie</h5>
					<div class="circle">
						<p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_5']  }}</p>
					</div>
					<div class="circle">
						<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_5']   }}</p>
					</div>
				</div>
				<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
					<h5> Cayenne</h5>
					<div class="circle">
						<p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_6']   }}</p>
					</div>
					<div class="circle">
						<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_6']   }}</p>
					</div>
				</div>
				<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
					<h5> Nice</h5>
					<div class="circle">
						<p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_7']  }}</p>
					</div>
					<div class="circle">
						<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_7']   }}</p>
					</div>
				</div>
				<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
					<h5> Toulouse</h5>
					<div class="circle">
						<p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_8']   }}</p>
					</div>
					<div class="circle">
						<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_8']  }}</p>
					</div>
				</div>
				<div class="col-md-4 col-lg-4 col-sm-6 text-center  ">
					<h5> Bordeaux</h5>
					<div class="circle">
						<p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_9']  }}</p>
					</div>
					<div class="circle">
						<p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_9']  }}</p>
					</div>
				</div>
			</div>

			<div class="row">
				<!--

-->
<!--
				@if(auth()->user()->role=='admin')

				<section class="col-md-6- col-lg-6- col-sm-12- mb-3">
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
				-->
				<div class="col-lg-12 col-md-6- col-lg-6- col-sm-12- mb-3">
					<ul class="nav nav-tabs card-header" id="myTab1" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="offers-tab" data-toggle="tab" href="#offers" role="tab" aria-controls="offers" aria-selected="true" style="">{{__('msg.Price offers to be validated')}} <i class="fas fa-exclamation-triangle text-danger"></i></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="comps-tab" data-toggle="tab" href="#comps" role="tab" aria-controls="comps" aria-selected="false" style="">{{__('msg.Unclosed complaints')}} <i class="fas fa-exclamation-triangle text-danger"></i></a>
						</li>
					</ul>

					<div class="tab-content" style="padding-left:15px;padding-right:15px">
						<div class="tab-pane active" id="offers" role="tabpanel" aria-labelledby="offers-tab">

							<div class="table-container">

								<table id="" class="table table-striped" style="" >
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
											<td>{{$user->lastname ?? ''}} {{$user->name ?? ''}}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>

						</div>

						<div class="tab-pane" id="comps" role="tabpanel" aria-labelledby="comps-tab">
							<div class="table-container" style=" ">
								<table id="" class="table table-striped" style="">
								<thead>
									<tr style="background-color:#2e3e4e;color:white;" id="">
									<th>{{__('msg.Title')}}</th>
									<th>Ouverture</th>
									<th>{{__('msg.Customer')}}</th>
									<th>{{__('msg.Created by')}}</th>
									<th>{{__('msg.Reason')}}</th>
									</tr>
								</thead>
								<tbody>
									@foreach($retours as $retour)
									@php $creator = \App\Models\User::find($retour->user_id); @endphp
									<tr>
									<td><a href="{{route('retours.show',['id'=>$retour->id])}}">{{$retour->Name}}</a></td>
									<td>{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</td>
									<td>{{$retour->Nom_du_compte}}</td>
									<td>{{$creator->name ?? '' }} {{$creator->lastname ?? '' }}</td>
									<td>{{$retour->Motif_retour}}</td>
									</tr>
									@endforeach
								</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>
<!--
				<div class="col-lg-12 col-md-6- col-lg-6- col-sm-12- mb-3">
					<h4 class="text-center">{{__('msg.Price offers to be validated')}} <i class="fas fa-exclamation-triangle text-danger"></i></h4>
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
									<td>{{$user->lastname ?? ''}} {{$user->name ?? ''}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>

				</div>


				<div class="col-lg-12 mt-2">
					<h4 class="text-center">{{__('msg.Unclosed complaints')}}  <i class="fas fa-exclamation-triangle text-danger"></i></h4>
					<div class="table-container" style="margin-top:10px">
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
							<td>{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</td>
							<td>{{$retour->Nom_du_compte}}</td>
							<td>{{$retour->Nom_du_contact}}</td>
							<td>{{$retour->Motif_retour}}</td>
							</tr>
							@endforeach
						</tbody>
						</table>
					</div>
				</div>
			-->
			</div>
		</div>
		<div class="col-lg-7 col-md-12">
			<div class="text-right">
				<a href="{{route('consultations')}}" class="btn btn-primary mb-2" style=" "><i class="fas fa-chalkboard-teacher"></i> Activités des utilisateurs</a>
			</div>
					<h4 class="text-center ">Gestion des rôles</h4>
					<div class="table-container">
						<table class="table table-striped" style="width:90%">
							<thead style="background-color:lightgray;color:white">
								<tr><th>Id</th><th>Nom</th><th>Rôle</th></tr>
							</thead>
							<tbody>
								@foreach($users as $user)
									<tr><td>{{$user->id}}</td><td>{{$user->lastname}} {{$user->name}}</td>
									<td>
									<select class="form-control"  onchange="update_role(this,'{{$user->id}}')">
										<option value="0"></option>
										<option value="1" {{ $user->user_role==1 ? 'selected="selected"' : '' }} >Administration </option><option value="2" {{ $user->user_role==2 ? 'selected="selected"' : '' }}>Direction</option><option value="3" {{ $user->user_role==3 ? 'selected="selected"' : '' }}>Superviseur</option><option value="4" {{ $user->user_role==4 ? 'selected="selected"' : '' }}>Responsable d'Agence</option><option value="5" {{ $user->user_role==5 ? 'selected="selected"' : '' }}>Qualité</option><option value="6" {{ $user->user_role==6 ? 'selected="selected"' : '' }}>ADV</option><option value="7" {{ $user->user_role==7 ? 'selected="selected"' : '' }}>Commercial</option><option value="8" {{ $user->user_role==8 ? 'selected="selected"' : '' }}>AnimCo</option><option value="9" {{ $user->user_role==9 ? 'selected="selected"' : '' }}>Out</option>
									</select>
									</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<h4 class="text-center  mt-5">Réception des lots d'or hautes teneurs par agence</h4>
					<ul class="nav nav-tabs card-header" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="week-tab" data-toggle="tab" href="#week" role="tab" aria-controls="week" aria-selected="true" style="color:#4e73df;width:250px;text-align:center"><i class="fas fa-calendar-week "></i>  Par semaine </a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="month-tab" data-toggle="tab" href="#month" role="tab" aria-controls="month" aria-selected="false" style="color:#4e73df;width:250px;text-align:center"><i class="fas fa-calendar "></i>  Par mois </a>
						</li>

					</ul>


					<div class="tab-content" style="padding-left:15px;padding-right:15px">

						<div class="tab-pane active" id="week" role="tabpanel" aria-labelledby="week-tab">
							<div class="table-container" style="height:420px;max-height:550px;">
								<table class="table table-striped" style="width:90%">
									<thead style="background-color:lightgray;color:white">
										<tr><th>Agence</th><th>S0</th><th>S1</th><th>S2</th><th>S3</th><th>S4</th><th>S5</th><th>S6</th><th>S7</th><th>S8</th><th>S9</th><th>S10</th><th>S11</th></tr>
									</thead>
									@foreach($stats as $s)
									<tr>
										<th>{{$s->agences}}</th><th>{{$s->S0}}</th><th>{{$s->S1}}</th><th>{{$s->S2}}</th><th>{{$s->S3}}</th><th>{{$s->S4}}</th><th>{{$s->S5}}</th><th>{{$s->S6}}</th><th>{{$s->S7}}</th><th>{{$s->S8}}</th><th>{{$s->S9}}</th><th>{{$s->S10}}</th><th>{{$s->S11}}</th>
									</tr>
									@endforeach
								</table>
							</div>
						</div>

						<div class="tab-pane" id="month" role="tabpanel" aria-labelledby="month-tab">
							<div class="table-container" style="height:420px;max-height:550px;">
								<table class="table table-striped" style="width:90%">
									<thead style="background-color:lightgray;color:white">
										<tr><th>Agence</th><th>M</th><th>M1</th><th>M2</th><th>M3</th><th>M4</th></tr>
									</thead>
									@foreach($stats_mois as $s)
									<tr>
										<th>{{$s->agences}}</th><th>{{$s->M}}</th><th>{{$s->M_1}}</th><th>{{$s->M_2}}</th><th>{{$s->M_3}}</th><th>{{$s->M_4}}</th></th>
									</tr>
									@endforeach
								</table>
							</div>
						</div>
					</div>
		</div>
		@endif



	</div>
</div>
 
<div class="terms-popup-overlay" id="termsPopup" style="display: none;">
    <div class="terms-popup-container">
        <div class="terms-content" id="termsContent">
			<h1 class="text-center">Conditions d'utilisation du CRM</h1><br>
            
				<i>Version : 1.0 </i><br>
				<i>Date d'entrée en vigueur : 01 septembre 2024</i><br>
				<h5>Préambule</h5>
				Le système de Gestion de la Relation Client (CRM) de la SAAMP est un outil de travail essentiel destiné à optimiser la gestion de nos prospects, clients, partenaires et activités commerciales associées. Son utilisation est un privilège et implique des responsabilités. Les présentes Conditions Générales d'Utilisation et Politique de Confidentialité (ci-après "la Politique") définissent les règles que tout utilisateur doit respecter. L'accès et l'utilisation du CRM valent acceptation sans réserve de cette Politique.<br>
				<h5>Article 1 : Définitions</h5>
				CRM : Désigne l'outil logiciel et les bases de données associées fournis par SAAMP pour la gestion de la relation client.<br>
				Utilisateur : Toute personne employée ou mandatée par SAAMP autorisée à accéder et utiliser le CRM.<br>
				Données : Toute information enregistrée dans le CRM, incluant, sans s'y limiter, les informations sur les prospects, clients, contacts, entreprises, opportunités, interactions, contrats, ainsi que les données internes de l'entreprise.<br>
				Données Confidentielles : Toute Donnée non publique, incluant notamment : les données personnelles des contacts (conformément au RGPD), les détails commerciaux (tarifs spécifiques, conditions contractuelles), les informations financières (chiffres d'affaires, marges, coûts - même agrégés ou partiels), les stratégies commerciales, les notes internes, et toute information marquée comme confidentielle ou dont la nature est intrinsèquement confidentielle.<br>
				<h5>Article 2 : Accès et Sécurité</h5>
				2.1. L'accès au CRM est nominatif, personnel et strictement réservé aux Utilisateurs autorisés par SAAMP dans le cadre de leurs fonctions.<br>
				2.2. Les identifiants de connexion (nom d'utilisateur et mot de passe) sont strictement personnels et confidentiels. L'Utilisateur est seul responsable de leur conservation et de leur  sécurité. Il est interdit de les communiquer à des tiers, y compris à d'autres collaborateurs. 2.3. L'Utilisateur s'engage à choisir un mot de passe robuste et à le renouveler conformément aux politiques de sécurité de l'entreprise.<br>
				2.4. Toute suspicion d'accès non autorisé à son compte doit être immédiatement signalée au service Informatique et au supérieur hiérarchique.<br>
				2.5. L'Utilisateur s'engage à verrouiller sa session CRM lorsqu'il quitte son poste de travail, même momentanément.<br>
				<h5>Article 3 : Utilisation Autorisée</h5>
				3.1. Le CRM doit être utilisé exclusivement à des fins professionnelles, dans le cadre des missions confiées à l'Utilisateur par SAAMP.<br>
				3.2. Les utilisations autorisées incluent notamment :<br>
				* La saisie et la mise à jour des informations prospects, clients, contacts et entreprises.<br>
				* Le suivi des interactions commerciales et marketing (appels, emails, rendez-vous).<br>
				* La gestion du portefeuille d'opportunités commerciales.<br>
				* La génération de rapports standards autorisés via les fonctionnalités prévues.<br>
				* La collaboration interne sur les dossiers clients/prospects.<br>
				3.3. L'utilisation doit être loyale, éthique et respectueuse des lois et règlements en vigueur, notamment le Règlement Général sur la Protection des Données (RGPD).<br>
				<h5>Article 4 : Utilisation Interdite</h5>
				4.1. Il est strictement interdit d'utiliser le CRM à des fins personnelles, illégales, frauduleuses, diffamatoires, obscènes ou contraires à l'éthique.<br>
				4.2. Il est interdit de :<br>
				* Tenter d'accéder à des Données ou fonctionnalités pour lesquelles l'Utilisateur n'a pas reçu d'autorisation explicite.<br>
				* Introduire volontairement des virus, malwares ou tout autre code nuisible.<br>
				* Perturber ou tenter de perturber le bon fonctionnement du CRM.<br>
				* Stocker des informations sans rapport avec l'activité professionnelle légitime.<br>
				* Usurper l'identité d'un autre Utilisateur.<br>
				* Procéder à une extraction de données en dehors des fonctionnalités d'export standards et explicitement autorisées au sein du CRM pour les besoins directs de sa fonction.<br>
				Toute extraction massive ou non directement liée à une tâche opérationnelle immédiate requiert une autorisation préalable de la hiérarchie.<br>
				<h5>Article 5 : Qualité et Intégrité des Données</h5>
				5.1. L'Utilisateur est responsable de l'exactitude, de la complétude et de la mise à jour des Données qu'il saisit dans le CRM.<br>
				5.2. Les informations doivent être enregistrées de manière professionnelle, objective et factuelle.<br>
				5.3. La saisie des informations doit être effectuée en temps opportun pour garantir la fiabilité des Données pour l'ensemble des Utilisateurs et pour la prise de décision.<br>
				<h5>Article 6 : Confidentialité des Données</h5>
				6.1. Toutes les Données contenues dans le CRM sont considérées comme strictement confidentielles et constituent la propriété exclusive de la SAAMP. <br>
				6.2. L'Utilisateur s'engage à ne consulter que les Données nécessaires à l'accomplissement de ses missions (principe du "besoin d'en connaître").<br>
				6.3. Il est formellement interdit de communiquer, de divulguer ou de partager des Données Confidentielles (telles que définies à l'Article 1), que ce soit verbalement, par écrit, ou par voie électronique :<br>
				* À des tiers externes à l'entreprise (sauf autorisation expresse de la hiérarchie et dans le respect des accords de confidentialité éventuels).<br>
				* À d'autres collaborateurs internes qui n'ont pas un besoin légitime d'en connaître pour leurs propres fonctions.<br>
				6.4. Cette interdiction de communication s'applique de manière particulièrement stricte aux données financières sensibles, notamment les chiffres d'affaires et les marges, conformément aux notes internes spécifiques déjà diffusées. Leur communication est restreinte aux canaux de reporting officiels.<br>
				6.5. L'Utilisateur s'engage à respecter la confidentialité des Données même après la cessation de son contrat de travail.<br>
				<h5>Article 7 : Extraction et Partage de Données</h5>
				7.1. Réitération : L'extraction de Données est limitée aux fonctionnalités standards du CRM autorisées pour le profil de l'Utilisateur et uniquement pour des besoins professionnels légitimes et directs. Toute autre forme d'extraction est prohibée sans autorisation formelle.<br>
				7.2. Le partage de Données extraites (même autorisées) doit respecter scrupuleusement les règles de confidentialité énoncées à l'Article 6.<br>
				<h5>Article 8 : Propriété Intellectuelle</h5>
				Toutes les Données saisies, générées ou stockées dans le CRM, ainsi que la structure de la base de données et les développements spécifiques éventuels, sont et demeurent la propriété intellectuelle exclusive de la SAAMP.<br>
				<h5>Article 9 : Surveillance et Audit</h5>
				La SAAMP se réserve le droit de surveiller l'utilisation du CRM à des fins de sécurité, de maintenance, d'amélioration des performances et de vérification du respect de la présente Politique, dans le respect de la législation applicable en France et dans l'Union Européenne concernant la vie privée des employés.<br>
				<h5>Article 10 : Non-Respect de la Politique et Sanctions</h5>
				Tout manquement aux dispositions de la présente Politique constitue une faute et expose l'Utilisateur à des mesures disciplinaires pouvant aller jusqu'au licenciement pour faute grave, sans préjudice d'éventuelles poursuites judiciaires civiles ou pénales si le manquement cause un préjudice à l'entreprise ou contrevient à la loi.<br>
				<h5>Article 11 : Modifications de la Politique</h5>
				La SAAMP se réserve le droit de modifier la présente Politique à tout moment. Les Utilisateurs seront informés des modifications substantielles par les canaux de communication internes habituels. La poursuite de l'utilisation du CRM après notification des modifications vaut acceptation de la nouvelle Politique.<br>
				<h5>Article 12 : Droit Applicable</h5>
				La présente Politique est régie par le droit français.<br>

        </div>
        
        <button id="acceptTermsButton" class="btn btn-primary float-right" disabled>
            J'accepte les conditions
        </button>
    </div>
</div>

<style>
    .terms-popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.8);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .terms-popup-container {
        background: white;
        width: 80%;
        max-width: 800px;
        max-height: 80vh;
        padding: 20px;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .terms-content {
        overflow-y: auto;
        margin-bottom: 20px;
        flex-grow: 1;
    }
    
    #acceptTermsButton:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    body.terms-popup-open {
        overflow: hidden;
    }
	.terms-content h5{
		margin-top:10px;
	}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si la popup doit être affichée
	let _token = $('input[name="_token"]').val();

	$.ajax({
        url: "{{ route('terms.check') }}",
        method: "GET",
        data: {  _token: _token},
        success: function (data) {
			 
			if (data==0) {
				showTermsPopup();
            	}
			}
	});
 
    function showTermsPopup() {
        const popup = document.getElementById('termsPopup');
        const content = document.getElementById('termsContent');
        const acceptButton = document.getElementById('acceptTermsButton');
        
        // Afficher la popup et bloquer le scroll
        popup.style.display = 'flex';
        document.body.classList.add('terms-popup-open');

        // Vérifier le scroll
        content.addEventListener('scroll', function() {
         /*   const isBottom = content.scrollHeight - content.scrollTop <= content.clientHeight + 5;
            acceptButton.disabled = !isBottom;
*/
			const isBottom = termsContent.scrollHeight - termsContent.scrollTop === termsContent.clientHeight;
            
            if (isBottom) {
                acceptButton.disabled = false;
            }
        });

        // Gérer l'acceptation
        acceptButton.addEventListener('click', function() {

			$.ajax({
                url: "{{ route('terms.accept') }}",
                method: "POST",
                data: {  _token: _token},
                success: function (data) {
					 
					if (data==1) {
                    popup.style.display = 'none';
                    document.body.classList.remove('terms-popup-open');
                	}
				}
			});
        });
    }
});
</script>


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
		/*
		setTimeout(function() {
			$('#maintenance').modal('show');
		}, 5000); // 5000 milliseconds = 5 seconds
*/
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


	function update_role(select,user_id) {
        var user_role = $(select).val();
        var _token = $('input[name="_token"]').val();

        $.ajax({
            url: "{{ route('update_role') }}",
            method: "POST",
            data: {
                user_id: user_id,
                user_role: user_role,
                _token: _token
            },
            success: function(data) {
				$(select).hide('slow');
				$(select).show();
            }
        });
    }
  </script>
@endsection
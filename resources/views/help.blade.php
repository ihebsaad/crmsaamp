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
	}
</style>


<div class="row">

	<!-- Content Column -->
	<div class="col-lg-12 mb-4">

		<!-- Project Card Example -->
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Guide d'utilisation</h6>
			</div>
			<div class="card-body">
				<h2>Sommaire</h2>
				<h5>1. Pour démarrer</h5>

				<ul class="myist">
					<li>Créer un prospect</li>
				</ul>
				<h5>2. Client</h5>

				<ul class="myist">
					<li>Rechercher un client</li>
					<li>Fiche client</li>
					<li>Créer un rendez vous</li>
					<li>Créer une prise de contact</li>
					<li>Créer une offre</li>
					<li>Visualiser les informations financières</li>
				</ul>
			</div>
		</div>



	</div>

</div>

@endsection
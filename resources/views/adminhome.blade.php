
@extends('layouts.admin')

@section('content')

<?php

?>

<div class="" style="padding-left:5%;padding-right:5%;padding-top:2%">
	<div class="row">

		<div class="col-xl-6 col-md-6 mb-6"  style="margin-bottom:25px" >
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div style="font-size:22px;margin-bottom:25px" class="text-xs font-weight-bold text-primary text-uppercase mb-1">Client</div>
							<div class=" "><a href="{{route('home')}}">Mes statistiques</a> </div>
							<div class=" "><a href="{{route('search')}}">Recherche</a> </div>
							<div class=" "><a   href="{{route('profile')}}">Mon profil</a> </div>
						</div>
						<div class="col-auto">
							<i class="fas fa-users fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-6 col-md-6 mb-6"  style="margin-bottom:25px" >
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div style="font-size:22px;margin-bottom:25px" class="text-xs font-weight-bold text-success text-uppercase mb-1">Tâches</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-tasks fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-6 col-md-6 mb-6"  style="margin-bottom:25px" >
			<div class="card border-left-danger shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div style="font-size:22px;margin-bottom:25px" class="text-xs font-weight-bold text-danger text-uppercase mb-1">Offres commerciales</div>

						</div>
						<div class="col-auto">
							<i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-xl-6 col-md-6 mb-6"  style="margin-bottom:25px" >
			<div class="card border-left-warning shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div style="font-size:22px;margin-bottom:25px" class="text-xs font-weight-bold text-warning text-uppercase mb-1">Retour client</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-comments fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

@endsection

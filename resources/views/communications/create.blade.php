@extends('layouts.back')

@section('content')

<?php

?>

<style>


</style>
<div class="row">

	<div class="col-lg-12 col-sm-12 mb-4">

		<!-- Project Card Example -->
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Créer une nouvelle communication</h6>
			</div>

			<div class="card-body" style="min-height:500px">

				<form action="{{ route('communications.store') }}" method="POST" enctype="multipart/form-data">
					@csrf

					<div class="row">
						<!-- Objet -->
						<div class="col-md-6 mb-3">
							<label for="objet" class="form-label">Objet</label>
							<input type="text" class="form-control" id="objet" name="objet" value="{{ old('objet') }}" required>
							@error('objet') <span class="text-danger">{{ $message }}</span> @enderror
						</div>

						<!-- Type -->
						<div class="col-md-6 mb-3">
							<label for="type" class="form-label">Type</label>
							<select class="form-control" id="type" name="type" required>
								<option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Client</option>
								<option value="2" {{ old('type') == 2 ? 'selected' : '' }}>Prospect</option>
								<option value="2" {{ old('type') == 3 ? 'selected' : '' }}>Clients & Prospect</option>
							</select>
							@error('type') <span class="text-danger">{{ $message }}</span> @enderror
						</div>
						<!-- Corps du message -->
						<div class="col-md-6 mb-3">
							<label for="corps_message" class="form-label">Corps du message</label>
							<textarea class="form-control" id="corps_message" name="corps_message" rows="5" required>{{ old('corps_message') }}</textarea>
							@error('corps_message') <span class="text-danger">{{ $message }}</span> @enderror
						</div>

						<!-- Fichier -->
						<div class="col-md-6 mb-3">
							<label for="fichier" class="form-label">Fichier (optionnel)</label>
							<input type="file" class="form-control" id="fichier" name="fichier">
							@error('fichier') <span class="text-danger">{{ $message }}</span> @enderror
						</div>

						<div class="col-md-12 mb-3">
							<!-- Button to trigger modal -->
						</div>

						<!-- Destinataires -->
						<div class="col-md-12 mb-3">
							<label for="destinataires" class="form-label">Destinataires</label>
							<textarea class="form-control" id="destinataires" name="destinataires" rows="3" required readonly>{{ old('destinataires') }}</textarea>
							@error('destinataires') <span class="text-danger">{{ $message }}</span> @enderror
						</div>

						<div class="col-md-6 ">
						</div>
						<div class="col-md-3 ">
							<button type="button" class="btn btn-warning float-right mb-3" id="reset-destinataires"> <i class="fa fa-redo"></i> Vider</button>
						</div>
						<div class="col-md-3 mb-3">
							<button type="button" class="btn btn-secondary float-right mb-3" href="#" data-toggle="modal" data-target="#searchClientsModal"><i class="fa fa-search"></i> Chercher des clients</button>
						</div>

						<!-- Bouton de soumission -->
						<div class="col-md-12 mb-3 text-right">
							<button type="submit" class="btn btn-primary">Créer</button>
						</div>

					</div>

					<!-- Utilisateur -->
					<input class="text-right" type="hidden" name="par" value="{{ auth()->id() }}">


				</form>

			</div>

		</div>

	</div>




	<!-- Modal -->
	<div class="modal fade" id="searchClientsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="searchClientsModalLabel">Rechercher des clients</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- Filters -->
					<form id="searchClientsForm">
						<div class="row">
							<div class="col-md-4">
								<input type="text" class="form-control" name="Nom" placeholder="Nom">
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control" name="ville" placeholder="Ville">
							</div>
							<div class="col-md-4">
								<select class="form-control" name="type">
									<option value="0">Tous</option>
									<option value="1">Prospects</option>
									<option value="2">Clients</option>
								</select>
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-md-12 text-end">
								<button type="button" class="btn btn-primary" id="searchClientsBtn">Rechercher</button>
							</div>
						</div>
					</form>

					<!-- Results -->
					<div class="mt-4">
						<div class="table-container">
							<table class="table table-striped">
								<thead>
									<tr>
										<th><input type="checkbox" id="selectAll"></th>
										<th>ID</th>
										<th>Nom</th>
										<th>{{__('msg.City')}}</th>
										<th>Client ID</th>
										<th>Type</th>
										<th>Agence</th>
									</tr>
								</thead>
								<tbody id="clientsResults">
									<tr>
										<td colspan="7" class="text-center">{{__('msg.No results')}}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">{{__('msg.Close')}}</button>
					<button type="button" class="btn btn-primary" id="addSelectedClients">Ajouter les destinataires</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {


			document.getElementById('reset-destinataires').addEventListener('click', function() {
				// Vider le champ destinataires
				document.getElementById('destinataires').value = '';

				// Réinitialiser toutes les cases à cocher
				document.querySelectorAll('#clients-table input[type="checkbox"]').forEach(checkbox => {
					checkbox.checked = false;
				});

				// Réinitialiser la liste des clients sélectionnés
				selectedClients = [];
			});

			// Search clients
			$('#searchClientsBtn').click(function() {
				const formData = $('#searchClientsForm').serialize();
				let clientTypes = {
					'1': 'Prospect',
					'2': 'Client',
					'3': 'Fermé',
					'4': 'Inactif',
					'5': 'Particulier'
				};
				let agences = {
					<?php
					foreach($agences as $agence){
					echo '"'.$agence->agence_ident.'": "'.$agence->agence_lib.'",';
					}
					?>
				};
				$.ajax({
					url: "{{ route('search.ajax') }}",
					method: "GET",
					data: formData,
					success: function(data) {
						let rows = '';

						if (data.length > 0) {
							data.forEach(client => {
								rows += `
                            <tr>
                                <td><input type="checkbox" class="select-client" data-id="${client.id}" data-name="${client.Nom}"></td>
                                <td>${client.id}</td>
                                <td>${client.Nom}</td>
                                <td>${client.ville}</td>
                                <td>${client.cl_ident ?? 'N/A'}</td>
                                <td>${clientTypes[client.etat_id]}</td>
                                <td>${agences[client.agence_ident] ?? 'N/A'}</td>
                            </tr>
                        `;
							});
						} else {
							rows = '<tr><td colspan="7" class="text-center">Aucun résultat trouvé</td></tr>';
						}

						$('#clientsResults').html(rows);
					}
				});
			});

			// Select all clients
			$('#selectAll').click(function() {
				$('.select-client').prop('checked', this.checked);
			});

			// Add selected clients to the destinatary field
			$('#addSelectedClients').click(function() {
				const selectedClients = [];

				$('.select-client:checked').each(function() {
					selectedClients.push({
						id: $(this).data('id'),
						name: $(this).data('name'),
					});
				});

				if (selectedClients.length > 0) {
					const destinataryField = $('#destinataires'); // Adjust selector as needed
					let destinataryData = destinataryField.val() ? JSON.parse(destinataireField.val()) : [];

					destinataryData = destinataryData.concat(selectedClients);
					destinataryField.val(JSON.stringify(destinataryData));
				}

				$('#searchClientsModal').modal('hide');
			});
		});
	</script>
	@endsection
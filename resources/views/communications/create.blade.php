@extends('layouts.back')

@section('content')

<?php
?>
<link rel="stylesheet" href="{{ asset('sbadmin/summernote/summernote-bs4.min.css')}}">

<style>
	#template_body {
		display: non !important
	}
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
					<!-- Bouton pour créer un template -->
					<div class="col-md-6 mb-3 text-right">
						<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#templateModal">
							<i class="fa fa-plus"></i> Ajouter un template
						</button>
					</div>
					<div class="row">
						<!-- Sélection d'un template -->
						<div class="col-md-6 mb-3">
							<label for="template_id" class="form-label">Template</label>
							<select class="form-control" id="template_id" name="template_id">
								<option value="" selected="selected">Sans template</option>
								@foreach ($templates as $template)
								<option value="{{ $template->id }}">
									{{ $template->name }}
								</option>
								@endforeach
							</select>
							@error('template_id') <span class="text-danger">{{ $message }}</span> @enderror
						</div>
						<!-- Type -->
						<div class="col-md-6 mb-3">
							<label for="type" class="form-label">Type</label>
							<select class="form-control" id="type" name="type" required>
								<option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Client</option>
								<option value="2" {{ old('type') == 2 ? 'selected' : '' }}>Prospect</option>
								<option value="3" {{ old('type') == 3 ? 'selected' : '' }}>Clients & Prospect</option>
							</select>
							@error('type') <span class="text-danger">{{ $message }}</span> @enderror
						</div>
						<!-- Objet -->
						<div class="col-md-6 mb-3 div-objet">
							<label for="objet" class="form-label">Objet</label>
							<input type="text" class="form-control" id="objet" name="objet" value="{{ old('objet') }}" >
							@error('objet') <span class="text-danger">{{ $message }}</span> @enderror
						</div>

						<!-- Fichier -->
						<div class="col-md-6 mb-3">
							<label for="files" class="form-label">{{__('msg.File(s)')}} (optionnel)</label>
							<input type="file" class="form-control" id="files"   name="fichiers[]"  multiple  />
							@error('files') <span class="text-danger">{{ $message }}</span> @enderror
						</div>

						<!-- Corps du message -->
						<div class="col-md-6 mb-3 div-corps">
							<label for="corps_message" class="form-label">Corps du message</label>
							<textarea class="summernote" id="corps_message" name="corps_message" rows="5" >{{ old('corps_message') }}</textarea>
							@error('corps_message') <span class="text-danger">{{ $message }}</span> @enderror
						</div>

						<div class="col-md-12 mb-3">
							<!-- Button to trigger modal -->
						</div>

						<!-- Destinataires -->
						<div class="col-md-12 mb-3">
							<label for="destinataires" class="form-label">Destinataires</label>
							<textarea  style="display:none!important" class="form-control" id="destinataires" name="destinataires" rows="3" required readonly>{{ old('destinataires') }}</textarea>
							@error('destinataires') <span class="text-danger">{{ $message }}</span> @enderror
						</div>

						<div class="col-md-12 mb-3">
							<textarea id="clients" name="clients" class="form-control" placeholder="Liste des noms de clients" readonly></textarea>
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
							<div class="col-md-3">
								<input type="text" class="form-control" name="Nom" placeholder="Nom">
							</div>
							<div class="col-md-3">
								<input type="text" class="form-control" name="ville" placeholder="Ville">
							</div>
							<div class="col-md-3">
								<select class="form-control" name="type">
									<option value="0">Tous</option>
									<option value="1">Prospects</option>
									<option value="2">Clients</option>
								</select>
							</div>
							<div class="col-md-3">
								<select class="form-control" name="agence">
									<option>Choisir une agence</option>
									@foreach ($agences as $agence)
										<option value="{{$agence->agence_ident}}">{{$agence->agence_lib}}</option>
									@endforeach
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


	<div class="modal fade" id="templateModal" tabindex="-1" role="dialog" aria-labelledby="templateModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Créer un Template</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="templateForm">
						@csrf
						<div class="form-group">
							<label for="template_name">Nom du Template</label>
							<input type="text" class="form-control" id="template_name" name="name" required>
						</div>
						<div class="form-group">
							<label for="template_subject">Objet</label>
							<input type="text" class="form-control" id="template_subject" name="subject" required>
						</div>
						<div class="form-group">
							<label for="template_body">Contenu</label>
							<textarea class="summernote" id="template_body" name="body" required></textarea>
						</div>
						<button type="button" class="btn btn-primary" id="saveTemplate">Enregistrer</button>
					</form>
				</div>
			</div>
		</div>
	</div>


<!--
	<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#editor'
    });
</script>-->

	<script src="{{ asset('sbadmin/summernote/summernote-bs4.min.js') }}"></script>

	<script>

		$('#template_id').change(function() {
			let template_id = $('#template_id option:selected').val() ;

			if(parseInt(template_id)>0){
				$('.div-objet').hide('slow');
				$('.div-corps').hide('slow');
				//corps_message prop not required
				//objet  prop not required
			}else{
				$('.div-objet').show('slow');
				$('.div-corps').show('slow');
				//corps_message prop not required

			}
		});


		$(document).ready(function() {

			$('.summernote').summernote({
				height: 200
			});

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
/*
			document.getElementById('destinataires').addEventListener('input', function () {
				const destinatairesTextarea = this.value.trim();
				const clientsTextarea = document.getElementById('clients');

				try {
					// Parse le contenu de destinataires
					const destinataires = JSON.parse(destinatairesTextarea);

					// Vérifie si c'est un tableau d'objets avec des noms
					if (Array.isArray(destinataires)) {
						const clientNames = destinataires.map(dest => dest.name).join(', ');
						clientsTextarea.value = clientNames;
					} else {
						clientsTextarea.value = '';
					}
				} catch (error) {
					// En cas d'erreur, vide le champ clients
					clientsTextarea.value = '';
				}
			});*/

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
					foreach ($agences as $agence) {
						echo '"' . $agence->agence_ident . '": "' . $agence->agence_lib . '",';
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
                                <td><input type="checkbox" class="select-client" data-id="${client.id}" data-name="${client.Nom}"   ></td>
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
				const Clients = [];

				$('.select-client:checked').each(function() {
					selectedClients.push({
						id: $(this).data('id'),
						name: $(this).data('name').trim(),
					});
					Clients.push(
						$(this).data('name').trim(),
					);
				});

				if (selectedClients.length > 0) {
					const destinataryField = $('#destinataires'); // Adjust selector as needed
					const clientsField = $('#clients'); // Adjust selector as needed
					let destinataryData = destinataryField.val() ? JSON.parse(destinataryField.val()) : [];
					let clientsData = clientsField.val() ? JSON.parse(clientsField.val()) : [];

					destinataryData = destinataryData.concat(selectedClients);
					destinataryField.val(JSON.stringify(destinataryData));
					clientsData= clientsData.concat(Clients);
					clientsField.val(JSON.stringify(clientsData));
				}

				$('#searchClientsModal').modal('hide');
			});


			$('#saveTemplate').on('click', function() {
				let formData = new FormData($('#templateForm')[0]);
				$.ajax({
					url: '/add_template',
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					success: function(response) {
						$('#templateModal').modal('hide');
						alert('Template créé avec succès');
						location.reload(); // Recharge les templates disponibles
					},
					error: function(xhr) {
						alert('Erreur lors de la création du template.');
					}
				});
			});


		});
	</script>
	@endsection
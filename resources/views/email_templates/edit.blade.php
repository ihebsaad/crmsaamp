@extends('layouts.back')

<link rel="stylesheet" href="{{ asset('sbadmin/summernote/summernote-bs4.min.css')}}">
<style>
	#template_body , #body {
		display: none !important;
	}
</style>
@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ isset($emailTemplate) ? 'Mettre à jour' : 'Créer' }} template email</h6>
    </div>
    <div class="card-body">

        <form action="{{ isset($emailTemplate) ? route('email-templates.update', $emailTemplate) : route('templates.add') }}" method="POST">
            @csrf
            @isset($emailTemplate)
            @method('PUT')
            @endisset
            <input type="hidden" name="user" value="{{auth()->id()}}"/>

            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $emailTemplate->name ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="subject" class="form-label">Sujet</label>
                <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject', $emailTemplate->subject ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="body" class="form-label">Corps</label>
                <textarea name="body" id="body" class="form-control summernote" rows="4" required>{{ old('body', $emailTemplate->body ?? '') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary float-right">{{ isset($emailTemplate) ? 'Mettre à jour' : 'Créer' }}</button>
        </form>
    </div>
</div>


<script src="{{ asset('sbadmin/summernote/summernote-bs4.min.js') }}"></script>
<script>

	$(document).ready(function() {

        $('.summernote').summernote({
				height: 200,
				callbacks: {
					onImageUpload: function(files) {
						uploadImages(files, this);
					}
				}
			});

            function uploadImages(files, editor) {
				var _token = $('input[name="_token"]').val();
				let data = new FormData();
				data.append('image', files[0]); // Ajouter la première image
				data.append('_token', _token);

				// Envoyer l'image au serveur via AJAX
				$.ajax({
					url: '/upload-image', // Route Laravel pour gérer le téléchargement
					method: 'POST',
					data: data,
					contentType: false,
					processData: false,
					success: function(response) {
						// Insérer l'URL de l'image dans le contenu de Summernote
						if (response.url) {
							$(editor).summernote('insertImage', response.url);
						}
					},
					error: function() {
						alert('Erreur lors du téléchargement de l\'image.');
					}
				});
			}
	});


</script>
@endsection
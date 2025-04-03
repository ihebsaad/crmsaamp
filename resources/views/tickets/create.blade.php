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
                <h6 class="m-0 font-weight-bold text-primary">{{ __('msg.Add a support ticket') }}</h6>
            </div>

            <div class="card-body" style="min-height:500px">

				<form method="POST" action="{{ route('tickets.store') }}"   enctype="multipart/form-data">
					@csrf
 					<!-- Sujet du ticket -->
					<div class="form-group row">
						<label for="subject" class="col-md-3 col-form-label">{{ __('msg.Subject') }}*:</label>
						<div class="col-md-9">
							<input type="text" id="subject" class="form-control" name="subject" value="{{ old('subject') }}" required>
						</div>
					</div>

					<!-- Description -->
					<div class="form-group row">
						<label for="description" class="col-md-3 col-form-label">{{ __('msg.Description') }}*:</label>
						<div class="col-md-9">
							<textarea id="description" class="form-control" name="description" rows="4" required>{{ old('description') }}</textarea>
						</div>
					</div>

					<!-- Catégorie -->
					<div class="form-group row">
						<label for="category" class="col-md-3 col-form-label">{{ __('msg.Category') }}*:</label>
						<div class="col-md-9">
							<select id="category" class="form-control" name="category" required>
								<option value="">{{ __('msg.Category') }}</option>
								<option value="question">{{ __('msg.Question') }}</option>
								<option value="error">{{ __('msg.Error or Problem') }}</option>
								<option value="suggestion">{{ __('msg.Suggestion') }}</option>
								<option value="other">{{ __('msg.Other') }}</option>
							</select>
						</div>
					</div>

					<!-- Fichiers attachés -->
					<div class="form-group row">
						<label for="files" class="col-md-3 col-form-label">{{ __('msg.Attach Files') }}:</label>
						<div class="col-md-9">
							<input type="file" id="files" class="form-control-file" name="files[]" multiple accept="application/pdf, image/*">
							<small class="form-text text-muted">{{ __('msg.Allowed file types: PDF, images') }}</small>
						</div>
					</div>

					<!-- Bouton d'envoi -->
					<div class="form-group row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary">{{ __('msg.Submit Ticket') }}</button>
						</div>
					</div>

				</form>

            </div>
        </div>

    </div>

</div>


@endsection
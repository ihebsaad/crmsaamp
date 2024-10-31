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
				<h6 class="m-0 font-weight-bold text-primary">{{ __('msg.Edit') }}</h6>
			</div>

			<div class="card-body" style="min-height:500px">

				<form action="{{ route('tickets.update', $ticket->id) }}" method="post">
					@csrf
					@method('PUT')

					<!-- Sujet du ticket -->
					<div class="form-group row">
						<label for="subject" class="col-md-3 col-form-label">{{ __('msg.Subject') }}:</label>
						<div class="col-md-9">
							<input type="text" id="subject" class="form-control" name="subject" value="{{ old('subject', $ticket->subject) }}" required>
						</div>
					</div>

					<!-- Description -->
					<div class="form-group row">
						<label for="description" class="col-md-3 col-form-label">{{ __('msg.Description') }}:</label>
						<div class="col-md-9">
							<textarea id="description" class="form-control" name="description" rows="4" required>{{ old('description', $ticket->description) }}</textarea>
						</div>
					</div>

					<!-- Catégorie -->
					<div class="form-group row">
						<label for="category" class="col-md-3 col-form-label">{{ __('msg.Category') }}:</label>
						<div class="col-md-9">
							<select id="category" class="form-control" name="category" required>
								<option value="question" {{ $ticket->category == 'question' ? 'selected' : '' }}>{{ __('msg.Question') }}</option>
								<option value="error" {{ $ticket->category == 'error' ? 'selected' : '' }}>{{ __('msg.Error or Problem') }}</option>
								<option value="suggestion" {{ $ticket->category == 'suggestion' ? 'selected' : '' }}>{{ __('msg.Suggestion') }}</option>
								<option value="other" {{ $ticket->category == 'other' ? 'selected' : '' }}>{{ __('msg.Other') }}</option>
							</select>
						</div>
					</div>

					<!-- Statut -->
					<div class="form-group row">
						<label for="status" class="col-md-3 col-form-label">{{ __('msg.Status') }}:</label>
						<div class="col-md-9">
							<select id="status" class="form-control" name="status" required>
								<option value="Opened" {{ $ticket->status == 'Opened' ? 'selected' : '' }}>{{ __('msg.Opened') }}</option>
								<option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>{{ __('msg.In Progress') }}</option>
								<option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>{{ __('msg.Resolved') }}</option>
							</select>
						</div>
					</div>

					<!-- Bouton de soumission -->
					<div class="form-group row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary">{{ __('msg.Update Ticket') }}</button>
						</div>
					</div>
				</form>
			</div>
		</div>

	</div>

</div>


@endsection
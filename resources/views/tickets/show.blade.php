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
				<h6 class="m-0 font-weight-bold text-primary">{{ __('msg.Ticket Details') }}</h6>
			</div>

			<div class="card-body" style="min-height:500px">


				<div class="card mb-4">
					<div class="card-header">{{ __('msg.Ticket Information') }} {{$ticket->id}}</div>
					<div class="card-body">
						@php $user= \App\Models\User::find($ticket->user_id); @endphp
						<p><strong>{{ __('msg.By') }}:</strong> {{ $user->name }} {{ $user->lastname }}</p>
						<p><strong>{{ __('msg.Subject') }}:</strong> {{ $ticket->subject }}</p>
						<p><strong>{{ __('msg.Category') }}:</strong> {{ ucfirst($ticket->category) }}</p>
						<p><strong>{{ __('msg.Status') }}:</strong>
							<span class="badge {{ $ticket->status == 'Opened' ? 'badge-warning' : ($ticket->status == 'In Progress' ? 'badge-primary' : 'badge-success') }}">
								{{ $ticket->status }}
							</span>
						</p>
						<p><strong>{{ __('msg.Description') }}:</strong> {{ $ticket->description }}</p>
					</div>
				</div>

				@if($ticket->files!= null)
					<div class="card mb-4 pb-2 pt-2 pl-2 pr-2">
						@php $fileNames = unserialize($ticket->files); @endphp
						<div class="">
							<label for="Description">{{__('msg.File(s)')}}:</label><br>
							<table style="border:none">

								@foreach ($fileNames as $fichier)
								<tr style="border:none">
									<td><label><b class="black mr-2">{{$fichier}}</b></label></td>
									<td><a href="https://crm.mysaamp.com/tickets/{{$fichier}}" target="_blank"><img class="view mr-2" title="Visualiser" width="30" src="{{ URL::asset('img/view.png')}}"></a></td>
									<td><a href="https://crm.mysaamp.com/tickets/{{$fichier}}" download><img class="download mr-2" title="Télecharger" width="30" src="{{ URL::asset('img/download.png')}}"></a></td>
								</tr>
								@endforeach

							</table>
						</div>

					</div>
				@endif

				<h3 class="mb-4">{{ __('msg.Comments') }}</h3>

				<div class="list-group mb-4">
					@foreach($ticket->comments as $comment)
					<div class="list-group-item">
						<p><strong>{{ $comment->user->name }}:</strong> {{ $comment->comment }}</p>
						<small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
					</div>
					@endforeach
				</div>

				<form action="{{ route('tickets.comments.store', $ticket->id) }}" method="post">
					@csrf
					<div class="form-group">
						<label for="comment">{{ __('msg.Add Comment') }}:</label>
						<textarea id="comment" class="form-control" name="comment" rows="3" required></textarea>
					</div>
					<button type="submit" class="btn btn-primary">{{ __('msg.Submit') }}</button>
				</form>

			</div>
		</div>

	</div>

</div>


@endsection
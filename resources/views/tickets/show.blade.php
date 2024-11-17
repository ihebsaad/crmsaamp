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

				@if(count($fichiers)>0)
				<div class="card mb-4 pb-2 pt-2 pl-2 pr-2">
					<label for="Description">{{ __('msg.File(s)') }}:</label><br>
					<table style="border:none;width:100%">
						@foreach ($fichiers as $file)
						<tr>
							<td style="border:none;"><label><b class="black mr-2">{{ $file->name }}</b></label></td>
							<td style="border:none;"><a href="{{ url('/fichiers/tickets/' . $file->name) }}" target="_blank"><img class="view mr-2" title="Visualiser" width="30" src="{{ URL::asset('img/view.png') }}"></a></td>
							<td style="border:none;"><a href="{{ url('/fichiers/tickets/' . $file->name) }}" download><img class="download mr-2" title="Télécharger" width="30" src="{{ URL::asset('img/download.png') }}"></a></td>
							<td style="border:none;">
							<td>
								@if(auth()->user()->id == $ticket->user_id)
								<a title="{{__('msg.Delete')}}" onclick="deleteFile('{{ $file->id }}')" href="javascript:void(0);" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
									<span class="fa fa-fw fa-trash-alt"></span>
								</a>
								@endif
							</td>
							</td>
						</tr>
						@endforeach
					</table>
				</div>
				@endif



				<h3 class="mb-4">{{ __('msg.Comments') }}</h3>

				<div class="list-group mb-4">
					@foreach($ticket->comments as $comment)
					<div class="list-group-item">
						<p><strong>{{ $comment->user->name }}:</strong> {{ $comment->comment }}</p>
						<small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
						@php  		$files=\App\Models\File::where('parent','comments')->where('parent_id',$comment->id)->get();  @endphp
						<div class="row pt-1 pb-1">
							<div class="col-md-4">
								@if(count($files)>0)
								<label for="Description">{{ __('msg.File(s)') }}:</label><br>
								<table style="border:none;width:100%">
									@foreach ($files as $file)
									<tr>
										<td style="border:none;"><label><b class="black mr-2">{{ $file->name }}</b></label></td>
										<td style="border:none;"><a href="{{ url('/fichiers/comments/' . $file->name) }}" target="_blank"><img class="view mr-2" title="Visualiser" width="30" src="{{ URL::asset('img/view.png') }}"></a></td>
										<td style="border:none;"><a href="{{ url('/fichiers/comments/' . $file->name) }}" download><img class="download mr-2" title="Télécharger" width="30" src="{{ URL::asset('img/download.png') }}"></a></td>
										<td style="display:none;">
											@if(auth()->user()->id == $comment->user->id)
											<a title="{{__('msg.Delete')}}" onclick="deleteFile('{{ $file->id }}')" href="javascript:void(0);" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
												<span class="fa fa-fw fa-trash-alt"></span>
											</a>
											@endif
										</td>
									</tr>
									@endforeach
								</table>
								@endif
							</div>
						</div>

					</div>
					@endforeach
				</div>

				<form action="{{ route('tickets.comments.store', $ticket->id) }}" method="post" enctype="multipart/form-data">
					@csrf
					<div class="row pt-1 pb-1">
						<div class="col-md-12">
							<div class="form-group">
								<label for="comment">{{ __('msg.Add Comment') }}:</label>
								<textarea id="comment" class="form-control" name="comment" rows="3" required></textarea>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="Nom_offre">{{__('msg.File(s)')}}:</label>
								<input type="file" id="fichier" class="form-control" name="files[]" multiple  accept="application/pdf" /><br><br>
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary">{{ __('msg.Submit') }}</button>
				</form>

			</div>
		</div>

	</div>

</div>

<script>
	function deleteFile(fileId) {
		if (confirm('Êtes-vous sûrs ?')) {
			fetch(`{{ url('/files') }}/${fileId}`, {
					method: 'DELETE',
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}'
					}
				})
				.then(response => {
					if (response.ok) {
						location.reload(); // Reload the page to reflect changes
					} else {
						alert("Failed to delete file.");
					}
				})
				.catch(error => {
					console.error("Error:", error);
					alert("An error occurred while deleting the file.");
				});
		}
	}
</script>
@endsection
@extends('layouts.back')

@section('content')



<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"> {{ __('msg.Support Tickets') }}</h6>
            </div>

			<div class="card-body" style="min-height:500px">

                <a href="{{ route('tickets.create') }}" class="btn btn-success mb-3 float-right">{{ __('msg.New Ticket') }}</a>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">{{ __('msg.Subject') }}</th>
                                <th scope="col">{{ __('msg.Category') }}</th>
                                <th scope="col">{{ __('msg.Status') }}</th>
                                <th scope="col">{{ __('msg.Created At') }}</th>
                                <th scope="col">{{ __('msg.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                            @php $reponses = \App\Models\Comment::where('ticket_id',$ticket->id)->count(); @endphp
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->subject }} ({{ $reponses }})</td>
                                <td>{{ ucfirst($ticket->category) }}</td>
                                <td>
                                    <span class="badge {{ $ticket->status == 'Opened' ? 'badge-warning' : ($ticket->status == 'In Progress' ? 'badge-primary' : 'badge-success') }}">
                                        {{ $ticket->status }}
                                    </span>
                                </td>
                                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-info btn-sm">{{ __('msg.View') }}</a>
                                    @if(auth()->user()->user_type=='admin')
                                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-warning btn-sm">{{ __('msg.Edit') }}</a>
                                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Etes vou sûres?')">{{ __('msg.Delete') }}</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>

        </div>
    </div>

</div>

</div>


@endsection
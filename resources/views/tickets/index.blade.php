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

                <div class="filters row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-2">
                        <label>{{ __('msg.Status') }}</label>
                        <select id="status" class="form-control" name="status">
                            <option value="">{{ __('msg.All') }}</option>
                            <option value="Opened">{{ __('msg.Opened') }}</option>
                            <option value="In Progress">{{ __('msg.In Progress') }}</option>
                            <option value="Resolved">{{ __('msg.Resolved') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>{{ __('msg.Category') }}</label>
                        <select id="category" class="form-control" name="category">
                            <option value="">{{ __('msg.All') }}</option>
                            <option value="question">{{ __('msg.Question') }}</option>
                            <option value="error">{{ __('msg.Error or Problem') }}</option>
                            <option value="suggestion">{{ __('msg.Suggestion') }}</option>
                            <option value="other">{{ __('msg.Other') }}</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">{{ __('msg.Subject') }}</th>
                                <th scope="col">{{ __('msg.Category') }}</th>
                                <th scope="col">{{ __('msg.Status') }}</th>
                                <th scope="col">{{ __('msg.Created At') }}</th>
                                <th scope="col">{{ __('msg.By') }}</th>
                                <th scope="col">{{ __('msg.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                            @php
                            $reponses = \App\Models\Comment::where('ticket_id',$ticket->id)->count();
                            $user= \App\Models\User::find($ticket->user_id);
                            @endphp
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
                                <td>{{ $user->name ?? '' }} {{ $user->lastname ?? '' }}</td>
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



@endsection

@section('footer_scripts')

<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.rowReorder.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.scroller.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.responsive.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/pdfmake.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/vfs_fonts.js') }}"></script>

<style>
    .searchfield {
        width: 100px;
    }

    .table {
        width: 100% !important;
        margin-top: 10px !important;
    }

    #DataTables_Table_0_length,
    #DataTables_Table_0_paginate {
        display: none;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {

        $('.table thead tr:eq(1) th').each(function() {
            var title = $('.table thead tr:eq(0) th').eq($(this).index()).text();
            $(this).html('<input class="searchfield" type="text"   />');
        });

        var table = $('.table').DataTable({
            orderCellsTop: true,
            dom: '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
            responsive: true,
            aaSorting: [],
            buttons: [

                'csv', 'excel', 'pdf', 'print'
            ],
            "columnDefs": [{
                "targets": 'no-sort',
                "orderable": false,
            }],
            "language": {
                "decimal": "",
                "emptyTable": "Pas de données",
                "info": "affichage de  _START_ à _END_ de _TOTAL_ entrées",
                "infoEmpty": "affichage 0 à 0 de 0 entrées",
                "infoFiltered": "(Filtrer de _MAX_ total d`entrées)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "affichage de _MENU_ entrées",
                "loadingRecords": "chargement...",
                "processing": "chargement ...",
                "search": "Recherche:",
                "zeroRecords": "Pas de résultats",
                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "next": "Suivant",
                    "previous": "Précédent"
                },
                "aria": {
                    "sortAscending": ": activer pour un tri ascendant",
                    "sortDescending": ": activer pour un tri descendant"
                }
            }
        });

        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this,
                    args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function() {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }
        // Apply the search
        table.columns().every(function(index) {
            $('.table thead tr:eq(1) th:eq(' + index + ') input').on('keyup change', function() {
                table.column($(this).parent().index() + ':visible')
                    .search(this.value)
                    .draw();

            });

            $('.table thead tr:eq(1) th:eq(' + index + ') input').keyup(delay(function(e) {
                console.log('Time elapsed!', this.value);
                $(this).blur();

            }, 2000));
        });

        // Filter based on status and category selections
        $('#category').on('change', function() {
            table.column(2)
                .search(this.value)
                .draw();
        });
        $('#status').on('change', function() {
            table.column(3)
                .search(this.value)
                .draw();
        });
    });
</script>

@endsection
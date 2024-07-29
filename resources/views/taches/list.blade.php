@extends('layouts.back')

@section('content')

<?php

?>

<style>
    .task-header {
        display: flex;
        justify-content: space-between;
        /*align-items: center;*/
    }

    .task-title {
        font-weight: bold;
    }

    .task-date {
        color: grey;
    }

    .task-details {
        margin-top: 5px;
        margin-left:15px;
        padding-left:20px;
        border-left:2px solid grey;
    }

    .task-actions {
        margin-top: 10px;
    }

    .status {
        padding: 5px 5px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Liste des prises de contact </h6>
            </div>

            <div class="card-body" style="min-height:500px">
                <div class="row">
                    @if(isset($client))
                    <div class="col-sm-12">
                        <a href="{{route('taches.create',['id'=>$client->id])}}" class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-plus"></i> Ajouter</a>
                    </div>
                    @endif
                </div>
                <!--
                <table id="mytable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr id="headtable">
                            <th>ID </th>
                            <th>Sujet</th>
                            <th>Contact</th>
                            <th>Type</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taches as $tache)
                            <tr>
                                <td>{{ $tache->id }}</td>
                                <td><a href="{{route('taches.show',['id'=>$tache->id])}}">{{ $tache->Subject }}</a></td>
                                <td>{{ $tache->Nom_contact }}</td>
                                <td>{{ $tache->Type }}</td>
                                <td>{{ $tache->Priority }}</td>
                                <td>{{ $tache->Status }}</td>
                                <td>{{ date('d/m/Y', strtotime($tache->DateTache)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
-->

                @php
                $groupedTasks = $taches->groupBy(function($tache) {
                return \Carbon\Carbon::parse($tache->DateTache)->translatedFormat('F Y');
                });
                @endphp

                <div class="accordion" id="tasksAccordion">
                    @foreach($groupedTasks as $month => $tasks)

                    <div class="card">
                        <div class="card-header" id="heading-{{ $loop->index }}">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse-{{ $loop->index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse-{{ $loop->index }}">
                                    {{ $month }}
                                </button>
                            </h2>
                        </div>

                        <div id="collapse-{{ $loop->index }}" class="collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading-{{ $loop->index }}" data-parent="#tasksAccordion">
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach($tasks as $task)
                                    @php
                                    $color='';
                                    switch ( $task->Status ) {
                                    case 'Not Started':
                                    $color = '#82e2e8';
                                    break;
                                    case 'Waiting on someone e':
                                    $color = '#ea922b';
                                    break;
                                    case 'In Progress':
                                    $color = '#5f9fff';
                                    break;
                                    case 'Deferred':
                                    $color = '#a778c9';
                                    break;
                                    case 'Completed':
                                    $color = '#40c157';
                                    break;
                                    default:
                                    $color = '';
                                    }

                                    $class='';
                                    switch ( $task->Priority ) {
                                    case 'Normal':
                                    $class = 'primary';
                                    break;
                                    case 'High':
                                    $class = 'danger';
                                    break;
                                    case 'Low':
                                    $class = 'info';
                                    break;

                                    default:
                                    $class = '';
                                    }

                                    $icon='';
                                    switch ( $task->Type ) {
                                    case 'Acompte / Demande de paiement':
                                    $icon = 'img/invoice.png';
                                    break;
                                    case 'Appel téléphonique':
                                    $icon = 'img/call.png';
                                    break;
                                    case 'Envoyer email':
                                    $icon = 'img/email.png';
                                    break;

                                    case 'Envoyer courrier':
                                    $icon = 'img/mail.png';
                                    break;


                                    default:
                                    $class = '';
                                    }
                                    @endphp
                                    <li class="list-group-item">
                                        <div class="task-header">
                                            <span class="task-title" title="{{$task->Type}}"><img  src="{{  URL::asset($icon) }}"  width="25"/> {{ $task->Subject }}</span>
                                            <span class="task-date">{{ \Carbon\Carbon::parse($task->DateTache)->translatedFormat(' d M') }}</span>
                                        </div>
                                        <div class="task-details">
                                            @if($task->Nom_contact !='')<span><i class="fas fa-user-circle"></i> {{ $task->Nom_contact }}</span> <br>@endif
                                            <span class="float-right status ml-2" style="color:white;font-weight:bold;background-color:{{$color}}" title="Statut"><i class="fas fa-flag"></i> {{ $task->Status }}</span>
                                            <span class="float-right status bg-{{$class}} ml-2" style="color:white;" title="Priorité"><i class="fas fa-bell"></i> {{ $task->Priority }}</span>

                                            {{ $task->Description }}

                                        </div>
                                        <div class="task-actions">
                                                <!-- Place for any task actions like edit, delete -->
                                            <a href="{{ route('taches.show',['id'=>$task->id]) }}" class="btn btn-primary btn-sm "><i class="fa fa-edit"></i></a>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
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

    #mytable {
        width: 100% !important;
        margin-top: 10px !important;
    }
</style>


<script type="text/javascript">
    $(document).ready(function() {


        $('#mytable thead tr:eq(1) th').each(function() {
            var title = $('#mytable thead tr:eq(0) th').eq($(this).index()).text();
            $(this).html('<input class="searchfield" type="text"   />');
        });

        var table = $('#mytable').DataTable({
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
            $('#mytable thead tr:eq(1) th:eq(' + index + ') input').on('keyup change', function() {
                table.column($(this).parent().index() + ':visible')
                    .search(this.value)
                    .draw();


            });

            $('#mytable thead tr:eq(1) th:eq(' + index + ') input').keyup(delay(function(e) {
                console.log('Time elapsed!', this.value);
                $(this).blur();

            }, 2000));
        });



    });
</script>
@stop
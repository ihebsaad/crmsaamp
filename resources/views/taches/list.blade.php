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
        margin-left: 15px;
        padding-left: 20px;
        border-left: 2px solid grey;
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
                <h6 class="m-0 font-weight-bold text-primary"> {{$title}} </h6>
            </div>

            <div class="card-body" style="min-height:500px">
                <form  @if( request()->is('mestaches')) action="{{route('mestaches')}}"   @else action="{{route('taches.index')}}"  @endif  method="GET" style="display:contents">

                    <div class="row">
                        <div class="col-md-6 mb-2">
                        @if(auth()->user()->user_type =='admin' || auth()->user()->role =='admin')
                                         <span for="agence" class="text-primary mr-2">{{__('msg.Agency')}}:</span>
                                        <select class=" mt-2 form-control" id="agence" onchange="filter()" style="width:200px">
                                            <option value="agence">{{__('msg.All')}}</option>
                                            <option value="web">WEB</option>
                                            @foreach ($agences as $agence)
                                            <option value="{{$agence->agence_lib}}">{{$agence->agence_lib}}</option>
                                            @endforeach
                                        </select>
                                 @else
                                    <input type="hidden" id="agence" value="{{auth()->user()->agence_ident}}" />
                                @endif
                        </div>
                        <div class="col-md-6">
                        <a href="{{route('stats_tasks')}}" class="btn btn-success mb-2 ml-3 mr-2 float-right"><i class="fas fa-stats"></i> Voir statistiques d'activité</a>
                        </div>
                    </div>
                        <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                                    <span class="text-primary mr-2">{{__('msg.Name')}}:</span><br>
                                    <input class="form-control" name="nom" type="text" value="{{$nom}}" style="width:95%" />
                                </div>
                                <div class="col-md-2 col-sm-12 mb-2">
                                    <span class="text-primary mr-2">{{__('msg.Client ID')}}:</span><br>
                                    <input class="form-control" name="cl_ident" type="number" value="{{$cl_ident}}" style="max-width:100px" />
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <span class="text-primary mr-2">Début</span><br>
                                    <input class="form-control datepicker" id="debut" name="debut" value="{{ $debut }}" style="width:150px" />
								</div>
								<div class="col-md-2 col-sm-12">
                                    <span class="text-primary ">Fin</span><br>
                                    <input class="form-control datepicker" id="fin" name="fin" value="{{ $fin }}" style="width:150px" />
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <button class="btn btn-info mt-4" type="submit" value="{{__('msg.Filter')}}"><i class="fa fa-filter"></i> {{__('msg.Filter')}}</input>
                                </div>
                            </form>
                            <!--
                    <div class="col-sm-6">
                        @if( request()->is('mestaches'))
                        <a href="{{route('taches.index')}}" class="btn btn-primary mb-3 mr-3 float-right"><i class="fas fa-tasks"></i> Activités de l'agence</a>
                        @else
                        <a href="{{route('mestaches')}}" class="btn btn-primary mb-3 mr-3 float-right"><i class="fas fa-tasks"></i> Mes Activités</a>
                        @endif
                    </div>
                    -->
                        </div>



                <div class="row">
                    @if(isset($client))
                    <div class="col-sm-12">
                        <a href="{{route('taches.create',['id'=>$client->id])}}" class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-plus"></i> Ajouter</a>
                    </div>
                    @endif
                </div>

                @php
                $groupedTasks = $taches->sortByDesc(function($tache) {
                return \Carbon\Carbon::parse($tache->DateTache)->format('Y-m-d') . ' ' . $tache->heure_debut; // Sort by DateTache and heure_debut
                })->groupBy(function($tache) {
                return \Carbon\Carbon::parse($tache->DateTache)->translatedFormat('F Y'); // Group by 'Month Year'
                });
                @endphp

                <div class="accordion" id="tasksAccordion" style="border-bottom:1px solid lightgray">

                    @if(count($taches)==0)
                    <h2 class="text-center mt-5">{{__('msg.You have no contacts')}}</h2>
                    @else

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
                                    $client=\App\Models\CompteClient::where('id',$task->ID_Compte)->first();
                                    $color='';
                                    switch ( $task->Status ) {
                                    case 'Not Started':
                                    $color = '#82e2e8';$statut=__('msg.Not started');
                                    break;
                                    case 'In Progress':
                                    $color = '#5f9fff';$statut=__('msg.In progress');
                                    break;
                                    case 'Deferred':
                                    $color = '#a778c9';$statut=__('msg.Deferred');
                                    break;
                                    case 'Completed':
                                    $color = '#40c157';$statut=__('msg.Completed');
                                    break;
                                    default:
                                    $color = '';$statut='';
                                    }

                                    $class='';
                                    switch ( $task->Priority ) {
                                    case 'Normal':
                                    $class = 'primary';$priority=__('msg.Normal');
                                    break;
                                    case 'High':
                                    $class = 'danger';$priority=__('msg.High');
                                    break;
                                    case 'Low':
                                    $class = 'info';$priority=__('msg.Low');
                                    break;

                                    default:
                                    $class = '';$priority='Normale';
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
                                    $icon = 'img/task.png';
                                    }
                                    @endphp
                                    <li class="list-group-item agence  @if($task->as400 == 0) web @endif  {{ $task->Agence }}">
                                        <div class="task-header">
                                            <span class="task-title" title="{{$task->Type}}"><img src="{{  URL::asset($icon) }}" width="25" /> {{ $task->Subject }}</span>
                                            <span class="task-date">{{ \Carbon\Carbon::parse($task->DateTache)->translatedFormat(' d M') }} {{$task->heure_debut}}</span>
                                        </div>
                                        <div class="task-details">
                                            <span style="color:black"><i class="fas fa-user-circle"></i><a href="{{route('fiche',['id'=>$client->id ?? 0])}}"> @if($task->Nom_de_compte !='') {{ $task->Nom_de_compte }} @else {{$client->Nom ?? ''}} @endif - Client ID : {{ $task->mycl_id }}</a> </span><br>
                                            <span class="float-right status ml-2" style="color:white;font-weight:bold;background-color:{{$color}}" title="Statut"><i class="fas fa-flag"></i> {!! $statut !!}</span>
                                            <span class="float-right status bg-{{$class}} ml-2" style="color:white;" title="Priorité"><i class="fas fa-bell"></i> {!! $priority !!}</span>

                                            {{ $task->Description }}<br>
                                            <i>{{ $task->Agence }}</i>
                                            @if($task->as400 == 0) <br><b>WEB</b> @endif

                                        </div>
                                        <div class="task-actions">
                                            <!-- Place for any task actions like edit, delete -->
                                            @if($task->as400 == 0)
                                            <a href="{{ route('taches.show',['id'=>$task->id]) }}" class="btn btn-primary btn-sm "><i class="fa fa-edit"></i></a>
                                            @endif
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
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

@if($title=="Suivi d'activité")
<script type="text/javascript">
    filter();
</script>
@endif
<script type="text/javascript">
    //filter();

    function filter() {
        //var classname = $(elm).is(":checked") ? 1 : 0;
        var classname = $('#agence').val();
        toggle('agence', 'none');
        toggle(classname, 'block');
    }

    function toggle(className, displayState) {
        var elements = document.getElementsByClassName(className);
        for (var i = 0; i < elements.length; i++) {
            elements[i].style.display = displayState;
        }
    }

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




        $( ".datepicker" ).datepicker({

            //altField: "#datepicker",
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            buttonImage: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAATCAYAAAB2pebxAAABGUlEQVQ4jc2UP06EQBjFfyCN3ZR2yxHwBGBCYUIhN1hqGrWj03KsiM3Y7p7AI8CeQI/ATbBgiE+gMlvsS8jM+97jy5s/mQCFszFQAQN1c2AJZzMgA3rqpgcYx5FQDAb4Ah6AFmdfNxp0QAp0OJvMUii2BDDUzS3w7s2KOcGd5+UsRDhbAo+AWfyU4GwnPAYG4XucTYOPt1PkG2SsYTbq2iT2X3ZFkVeeTChyA9wDN5uNi/x62TzaMD5t1DTdy7rsbPfnJNan0i24ejOcHUPOgLM0CSTuyY+pzAH2wFG46jugupw9mZczSORl/BZ4Fq56ArTzPYn5vUA6h/XNVX03DZe0J59Maxsk7iCeBPgWrroB4sA/LiX/R/8DOHhi5y8Apx4AAAAASUVORK5CYII=",
            firstDay: 1,
            dateFormat: "yy-mm-dd",
            //minDate:0
        });

    });



</script>
@stop
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
                <h6 class="m-0 font-weight-bold text-primary">Liste des Réclamations </h6>
            </div>

            <div class="card-body" style="min-height:500px">
                <div class="row">
                    <label class="ml-2 pointer text-primary" for='mycheck'><input id="mycheck" type="checkbox" onclick="filter(this)"> Uniquement clôturés</input></lablel>
                </div>

                <table id="mytable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr id="headtable">
                            <th>ID </th>
                            <th>Nom</th>
                            <th>Date</th>
                            <th>Contact</th>
                            <th>Motif</th>
                            <th>Agence</th>
                            <th>Date de clôture</th>
                            <th>Type</th>
                            @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                            <th>Supp</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($retours as $retour)
                        @php
                        $class='';
                        switch ( $retour->Type_retour ) {
                        case 'Négatif':
                        $class = 'danger';
                        break;
                        case 'Positif':
                        $class = 'success';
                        break;
                        case 'Information générale':
                        $class = 'primary';
                        break;

                        default:
                        $class = '';
                        }
                        @endphp
                        <tr class="myline @if($retour->Date_cloture=='0000-00-00' || $retour->Date_cloture=='') noncloture @endif">
                            <td>{{$retour->id}}</td>
                            <td><a href="{{route('retours.show',['id'=>$retour->id])}}">{{$retour->Name}}</a></td>
                            <td>{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</td>
                            <td>{{$retour->Nom_du_contact}}</td>
                            <td>{{$retour->Motif_retour}}</td>
                            <td>{{$retour->Responsable_de_resolution}}</td>
                            <td> @if($retour->Date_cloture!='0000-00-00' && $retour->Date_cloture!='') {{date('d/m/Y', strtotime($retour->Date_cloture))}} @endif </td>
                            <td style="color:white" class="bg-{{$class}}">{{$retour->Type_retour}}</td>
                            @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                            <td>
                                <a title="Supprimer" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('retours.destroy', $retour->id )}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                    <span class="fa fa-fw fa-trash-alt"></span>
                                </a>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                </table>

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
        function filter(elm) {
            var nonsolde = $(elm).is(":checked") ? 1 : 0;
            if (nonsolde) {
                toggle('noncloture', 'none');
            } else {
                toggle('myline', 'table-row');
            }
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
                pageLength:50,
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
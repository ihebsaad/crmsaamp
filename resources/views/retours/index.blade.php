@extends('layouts.back')

@section('content')

<style>
    .pointer{
        cursor:pointer;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-sm-12 mb-4">
        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.List of Complaints')}} </h6>
            </div>
            <div class="card-body" style="min-height:500px">
                <div class="row">
                    <div class="col-md-2">
                        <label class="ml-2 pointer text-primary" for='mycheck'><input id="mycheck" type="checkbox"  > {{__('msg.Only fenced')}}</input></lablel>
                    </div>
                    <div class="col-md-2">
                        <label class="ml-2 pointer text-primary" for='mycheck2'><input id="mycheck2" type="checkbox"  > {{__('msg.Only open')}}</input></lablel>
                    </div>
                    @if( auth()->user()->user_role== 1 || auth()->user()->user_role== 2 ||auth()->user()->user_role== 3 || auth()->user()->user_role == 5)

                    <div class="col-md-4">
                    Agence
                    <select type="text" id="agence_ident" class="form-control" name="agence_ident" style="width:150px;display:block">
                        <option value=""></option>
                        @foreach($agences as $agence)
                        <option value="{{$agence->agence_lib}}">{{$agence->agence_lib}}</option>
                        @endforeach
                    </select>
                    </div>
                    @endif


                </div>
                <table id="mytable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr id="headtable">
                            <th>ID </th>
                            <th>{{__('msg.Name')}}</th>
                            <th>{{__('msg.Date')}}</th>
                            <th>{{__('msg.Contact')}}</th>
                            <th>{{__('msg.Reason')}}</th>
                            <th>{{__('msg.Agency')}}</th>
                            <th>{{__('msg.Closing date')}}</th>
                            <th>{{__('msg.Type')}}</th>
                            @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                            <th>{{__('msg.Del')}}</th>
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
                        <tr class="myline @if($retour->Date_cloture=='0000-00-00' || $retour->Date_cloture=='') noncloture @else cloture @endif">
                            <td>{{$retour->id}}</td>
                            <td><a href="{{route('retours.show',['id'=>$retour->id])}}">{{$retour->Name}}</a></td>
                            <td data-order="{{ $retour->Date_ouverture ? date('Y-m-d', strtotime($retour->Date_ouverture)) : '' }}">
                                {{ $retour->Date_ouverture ? date('d/m/Y', strtotime($retour->Date_ouverture)) : '' }}
                            </td>
                            <td>{{$retour->Nom_du_contact}}</td>
                            <td>{{$retour->Motif_retour}}</td>
                            <td>{{$retour->Responsable_de_resolution}}</td>
                            <td data-order="{{ $retour->Date_cloture && $retour->Date_cloture != '0000-00-00' ? date('Y-m-d', strtotime($retour->Date_cloture)) : '' }}">
                                {{ $retour->Date_cloture && $retour->Date_cloture != '0000-00-00' ? date('d/m/Y', strtotime($retour->Date_cloture)) : '' }}
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
    $(document).ready(function() {
        // Initialisation de DataTable avec options de configuration
        var table = $('#mytable').DataTable({
            orderCellsTop: true,
            dom: '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
            responsive: true,
            aaSorting: [],
            pageLength: 50,
            buttons: ['csv', 'excel', 'pdf', 'print'],
            columnDefs: [{
                "targets": 'no-sort',
                "orderable": false,
            }],
            <?php if(auth()->user()->lg == 'fr') { ?>
            language: {
                "decimal": "",
                "emptyTable": "Pas de données",
                "info": "affichage de  _START_ à _END_ de _TOTAL_ entrées",
                "infoEmpty": "affichage 0 à 0 de 0 entrées",
                "infoFiltered": "(Filtrer de _MAX_ total d`entrées)",
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
            <?php } ?>
        });

        // Configuration des champs de recherche dans chaque colonne
        $('#mytable thead tr:eq(1) th').each(function() {
            var title = $('#mytable thead tr:eq(0) th').eq($(this).index()).text();
            $(this).html('<input class="searchfield" type="text" />');
        });

        // Appliquer la recherche pour chaque colonne
        table.columns().every(function(index) {
            $('#mytable thead tr:eq(1) th:eq(' + index + ') input').on('keyup change', function() {
                table.column($(this).parent().index() + ':visible').search(this.value).draw();
            });
        });

        // Fonction pour filtrer les lignes clôturées
        function filter(elm) {
            var onlyFenced = $(elm).is(":checked");
            $('#mycheck2').prop('checked', false); // Décocher l'autre filtre

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var isClosed = $(table.row(dataIndex).node()).hasClass('cloture');
                return onlyFenced ? isClosed : true;
            });
            table.draw();
            $.fn.dataTable.ext.search.pop();
        }

        // Fonction pour filtrer les lignes ouvertes
        function filter2(elm) {
            var onlyOpen = $(elm).is(":checked");
            $('#mycheck').prop('checked', false); // Décocher l'autre filtre

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var isOpen = $(table.row(dataIndex).node()).hasClass('noncloture');
                return onlyOpen ? isOpen : true;
            });
            table.draw();
            $.fn.dataTable.ext.search.pop();
        }

        // Appliquer le filtre "Only open" au départ
        $('#mycheck2').prop('checked', true);
        filter2($('#mycheck2'));

        // Attachement des fonctions de filtre aux checkboxes
        $('#mycheck').on('click', function() {
            filter(this);
        });

        $('#mycheck2').on('click', function() {
            filter2(this);
        });

        // Filtrer par agence
        $('#agence_ident').on('change', function() {
            var agence = $(this).val();
            table.column(5).search(agence).draw(); // La colonne "Agence" est la 6ème colonne (index 5)
        });
    });

    </script>
    @stop
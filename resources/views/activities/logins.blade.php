@extends('layouts.back')


@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/datatables/css/buttons.bootstrap.css') }}" />

<link rel="stylesheet" type="text/css" href="{{ asset('assets/datatables/css/scroller.bootstrap.css') }}" />



<style>
    .uper {
        margin-top: 10px;
    }

    .no-sort input {
        display: none;
    }
</style>


<div class="row">

    <!-- Content Column -->
    <div class="col-lg-12 col-sd-12 mb-4">

        <div class="card shadow mb-4">
            <div class="  ">
                <a href="#div1" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                    <h6 class="m-0 font-weight-bold text-primary">Connexions des utilisateurs</h6>
                </a>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-8 col-sm-6">
                        <form method="GET" action="{{ route('logins') }}" style="width:100%;display:flex">
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label for="debut">Date de début :</label>
                                    <input type="date" id="debut" name="debut" class="form-control" value="{{ request('debut') }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label for="fin">Date de fin :</label>
                                    <input type="date" id="fin" name="fin" class="form-control" value="{{ request('fin') }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 mt-4">
                                <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-filter"></i>   Filtrer</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-right">
                        <a href="{{route('consultations')}}" class="btn btn-success" style=" "><i class="fas fa-chalkboard-teacher"></i> Historique des activités</a>
                    </div>

                </div>
                <table id="mytable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr id="headtable">
                            <th>ID </th>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Date de connexion</th>
                            <th>Date de déconnexion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logins as $login)
						    @php
                            $role='';
                            switch ($login->user->user_role) {
                                case 1:
                                    $role= "Admin";
                                    break;
                                case 2:
                                    $role= "Direction";
                                    break;
                                case 3:
                                    $role= "Superviseur";
                                    break;
                                case 4:
                                    $role= "Responsable d'agence";
                                    break;
                                case 5:
                                    $role= "Qualité";
                                    break;
                                case 6:
                                    $role= "Adv";
                                    break;
                                case 7:
                                    $role= "Commercial";
                                    break;
                                case 8:
                                    $role= "Animateur commercial";
                                    break;
                            }

                            @endphp
                            <tr>
                                <td>{{ $login->user->id ?? '' }}</td>
                                <td>@if($login->user !='')  {{ $login->user->name }} {{ $login->user->lastname }}   @endif</td>
                                <td>{{ $login->user->email ?? '' }}</td>
                                <td>{{ $login->user->user_type ?? '' }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($login->login_at)) ?? ''}}</td>
                                <td>{{ $login->logout_at!='' ? date('d/m/Y H:i', strtotime($login->logout_at)) : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                    <div class="col-lg-12 col-sm-6">
                        <form method="GET" action="{{ route('export-user-logins') }}">
                                <input type="hidden" name="debut" value="{{ request('debut') }}">
                                <input type="hidden" name="fin" value="{{ request('fin') }}">
                                <button type="submit" style="background-color:#1cc88a" class="btn btn-success float-right mr-3"><i class="fa fa-file-excel"></i>   Exporter </button>
                        </form>
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
            pageLength: 50, // Affiche 10 lignes par défaut
            lengthMenu: [[20, 50,75,100, -1], [20, 50,75,100, "Tout"]],
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

        // Restore state
        /*     var state = table.state.loaded();
            if ( state ) {
                table.columns().eq( 0 ).each( function ( colIdx ) {
                    var colSearch = state.columns[colIdx].search;

                    if ( colSearch.search ) {
                        $( '#mytable thead tr:eq(1) th:eq(' + index + ') input', table.column( colIdx ).footer() ).val( colSearch.search );

                    }
                } );

                table.draw();
            }

*/

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
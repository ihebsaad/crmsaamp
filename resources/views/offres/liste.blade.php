@extends('layouts.back')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
 
            <!-- Card avec filtres et tableau -->
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="h3 mb-0 text-gray-800">
                                {{__('msg.List of offers')}}
                            </h1>
                            @if(isset($client))
                                <p class="text-muted mb-0">
                                    <small>{{ $client->cl_ident ?? '' }} - {{ $client->Nom}}</small>
                                </p>
                            @endif
                        </div>
                        @if(isset($client))
                            <a href="{{route('offres.create',['id'=>$client->id])}}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i> {{__('msg.Add')}}
                            </a>
                        @endif
                            
                            <!-- Filtres -->
                            <div class="row" style="padding-left: 20px!important;padding-top: 10px!important;">
                                <div class="col-md-3 mb-2">
                                    <label class="small font-weight-bold text-muted">Validation</label>
                                    <select id="validation-filter" class="form-control form-control-sm">
                                        <option value="">Tous</option>
                                        <option value="validated">Validées</option>
                                        <option value="not_validated">En attente</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-2">
                                    <label class="small font-weight-bold text-muted">Statut</label>
                                    <select id="status-filter" class="form-control form-control-sm">
                                        <option value="">Tous les statuts</option>
                                            <option value="KO">KO</option>
                                            <option value="OK">OK</option>
                                            <option value="vide">Vide</option>
                                    </select>
                                </div>
                                @if($user->user_role == 1 || $user->user_role == 2 || $user->user_role == 3   || $user->user_role == 8)
                                <div class="col-md-3 mb-2">
                                    <label class="small font-weight-bold text-muted">Agence</label>
                                    <select id="agence-filter" class="form-control form-control-sm">
                                        <option value="">Toutes les agences</option>
                                        @foreach($agences as $agence)
                                            <option value="{{$agence->agence_ident}}">{{$agence->agence_lib}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                @if($user->user_role == 1 || $user->user_role == 2 || $user->user_role == 3   || $user->user_role == 4   || $user->user_role == 8 )
                                <div class="col-md-3 mb-2">
                                    <label class="small font-weight-bold text-muted">Créé par</label>
                                    <select id="user-filter" class="form-control form-control-sm">
                                        <option value="">Tous les utilisateurs</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}} {{$user->lastname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-12">
                                    <button id="reset-filters" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times mr-1"></i>Réinitialiser les filtres
                                    </button>
                                </div>
                            </div>
                    </div>
                </div>
            

                <div class="card-body"  style="min-height:500px;padding-top: 0px!important;">
                    <!-- Tableau -->
                    <div class="table-responsive">
                        <table id="offres-table" class="table table-hover table-sm table-striped" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>{{__('msg.Name')}}</th>
                                    <th>{{__('msg.Account')}}</th>
                                    <th>{{__('msg.Type')}}</th>
                                    <th>{{__('msg.Created by')}}</th>
                                    <th>{{__('msg.Agency')}}</th>
                                    <th>{{__('msg.Status')}}</th>
                                    <th>Validation</th>
                                    <th>Commentaire</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
			</div>
        </div>
    </div>
</div>

@endsection

@section('footer_scripts')
<!-- DataTables CSS & JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap4.min.css">

<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<style>
.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.badge {
    font-size: 0.8em;
    padding: 0.4em 0.6em;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    margin-top: 1rem;
}

.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
    padding: 0.375rem 0.75rem;
}

.page-link {
    color: #5a5c69;
}

.page-item.active .page-link {
    background-color: #5a5c69;
    border-color: #5a5c69;
}

/* Style pour les filtres */
.form-control-sm {
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
}

.form-control-sm:focus {
    border-color: #bac8f3;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* Animation pour les rows */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.table tbody tr {
    animation: fadeIn 0.3s ease-in;
}
</style>

<script type="text/javascript">
$(document).ready(function() {
    // Configuration de la langue
    const languageConfig = @json(auth()->user()->lg === 'fr');
    const language = languageConfig ? {
        "decimal": "",
        "emptyTable": "Aucune donnée disponible",
        "info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
        "infoEmpty": "Affichage de 0 à 0 sur 0 entrées",
        "infoFiltered": "(filtré à partir de _MAX_ entrées au total)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Afficher _MENU_ entrées",
        "loadingRecords": "Chargement...",
        "processing": "Traitement...",
        "search": "Rechercher:",
        "zeroRecords": "Aucun résultat trouvé",
        "paginate": {
            "first": "Premier",
            "last": "Dernier",
            "next": "Suivant",
            "previous": "Précédent"
        },
        "aria": {
            "sortAscending": ": activer pour trier par ordre croissant",
            "sortDescending": ": activer pour trier par ordre décroissant"
        }
    } : {};

    // Initialisation de DataTable
    const table = $('#offres-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('offres.getdata') }}",
            data: function (d) {
                d.validation_filter = $('#validation-filter').val();
                d.agence_filter = $('#agence-filter').val();
                d.user_filter = $('#user-filter').val();
                d.status_filter = $('#status-filter').val();
            }
        },
        columns: [
            {data: 'id', name: 'id', width: '60px'},
            {data: 'nom_offre', name: 'Nom_offre'},
            {data: 'nom_compte', name: 'nom_compte'},
            {data: 'type', name: 'type'},
            {data: 'created_by', name: 'created_by', orderable: true},
            {data: 'agence', name: 'agence', orderable: false},
            {data: 'status', name: 'statut', width: '60px', orderable: false},
            {data: 'validation', name: 'validation', orderable: false},
            {data: 'commentaire', name: 'commentaire', orderable: false},
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimer',
                className: 'btn btn-info btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            }
        ],
        language: language,
        columnDefs: [
            {
                targets: 'no-sort',
                orderable: false
            }
        ]
    });

    // Ajout des boutons d'export
    table.buttons().container()
        .appendTo($('.card-header .row .col-12'));

    // Gestion des filtres
    $('#validation-filter, #agence-filter, #user-filter, #status-filter').change(function() {
        table.draw();
    });

    // Réinitialisation des filtres
    $('#reset-filters').click(function() {
        $('#validation-filter, #agence-filter, #user-filter, #status-filter').val('');
        table.draw();
    });

    // Animation au chargement
    table.on('draw', function() {
        $('tbody tr').each(function(index) {
            $(this).css('animation-delay', (index * 0.05) + 's');
        });
    });
});
</script>
@endsection
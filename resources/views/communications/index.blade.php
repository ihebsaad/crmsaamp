@extends('layouts.back')

@section('content')

<?php

?>

<style>
    .searchfield {
        width: 100px;
    }

    #mytable {
        width: 100% !important;
        margin-top: 10px !important;
    }


</style>
<link rel="stylesheet" href="{{ asset('sbadmin/summernote/summernote-bs4.min.css')}}">

<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Liste des communications</h6>
            </div>

            <div class="card-body" style="min-height:500px">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{route('communications.create')}}"  class="btn btn-primary mb-3 ml-3"><i class="fas fa-plus"></i> Créer une communication</a>
                    </div>
                </div>
                <table id="mytable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr id="headtable">
                            <th>ID</th>
                            <th>{{__('msg.Date')}}</th>
                            <th>{{__('msg.By')}}</th>
                            <th>Destinataires</th>
                            <th>{{__('msg.Status')}}</th>
							<th>{{__('msg.Type')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($communications as $communication)
						@php $creator = \App\Models\User::find($communication->par);
                        if($communication->statut==1){ $badge='<span class="badge btn-sm btn-success">Succès</span>';  }else{ $badge='<span class="badge btn-sm btn-danger">Echec</span>';  }
                        @endphp
                            <tr style="cursor:pointer" onclick="show_details({{$communication->id}})" title="cliquez pour voir les détails">
                                <td>{{ $communication->id }}</td>
                                <td>{{date('d/m/Y', strtotime($communication->created_at))}}</td>
                                <td>{{$creator->name}} {{$creator->lastname}}</td>
                                <td>{{ $communication->clients ?? $communication->destinataires   }}</td>
                                <td>{!! $badge !!} </td>
                                <td>@if($communication->type==1) Clients @elseif($communication->type==2)  Prospects  @else  Clients & Prospects   @endif </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>

    </div>


    	<!-- Modal -->
	<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="searchClientsModalLabel">Détails</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
                    	<div class="form-group">
							<label for="template_subject">Objet</label>
							<input type="text" class="form-control" id="sujet" name="subject" >
						</div>
						<div class="form-group">
							<label for="template_body">Contenu</label>
							<textarea class="summernote" id="contenu" name="body"  ></textarea>
						</div>

				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">{{__('msg.Close')}}</button>
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

<script type="text/javascript">

    function show_details(communication){
        $('#sujet').val('');
        $('#contenu').val('');
        var _token = $('input[name="_token"]').val();

        $.ajax({
                url: "{{ route('get_communication') }}",
                method: "POST",
                data: {
                    communication: communication,
                    _token: _token
                },
                success: function(data) {
                    $('#sujet').val(data.sujet);
                    //$('#contenu').val(data.contenu);
                    $(".summernote").summernote("code", data.contenu);
                    $('#detailsModal').modal('show');
                }
            });
    }


    $(document).ready(function() {

        $('.summernote').summernote({
				height: 300
			});

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
            <?php if(auth()->user()->lg=='fr'){ ?>
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
            <?php } ?>
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
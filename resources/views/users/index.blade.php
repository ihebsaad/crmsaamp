@extends('layouts.admin')


@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/datatables/css/buttons.bootstrap.css') }}" />

<link rel="stylesheet" type="text/css" href="{{ asset('assets/datatables/css/scroller.bootstrap.css') }}" />



@php
use App\Http\Controllers\UsersController;
@endphp

    <style>
        .uper {
            margin-top: 10px;
        }
        .no-sort input{display:none;}
    </style>


	<div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-12 col-sd-12 mb-4">

						 <div class="card shadow mb-4">
                                <div class="  ">
                                    <a href="#div1" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                                    <h6 class="m-0 font-weight-bold text-primary">{{__('msg.List of users')}}</h6>
									</a>
                                </div>
                                <div   class="card-body">

    <div class="row">
	<div class="col-lg-8 col-sm-6"> </div>
	<div class="col-lg-4 col-sm-6">
	<a   class="btn btn-md btn-success mb-10"  style="width:300px; right:50px;margin-top:10px;margin-bottom:20px"   href="{{route('adduser')}}"  ><b><i class="fas fa-plus"></i>  {{__('msg.Add a new user')}}</b></a>
	</div>
	</div>
        <table id="mytable" class="table table-striped"  style="width:100%">
            <thead>
            <tr id="headtable">
                <th style="width:10%">N°</th>
                <th style="width:15%">{{__('msg.Name')}}</th>
                <th style="width:15%">{{__('msg.Client ID')}}</th>
                <th style="width:15%">SIRET & TVA</th>
                <th style="width:20%">{{__('msg.Sales office')}}</th>
                <th style="width:15%">{{__('msg.Activity')}}</th>
                <th style="width:10%">Actions</th>
              </tr>

            </thead>
            <tbody>
            @foreach($users as $user)

                <?php $client= \App\Models\Client::where('cl_ident',$user->client_id)->first(); ?>
                <tr>
                    <td style="width:10%" ><a title="{{__('msg.View user')}}" href="{{route('view', $user->id )}}" >{{$user->id}}</a></td>
                    <td style="width:15%" ><a title="{{__('msg.View user')}}" href="{{route('view', $user->id )}}" >{{$user->name}} {{$user->lastname}}</a></td>
                     <td style="width:15%" >{{$user->client_id}}</td>
                     <td style="width:15%"   >SIRET: {!!isset($client->siret)?$client->siret:''!!}<br>TVA: {!!isset($client->siret)?$client->num_tva:''!!}</td>
                     <td style="width:20%" >{!!isset($client->raison_sociale)?$client->raison_sociale:''!!}</td>
                     <td style="width:15%" > {{ UsersController::ActiviteById($user->activity)}} </td>
                    <td style="width:10%"   >

 <?php $User=auth()->user();
if($User['user_type']=='admin'){ ?>
						<center> <a title="{{__('msg.Login')}}"   href="{{route('loginas', $user->id )}}" class="btn btn-success btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="{{__('msg.Login')}}" >
                                <span class="far fa-eye"></span>
                            </a></center><br>

					<center>	 <a title="{{__('msg.Delete')}}" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('users.destroy', $user->id )}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                <span class="fa fa-fw fa-trash-alt"></span>
                            </a></center>

<?php } ?>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
 </div>
</div>
</div>








@endsection




@section('footer_scripts')

    <script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.rowReorder.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.scroller.js') }}" ></script>

    <script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.buttons.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.responsive.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.colVis.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.html5.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.print.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.bootstrap.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/buttons.print.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/pdfmake.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/vfs_fonts.js') }}" ></script>

    <style>.searchfield{width:100px;}
		 #mytable{width:100%!important;margin-top:10px !important;}

	</style>


    <script type="text/javascript">
        $(document).ready(function() {


            $('#mytable thead tr:eq(1) th').each( function () {
                var title = $('#mytable thead tr:eq(0) th').eq( $(this).index() ).text();
                $(this).html( '<input class="searchfield" type="text"   />' );
            } );

            var table = $('#mytable').DataTable({
                orderCellsTop: true,
                dom : '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
                responsive:true,
				 aaSorting : [],
                buttons: [

                    'csv', 'excel', 'pdf', 'print'
                ],
                "columnDefs": [ {
                    "targets": 'no-sort',
                    "orderable": false,
                } ]
                ,
                "language":
                    {
                        "decimal":        "",
                        "emptyTable":     "Pas de données",
                        "info":           "affichage de  _START_ à _END_ de _TOTAL_ entrées",
                        "infoEmpty":      "affichage 0 à 0 de 0 entrées",
                        "infoFiltered":   "(Filtrer de _MAX_ total d`entrées)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "affichage de _MENU_ entrées",
                        "loadingRecords": "chargement...",
                        "processing":     "chargement ...",
                        "search":         "Recherche:",
                        "zeroRecords":    "Pas de résultats",
                        "paginate": {
                            "first":      "Premier",
                            "last":       "Dernier",
                            "next":       "Suivant",
                            "previous":   "Précédent"
                        },
                        "aria": {
                            "sortAscending":  ": activer pour un tri ascendant",
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
                    var context = this, args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        callback.apply(context, args);
                    }, ms || 0);
                };
            }
// Apply the search
            table.columns().every(function (index) {
                $('#mytable thead tr:eq(1) th:eq(' + index + ') input').on('keyup change', function () {
                    table.column($(this).parent().index() + ':visible')
                        .search(this.value)
                        .draw();


                });

                $('#mytable thead tr:eq(1) th:eq(' + index + ') input').keyup(delay(function (e) {
                    console.log('Time elapsed!', this.value);
                    $(this).blur();

                }, 2000));
            });



        });

    </script>
@stop

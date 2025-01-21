@extends('layouts.back')

@section('content')
<style>
    h6 {
        color: black;
        font-weight: bold;
    }

    table {
        border: none;
    }

    .foldername {
        width: 100%;
        margin-bottom: 25px;
    }

    #folders-container {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
    }

    .folder-btn {
        background-image: url("{{ URL::asset('img/folder.png')}}");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        width: 150px;
        height: 150px;
        border: none;
        font-size: 16px;
        background-color: transparent;
        text-align: center;
        padding-top: 50px;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        font-family: 'Roboto';
        font-weight: bold;
    }

    .folder-btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(21, 156, 228, 0.4);
    }


    .file-title {
        font-weight: normal;
        color: black;
        font-size: 13px;
        margin-top: 5px;
        margin-bottom: 5px;
    }

    .file {
        background-image: url("{{ URL::asset('img/pdf.png')}}");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        width: 60px;
        height: 60px;
        border: none;
        background-color: transparent;
        text-align: center;
        padding-top: 30px;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .download,
    .view,
    .replace,
    .delete {
        cursor: pointer;
    }

    .ged {
        border: 2px solid #f8f9fd;
    }
</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Offer')}} {{$offre->id}}  -  {{__('msg.Customer')}} : {{$offre->cl_id}} - {{$offre->nom_compte}} </h6>
            </div>

            <div class="card-body" style="min-height:300px">

                <form action="{{ route('offres.update', $offre->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="offer" value="{{$offre->id}}" />
                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Nom_offre">{{__('msg.Name')}}:</label>
                                <h6><a href="{{route('fiche',['id'=>$offre->mycl_id ?? 0])}}">{{$offre->Nom_offre}}</a></h6><!--
                                <input type="text" id="Nom_offre" class="form-control" name="Nom_offre"  value="{{$offre->Nom_offre}}"><br><br>
                                -->
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_creation">{{__('msg.Date')}}:</label>
                                <h6>{{date('d/m/Y', strtotime($offre->Date_creation))}}</h6>
                                <!--
                                <input type="text" id="Date_creation" class="form-control datepicker" name="Date_creation"  value="{{$offre->Date_creation}}"><br><br>
                                -->
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="Type">{{__('msg.Type')}}:</label>
                            <h6>{{$offre->type}}</h6>
                        </div>

                        <div class="col-md-3">
                            <div>
                                <label for="Produit_Service">{{__('msg.Service product')}}:</label>
                                <h6>{{$offre->Produit_Service}}</h6>
                            </div>
                        </div>

                    </div>


                    <div class="row pt-1 pb-1">
                        @if(auth()->user()->id== 1 || auth()->user()->id== 10 || auth()->user()->id== 39)
                        <div class="col-md-3">
                            <div class="">
                                <label for="Statut">{{__('msg.Status')}}:</label><br>
                                <select  class="form-control" name="statut" style="width:150px">
                                    <option value=""></option>
                                    <option @selected($offre->statut=='OK') value="OK">OK</option>
                                    <option @selected($offre->statut=='KO') value="KO">KO</option>
                                </select><br><br>
                            </div>
                        </div>
                        @else
                        <div class="col-md-3">
                            <div class="">
                                <label for="Statut">{{__('msg.Status')}}:</label>
                                <h6>{{$offre->Statut}}</h6>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-5">
                            <div class="">
                                <label for="Description">{{__('msg.Description')}}:</label>
                                <h6>{{$offre->Description}}</h6>
                            </div>
                        </div>



                        @if($offre->fichier!= null)
                        <div class="col-md-4">

                            @php $fileNames = unserialize($offre->fichier); @endphp
                            <div class="">
                                <label for="Description">{{__('msg.File(s)')}}:</label><br>
                                <table>

                                    @foreach ($fileNames as $fichier)
                                    <tr>
                                        <td><label><b class="black mr-2">{{$fichier}}</b></label></td>
                                        <td><a href="https://crm.mysaamp.com/offres/{{$fichier}}" target="_blank"><img class="view mr-2" title="Visualiser" width="30" src="{{ URL::asset('img/view.png')}}"></a></td>
                                        <td><a href="https://crm.mysaamp.com/offres/{{$fichier}}" download><img class="download mr-2" title="Télecharger" width="30" src="{{ URL::asset('img/download.png')}}"></a></td>
                                    </tr>
                                    @endforeach

                                </table>
                            </div>

                        </div>
                        @endif

                        @if(count($fichiers)>0)
                        <div class="col-md-4">
                            <label for="Description">{{ __('msg.File(s)') }}:</label><br>

                            <table style="border:none;width:100%">
                                @foreach ($fichiers as $file)
                                <tr>
                                    <td style="border:none;"><label><b class="black mr-2">{{ $file->name }}</b></label></td>
                                    <td style="border:none;"><a href="{{ url('/fichiers/offres/' . $file->name) }}" target="_blank"><img class="view mr-2" title="Visualiser" width="30" src="{{ URL::asset('img/view.png') }}"></a></td>
                                    <td style="border:none;"><a href="{{ url('/fichiers/offres/' . $file->name) }}" download><img class="download mr-2" title="Télécharger" width="30" src="{{ URL::asset('img/download.png') }}"></a></td>
                                    <td style="border:none;">
                                    <td>
                                        <a title="{{__('msg.Delete')}}" onclick="deleteFile('{{ $file->id }}')" href="javascript:void(0);" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                            <span class="fa fa-fw fa-trash-alt"></span>
                                        </a>
                                    </td>
                                    </td>
                                </tr>
                                @endforeach
                            </table>

                        </div>
                        @endif
                    </div>

                    <div class="row pt-2">
                        <!--
                        <div class="col-md-4">
                            @if($offre->date_relance!='')
                            <label for="Statut">Date de relance:</label>
                            <h6>{{date('d/m/Y', strtotime($offre->date_relance))}}</h6>
                            <button type="button" class="btn-info btn" onclick="relancer()" id="relance">Relancer</button>

                            @endif
                        </div>
-->
                        <div class="col-md-3">
                            <div class="">
                                <label for="date_relance">Date de relance :</label><br>
                                <input type="text" id="date_relance" class="form-control datepicker" name="date_relance" @if($offre->date_relance!= '') value="{{date('Y-m-d', strtotime($offre->date_relance))}}" @endif style="width:150px"><br>
                                <button type="button" class="btn-warning btn mt-2" onclick="relancer()" id="relance">Relancer</button>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="">
                                <label for="commentaire">Commentaire :</label>
                                <textarea id="commentaire" class="form-control" name="commentaire">{{$offre->commentaire}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="">
                                <label for="Nom_offre">{{__('msg.Add files')}}:</label>
                                <input type="file" id="fichier" class="form-control" name="files[]" multiple accept="application/pdf" /><br><br>
                            </div>
                        </div>
                    </div>


                    <div class="row pt-3">
                        @if($offre->valide_par > 0)
                        @php $creator = \App\Models\User::find($offre->valide_par); @endphp
                        <div class="col-md-12 mt-4 mb-4 text-info">
                            <i>Offre validée par <b>{{$creator->name ?? ''}} {{$creator->lastname ?? ''}}</b> le <b>{{$offre->date_valide}}</b></i>
                        </div>
                        @endif

                        <div class="col-md-12">
                            @if(auth()->user()->role=='admin' || auth()->user()->id== 10 || auth()->user()->id== 39 || auth()->user()->id== $offre->id)
                            <button type="submit" class="btn-primary btn float-right">{{__('msg.Edit')}}</button>

                            <a title="{{__('msg.Delete')}}" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('offres.destroy', $offre->id )}}" class="btn btn-danger btn-sm btn-responsive mr-2 float-right" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                <span class="fa fa-fw fa-trash-alt"></span> {{__('msg.Delete')}}
                            </a>
                            @endif
                        </div>

                    </div>

                </form>





                <div class="row pl-2 pb-3 pt-3">
                    <div class="col-md-6">
                        <h3>Historique</h3>
                        <a href="#" class="btn btn-primary mb-3 ml-3 float-right" data-toggle="modal" data-target="#ModalOffres"><i class="fas fa-plus"></i> {{__('msg.Add')}}</a>
                        @php $status=array('1'=>"L'offre est trop basse",'2'=>"L'offre n'est pas adaptée",'3'=>"L'offre est validée",);@endphp
                        <table class="table table-striped" style="min-height:150px" id="offres">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Détails</th>
                                    <th>Date de point</th>
                                    <th class="" style="width:5%">{{__('msg.Del')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historiques as $hist)
                                <tr id="hist-{{$hist->id}}">
                                    <td>{{date('d/m/Y', strtotime($hist->date))}}</td>
                                    <td>{{$status[$hist->statut]}}</td>
                                    <td>{{$hist->details}}</td>
                                    <td>{{ $hist->date_point!= '' ? date('d/m/Y', strtotime($hist->date_point)) : ''}}</td>
                                    <td><a href="#" onclick="delete_hist({{$hist->id}})"><span class="btn btn-sm"><i class="fa fa-trash"></i></span></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h3 class="">{{__('msg.Files')}} </h3>
                        <div class="ged pl-5 pr-5 pb-5 pt-3">


                            <div class="" id="folders-container"></div>
                            <div class="row" id="files-container"></div>

                            <script>
                                <?php
                                if (isset($folderContent)) { ?>
                                    const apiResponseContent = {
                                        data: <?php echo json_encode($folderContent); ?>
                                    };

                                    // Sélectionner l'élément conteneur du contenu
                                    const contentContainer = document.getElementById('files-container');

                                    // Fonction pour créer un élément de contenu
                                    function createContentItem(item) {
                                        const div = document.createElement('div');
                                        div.id += 'item-' + item.id;
                                        let nom = item.name;
                                        let filename;
                                        <?php if ($offre->old_id != '') { ?>
                                            filename = nom.substring(0, nom.length - 21);
                                        <?php } else { ?>
                                            filename = nom;
                                        <?php } ?>
                                        div.className += 'col-sm-2 ';
                                        div.className += 'mb-3 ';
                                        div.className += 'content-item';
                                        const itemNameEscaped = item.name.replace(/'/g, "\\'");

                                        div.innerHTML = `
                                <div class="file" onclick="viewItem(${item.id})"></div>
                                <div class="file-title"> ${filename}</div>
                                <div>
                                    <span onclick="viewItem(${item.id})"><img class="view mr-2" title="Visualiser" width="25" src="{{ URL::asset('img/view.png')}}"></span>
                                    <span onclick="downloadItem('${item.id}')"><img class="download mr-2" title="Télecharger" width="25" src="{{ URL::asset('img/download.png')}}"></span>
                                    <span onclick="editItem('${item.id}','${itemNameEscaped}')"><img class="replace mr-2" title="Remplacer" width="28" src="{{ URL::asset('img/edit-file.png')}}"></span>
                                    <span onclick="return confirm('Êtes-vous sûrs ?')?deleteItem('${item.id}'):'';"  ><img class="ml-2 delete" title="Supprimer" width="26" src="{{ URL::asset('img/delete.png')}}"></span>

                                </div>
                                `;
                                        return div;
                                    }

                                    // Générer les éléments de contenu à partir des données de l'API
                                    apiResponseContent.data.forEach(item => {
                                        const contentItem = createContentItem(item);
                                        contentContainer.appendChild(contentItem);
                                    });

                                    // Fonctions pour les boutons
                                    function viewItem(itemId) {
                                        //window.location.href =`https://mysaamp.com/view/${itemId}`;
                                        window.open(`https://crm.mysaamp.com/viewpdf/${itemId}`, '_blank');

                                    }

                                    function editItem(itemId, name) {
                                        //window.location.href =`https://mysaamp.com/view/${itemId}`;
                                        window.open(`https://crm.mysaamp.com/offres/edit_file/${itemId}/<?php echo $offre->id; ?>/${name}`, '_self');
                                    }

                                    function downloadItem(itemId) {
                                        //window.location.href = `downloadItem.php?id=${itemId}`;
                                        window.location.href = `https://crm.mysaamp.com/download/${itemId}`;
                                    }


                                    function deleteItem(itemId) {
                                        var _token = $('input[name="_token"]').val();
                                        $.ajax({
                                            url: `https://crm.mysaamp.com/delete_file/${itemId}`,
                                            method: "get",
                                            //data: {  _token: _token,id:itemId},
                                            success: function(data) {
                                                console.log(data);
                                                if (data == 1) {
                                                    $('#item-' + itemId).hide('slow');
                                                }
                                            }
                                        });
                                    }

                                <?php } ?>
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="modal fade" id="ModalOffres" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 75%;margin: 0 auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center">Ajouter un statut</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="offre" value="{{$offre->id}}" />
                    <label>Statut :</label><br>
                    <select class="form-control mb-2"   id="statut"  onchange="if($(this).val()==3){$('#point_id').show('slow');}else{$('#point_id').hide('slow')}" >
                        <option value="1">L'offre est trop basse</option>
                        <option value="2">L'offre n'est pas adaptée</option>
                        <option value="3">L'offre est validée</option>
                    </select>
                    <label>Détails :</label><br>
                    <textarea class="form-control mb-2" id="details" placeholder="Votre commentaire"></textarea>
                    <div id="point_id" style="display:none">
                        <label>Date de point :</label><br>
                        <input type="text" id="date_point" class="form-control datepicker" id="date_point">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="add_hist()">Créer</button>
                    <!--<button class="btn btn-secondary" type="button" data-dismiss="modal">{{__('msg.Close')}}</button>-->
                </div>

            </div>
        </div>
    </div>

    <script>

        function add_hist() {

        var _token = $('input[name="_token"]').val();
        var offre = $("#offre").val();
        var statut = $("#statut").val();
        var statut_text = $("#statut").find("option:selected").text();
        var details = $("#details").val();
        var date_point = $("#date_point").val();
        var date = "{{date('d/m/Y')}}";

        $.ajax({
            url: "{{ route('add_hist') }}",
            method: "POST",
            async: false,
            data: {
                offre: offre,
                details: details,
                statut: statut,
                date_point: date_point,
                _token: _token
            },
            success: function(data) {
                if (data != '') {
                    var row = '<tr><td>' + date + '</td><td>' + statut_text + ' </td><td>'+details+'</td><td>'+data.date_point+'</td><td></td></tr>';
                    $('#offres').append(row);
                    $('#ModalOffres').modal('hide');
                } else {
                    alert('erreur !')
                }

            }
        });

        }

        function delete_hist(hist) {
            if (!confirm("Êtes vous sûres?")) {
                return false;
            }

            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('delete_hist') }}",
                method: "POST",
                data: {
                    hist: hist,
                    _token: _token
                },
                success: function(data) {
                    if (data == 1)
                        $('#hist-' + hist).hide('slow');
                }
            });
        }

        function relancer() {
            var _token = $('input[name="_token"]').val();
            var offre = $('#offer').val();
            $.ajax({
                //url: `https://crm.mysaamp.com/relancer/${offre}`,
                url: `https://crm.mysaamp.com/relancer`,
                method: "post",
                data: {
                    _token: _token,
                    id: offre
                },
                success: function(data) {
                    console.log(data);
                    if (data == 1) {
                        $.notify({
                            message: 'Message envoyé !',
                            icon: 'glyphicon glyphicon-check'
                        }, {
                            type: 'success',
                            delay: 3000,
                            timer: 1000,
                            placement: {
                                from: "top",
                                align: "right"
                            },
                        });
                        $('#relance').prop('disabled', true);
                    }
                }
            });
        }

        $(function() {

            $(".datepicker").datepicker({

                altField: "#datepicker",
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


        function deleteFile(fileId) {
            if (confirm('Êtes-vous sûrs ?')) {
                fetch(`{{ url('/files') }}/${fileId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert("Failed to delete file.");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("An error occurred while deleting the file.");
                    });
            }
        }
    </script>

    @endsection
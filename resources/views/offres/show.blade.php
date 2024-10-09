@extends('layouts.back')

@section('content')

<?php

?>

<style>

    h6{
        color:black;
        font-weight:bold;
    }
    table{
        border:none;
    }
</style>

<style>
    .foldername{
        width:100%;
        margin-bottom:25px;
    }

    #folders-container {
        display: flex;
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


    .file-title{
        font-weight:normal;
        color:black;
        font-size:13px;
        margin-top:5px;
        margin-bottom:5px;
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
    .download, .view,.replace{
        cursor:pointer;
    }

    .ged{
        border:2px solid #f8f9fd;
    }
</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Offre {{$offre->id}}  -  Client : {{$offre->cl_id}} - {{$offre->nom_compte}}   </h6>
            </div>

            <div class="card-body" style="min-height:300px">

                <form action="{{ route('offres.update', $offre->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Nom_offre">Nom:</label>
                                <h6>{{$offre->Nom_offre}}</h6><!--
                                <input type="text" id="Nom_offre" class="form-control" name="Nom_offre"  value="{{$offre->Nom_offre}}"><br><br>
                                -->
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_creation">Date :</label>
                                <h6>{{date('d/m/Y', strtotime($offre->Date_creation))}}</h6>
                                <!--
                                <input type="text" id="Date_creation" class="form-control datepicker" name="Date_creation"  value="{{$offre->Date_creation}}"><br><br>
                                -->
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="Type">Type:</label>
                            <h6>{{$offre->type}}</h6>
                        </div>

                        <div class="col-md-3">
                            <div >
                                <label for="Produit_Service">Produit Service:</label>
                                <h6>{{$offre->Produit_Service}}</h6>
                            </div>
                        </div>

                    </div>


                    <div class="row pt-1">
                    @if(auth()->user()->id== 1 || auth()->user()->id== 10 || auth()->user()->id== 39)
                        <div class="col-md-4">
                            <div class="">
                                <label for="Statut">Statut:</label>
                                <select    id="statut" class="form-control" name="statut"   >
                                    <option  value=""></option>
                                    <option @selected($offre->statut=='OK') value="OK">OK</option>
                                    <option @selected($offre->statut=='KO')  value="KO">KO</option>
                                </select><br><br>
                            </div>
                        </div>
                    @else
                        <div class="col-md-4">
                            <div class="">
                                <label for="Statut">Statut:</label>
                                <h6>{{$offre->Statut}}</h6>
                            </div>
                        </div>
                    @endif
                        <div class="col-md-4">
                            <div class="">
                                <label for="Description">Description:</label>
                                <h6>{{$offre->Description}}</h6>
                            </div>
                        </div>

                        <div class="col-md-4">
                            @if($offre->fichier!= null)
                                @php $fileNames = unserialize($offre->fichier); @endphp
                                <div class="">
                                    <label for="Description">Fichier(s):</label><br>
                                    <table>

                                        @foreach ($fileNames as $fichier)
                                        <tr>
                                            <td><label><b class="black mr-2">{{$fichier}}</b></label></td>
                                            <td><a href="https://crm.mysaamp.com/offres/{{$fichier}}" target="_blank" ><img class="view mr-2" title="Visualiser" width="30" src="{{ URL::asset('img/view.png')}}"></a></td>
                                            <td><a href="https://crm.mysaamp.com/offres/{{$fichier}}" download ><img class="download mr-2" title="Télecharger" width="30" src="{{ URL::asset('img/download.png')}}"></a></td>
                                        </tr>
                                        @endforeach

                                    </table>
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">
                            @if($offre->statut=='')
                                <button type="submit" class="btn-primary btn float-right">Modifier</button>
                            @endif
                            @if(auth()->user()->role=='admin' || auth()->user()->id== 10 || auth()->user()->id== 39)
                                <a title="Supprimer" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('offres.destroy', $offre->id )}}" class="btn btn-danger btn-sm btn-responsive mr-2 float-right" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                    <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                                </a>
                            @endif
                        </div>
                    </div>

                </form>

            </div>

            <h3 class="pl-5">Documents </h3>
			<div class="ged pl-5 pr-5 pb-5 pt-3">


			    <div class="pl-5" id="folders-container"></div>
                <div class="row pl-5" id="files-container"></div>

                <script>
                     <?php
                    if(isset($folderContent)){ ?>
                        const apiResponseContent = {
                            data: <?php echo json_encode($folderContent); ?>
                        };

                        // Sélectionner l'élément conteneur du contenu
                        const contentContainer = document.getElementById('files-container');

                        // Fonction pour créer un élément de contenu
                        function createContentItem(item) {
                            const div = document.createElement('div');
                            let nom= item.name;
                            let filename ;
                            <?php if($offre->old_id!=''){?>
                             filename = nom.substring(0, nom.length-21);
                            <?php }else{?>
                             filename=nom ;
                            <?php } ?>
                            div.className += 'col-sm-2 ';
                            div.className += 'mb-3 ';
                            div.className += 'content-item';
                            div.innerHTML = `
                                <div class="file" onclick="viewItem(${item.id})"></div>
                                <div class="file-title"> ${filename}</div>
                                <div>
                                    <span onclick="viewItem(${item.id})"><img class="view mr-2" title="Visualiser" width="25" src="{{ URL::asset('img/view.png')}}"></span>
                                    <span onclick="downloadItem('${item.id}')"><img class="download mr-2" title="Télecharger" width="25" src="{{ URL::asset('img/download.png')}}"></span>
                                    <span onclick="editItem('${item.id}','${item.name}')"><img class="replace" title="Remplacer" width="28" src="{{ URL::asset('img/edit-file.png')}}"></span>
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

                        function editItem(itemId,name) {
                            //window.location.href =`https://mysaamp.com/view/${itemId}`;
                            window.open(`https://crm.mysaamp.com/offres/edit_file/${itemId}/<?php echo $offre->id;?>/${name}`, '_self');
                        }

                        function downloadItem(itemId) {
                            //window.location.href = `downloadItem.php?id=${itemId}`;
                            window.location.href =`https://crm.mysaamp.com/download/${itemId}`;
                        }
                    <?php } ?>
                </script>

			</div>

        </div>

    </div>
    <script>
        $(function () {

            $( ".datepicker" ).datepicker({

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
                minDate:0
            });
        });

    </script>

    @endsection
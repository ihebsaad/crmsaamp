@extends('layouts.back')
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>

@section('content')

<?php

?>
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
        font-size: 13px;
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
    .download, .view, .replace .delete{
        cursor:pointer;
    }
</style>
<div class="row">

    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Customer folder {{$client->Nom}} - {{$client->cl_ident}}</h6>
            </div>
            <div class="card-body" style="min-height:300px">
            <h5 class="black">{{__('msg.Add documents')}}</h5>
                <form action="{{route('ouverture')}}" method="post" enctype="multipart/form-data" style="margin:30px 0px 50px 50px">
                    {{ csrf_field() }}
                    <input   type="hidden"   name="id" value="{{$client->id}}"  required>
                    <input   type="hidden"   name="cl_ident" value="{{$client->cl_ident}}"  required>

                    <div class="row pt-1">
                        <div class="col-md-4">
                            <label for="files">{{__('msg.Select files (PDF only)')}}</label>
                            <input class="form-control" type="file" id="files" name="files[]" multiple required accept="application/pdf"><br>
                            <label class="text-danger">{{__('msg.(Maximum 5 files per folder)')}}</label>
                        </div>
                        @php
                            $folderNames = is_array($folders) && !empty($folders) ? array_column($folders, 'name') : [];
                        @endphp
                        <div class="col-md-5">
                            <label>{{__('msg.Type of deposit')}}:</label>
                            <select class="form-control" name="type">
                                @foreach([
                                    1 => "DOCUMENTS OUVERTURE DE COMPTE POIDS",
                                    2 => "PRINCIPES ET CODE DES PRATIQUES DU RJC ET DE SAAMP",
                                    3 => "DECLARATION : DUE DILIGENCE",
                                    4 => "CNI OU PASSEPORT",
                                    5 => "KBIS DE MOINS DE 3 MOIS OU REPERTOIRE DES METIERS",
                                    6 => "DECLARATION DEXISTENCE AUPRES DE LA GARANTIE",
                                    7 => "LETTRE DE FUSION",
                                    8 => "RIB",
                                    9 => "AEX",
                                    10 => "AUTORISATION DE DECLARATION EN DOUANE",
                                    11 => "QUALITE",
                                    12 => "ENQUETE(COMPLIANCE)",
                                    13 => "CONVENTION OCA",
                                    14 => "DIVERS DOCUMENTS",
                                ] as $value => $label)
                                    @if(!in_array($label, $folderNames) || $value==11 || 1 )
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn-primary btn mt-4 ml-5">{{__('msg.Add')}}</button>
                            </div>
                    </div>

                </form>

                @if(isset($folders) && isset($folders[0]))

                <h5 class="black">{{__('msg.My folder')}}</h5>

                @endif

                @if(isset($folderName) && $files)
                    <!--<h3 class="foldername ml-3"><img  width="45" src="{{ URL::asset('img/open-folder.png')}}"> {{$folderName}}</h3><br>-->
                @endif



                <div class="pl-5" id="folders-container"></div>
                <div class="row pl-5" id="files-container"></div>

                <script>
                    <?php if(isset($folders) && $files==false){ ?>

                    const apiResponse = {
                        data: <?php echo json_encode($folders); ?>
                    };

                    // Sélectionner l'élément conteneur des boutons
                    const buttonsContainer = document.getElementById('folders-container');

                    // Fonction pour créer un bouton avec redirection
                    function createButton(id, name,pathList) {
                        const button = document.createElement('button');

                        const pathIds = pathList.split(',');
                        const lastPathId = pathIds[pathIds.length - 1];

                        var count=26;
                        var result = name.slice(0, count) + (name.length > count ? "..." : "");
                        button.textContent = result;
                        button.className += 'folder-btn';
                        button.onclick = function() {
                           <?php if(isset($folderName)){ ?>
                            window.location.href = `https://crm.mysaamp.com/folders/${id}/${encodeURIComponent(name)}/${lastPathId}/<?php echo $client->id?>`;
                            <?php }else{ ?>
                            window.location.href = `https://crm.mysaamp.com/folders/${id}/${encodeURIComponent(name)}/${lastPathId}/<?php echo $client->id?>`;
                            <?php } ?>

                        };
                        return button;
                    }
                    // Générer les boutons à partir des données de l'API
                    apiResponse.data.forEach(item => {
                        const button = createButton(item.id, item.name,item.virtualPathIdList);
                        buttonsContainer.appendChild(button);
                    });


                    <?php }
                    if(isset($folderContent)){ ?>
                        const apiResponseContent = {
                            data: <?php echo json_encode($folderContent); ?>
                        };

                        // Sélectionner l'élément conteneur du contenu
                        const contentContainer = document.getElementById('files-container');

                        // Fonction pour créer un élément de contenu
                        function createContentItem(item) {
                            const div = document.createElement('div');
                            div.id += 'item-'+item.id;
                            div.className += 'col-sm-2 ';
                            div.className += 'mb-3 ';
                            div.className += 'content-item';
                            div.innerHTML = `
                                <div class="file" onclick="viewItem(${item.id})"></div>
                                <div class="file-title"> ${item.name}</div>
                                <div>
                                    <span onclick="viewItem(${item.id})"><img class="view mr-2" title="Visualiser" width="25" src="{{ URL::asset('img/view.png')}}"></span>
                                    <span onclick="downloadItem('${item.id}')"><img class="download" title="Télecharger" width="25" src="{{ URL::asset('img/download.png')}}"></span>
                                    <span onclick="editItem('${item.id}','${itemNameEscaped}')"><img class="replace mr-2" title="Remplacer" width="28" src="{{ URL::asset('img/edit-file.png')}}"></span>
                                    <span onclick="return confirm('Êtes-vous sûrs ?')?deleteItem('${item.id}'):'';"  ><img class="delete ml-2" title="Supprimer" width="26" src="{{ URL::asset('img/delete.png')}}"></span>
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
                            window.open(`https://crm.mysaamp.com/edit_file/${itemId}/<?php echo $client_id;?>/${name}`, '_self');
                        }

                        function downloadItem(itemId) {
                            //window.location.href = `downloadItem.php?id=${itemId}`;
                            window.location.href =`https://crm.mysaamp.com/download/${itemId}`;
                        }

                        function deleteItem(itemId) {
                            var _token = $('input[name="_token"]').val();
                            $.ajax({
                                url: `https://crm.mysaamp.com/delete_file/${itemId}`,
                                method: "get",
                                //data: {  _token: _token,id:itemId},
                                success:function(data){
                                    console.log(data);
                                    if(data==1){
                                        $('#item-'+itemId).hide('slow');
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

@endsection
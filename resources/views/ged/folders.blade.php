@extends('layouts.back')

@section('content')
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>

<?php

?>
<style>
    .foldername{
        width:100%;
        margin-bottom:25px;
    }

    #folders-container {
        display: flex;
        flex-wrap:wrap;
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
    .download, .view{
        cursor:pointer;
    }
</style>
<div class="row">

    <div class="col-lg-12 mb-4">

        <div class="card shadow mb-4" style="min-height:80vh">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Electronic document management')}}</h6>
            </div>
            <div class="card-body">
                @if(isset($folders) && isset($folders[0]))
                    <nav aria-label="breadcrumb" style="width:100%">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('folders') }}"><img  width="30" src="{{ URL::asset('img/shared-folder.png')}}"> {{__('msg.My documents')}}</a>
                        </li>
                        @php
                            $pathComponents = explode('/', $folders[0]['virtualPath']);
					        $idComponents = explode(',', $folders[0]['virtualPathIdList']);
                            $i=0;
                        @endphp
                        @foreach($pathComponents as $index => $component)
                            @php
                                $id = $idComponents[$index];
                                $i++;
                            @endphp
                            @php if($i>2){
                                $parent = $idComponents[$index-1];

                                @endphp
                                <li class="breadcrumb-item">
                                    <a href="{{ route('folderContent', ['id' => $id, 'name' => $component,'parent'=>$parent,'client_id'=>$client->id]) }}"><img  width="30" @if($id==$folderId) src="{{ URL::asset('img/open-folder.png')}}"   @else src="{{ URL::asset('img/folder.png')}}" @endif > {{ $component }}</a>
                                </li>
                            @php } @endphp
                        @endforeach
                        @if($files)
                        <li class="breadcrumb-item">
                            <span ><img  width="30" src="{{ URL::asset('img/open-folder.png')}}"> {{ $folderName }}</span>
                        </li>
                        @endif

                    </ol>
                </nav>
                @endif

                @if(isset($folderName) && $files)
                    <!--<h3 class="foldername ml-3"><img  width="45" src="{{ URL::asset('img/open-folder.png')}}"> {{$folderName}}</h3><br>-->
                @endif



                <div class="row pl-5" id="folders-container"></div>
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

                        button.textContent = name;
                        button.className += 'folder-btn col-md-2 col-sm-6';
                        button.onclick = function() {
                           <?php if(isset($folderName)){ ?>
                            window.location.href = `https://crm.mysaamp.com/folders/${id}/${encodeURIComponent(name)}/${lastPathId}`;
                            <?php }else{ ?>
                            window.location.href = `https://crm.mysaamp.com/folders/${id}/${encodeURIComponent(name)}/${lastPathId}`;
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
                            div.className += 'col-sm-2 ';
                            div.className += 'mb-3 ';
                            div.className += 'content-item';
                            div.innerHTML = `
                                <div class="file" onclick="viewItem(${item.id})"></div>
                                <div class="file-title"> ${item.name}</div>
                                <div>
                                    <span onclick="viewItem(${item.id})"><img class="view mr-2" title="Visualiser" width="25" src="{{ URL::asset('img/view.png')}}"></span>
                                    <span onclick="downloadItem('${item.id}')"><img class="download" title="Télecharger" width="25" src="{{ URL::asset('img/download.png')}}"></span>
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

                        function downloadItem(itemId) {
                            //window.location.href = `downloadItem.php?id=${itemId}`;
                            window.location.href =`https://crm.mysaamp.com/download/${itemId}`;
                        }
                    <?php } ?>
                </script>

            </div>
        </div>



    </div>

</div>

@endsection
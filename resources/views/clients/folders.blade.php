@extends('layouts.back')

@section('content')
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>

<?php
$links = isset($folderContent['links']) ? $folderContent['links'] : [];
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
    .download, .view,.replace,.delete{
        cursor:pointer;
    }
        /* Styles pour la pagination */
        .pagination {
            margin-top: 30px;
        }
        .pagination a, .pagination span {
            display: inline-block;
            margin: 0 5px;
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
        }
        .pagination span.current {
            background: #3498db;
            color: #fff;
            border-color: #3498db;
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
                            <a href="{{route('compte_client.folder',['id'=>$client_id])}}"><img  width="30" src="{{ URL::asset('img/shared-folder.png')}}"> Mon dossier</a>
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
                                    <a href="{{ route('folderContent', ['id' => $id, 'name' => $component,'parent'=>$parent,'client_id'=>$client_id]) }}"><img  width="30" @if($id==$folderId) src="{{ URL::asset('img/open-folder.png')}}"   @else src="{{ URL::asset('img/folder.png')}}" @endif > {{ $component }}</a>
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

                @if(isset($folderId) )
                    @if(auth()->user()->user_role==1 || auth()->user()->user_role==2 || auth()->user()->user_role==5 )
                    <div class="row">
                        <div class="col-md-12 float-right text-right ">
                            <span style="cursor:pointer" onclick="return confirm('Êtes-vous sûrs ?')?deleteFolder('{{$folderId}}'):'';"  ><img class="mb-2 mt-2" src="{{ URL::asset('img/delete-folder.png')}}" width="50" title="Supprimer le dossier" style="opacity:0.4"  /></span>
                        </div>
                    </div>
                    @endif
                @endif

                <div class="pl-5" id="folders-container"></div>
                    <form class="search-form" method="get" action="">
                        <input type="hidden" name="id" value="<?php echo $folderId; ?>">
                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($folderName); ?>">
                        <div class="row mb-3 ml-2 ">
                            <!-- Champ de recherche pour le numéro de lot -->
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher par numéro" value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="month" class="form-control">
                                    <option value="">-- Choisir un mois --</option>
                                    <option value="01" <?php if($month=="01") echo "selected"; ?>>Janvier</option>
                                    <option value="02" <?php if($month=="02") echo "selected"; ?>>Février</option>
                                    <option value="03" <?php if($month=="03") echo "selected"; ?>>Mars</option>
                                    <option value="04" <?php if($month=="04") echo "selected"; ?>>Avril</option>
                                    <option value="05" <?php if($month=="05") echo "selected"; ?>>Mai</option>
                                    <option value="06" <?php if($month=="06") echo "selected"; ?>>Juin</option>
                                    <option value="07" <?php if($month=="07") echo "selected"; ?>>Juillet</option>
                                    <option value="08" <?php if($month=="08") echo "selected"; ?>>Août</option>
                                    <option value="09" <?php if($month=="09") echo "selected"; ?>>Septembre</option>
                                    <option value="10" <?php if($month=="10") echo "selected"; ?>>Octobre</option>
                                    <option value="11" <?php if($month=="11") echo "selected"; ?>>Novembre</option>
                                    <option value="12" <?php if($month=="12") echo "selected"; ?>>Décembre</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="submit" class="btn btn-success" value="Rechercher">
                            </div>
                        </div>
                    </form>
                <div class="row pl-5" id="files-container"></div>

                <div class="pagination">
                    <?php
                    // Détermine si une recherche est active
                    $searchActive = ($search !== '' || $month !== '');

                    if ($searchActive) {
                        for ($i = 1; $i <= $folderContent['totalPages']; $i++) {
                            if ($i == $page) {
                                echo '<span class="current">' . $i . '</span>';
                            } else {
                                echo '<a href="?id=' . $folderId . '&name=' . urlencode($folderName) . '&page=' . $i . '&search=' . urlencode($search) . '&month=' . urlencode($month) . '">' . $i . '</a>';
                            }
                        }
                    } else {
                        if (count($links) > 0) {
                            foreach ($links as $link) {
                                if ($link == $page) {
                                    echo '<span class="current">' . $link . '</span>';
                                } else {
                                    echo '<a href="?id=' . $folderId . '&name=' . urlencode($folderName) . '&page=' . $link . '">' . $link . '</a>';
                                }
                            }
                        }
                    }
                    ?>
                </div>

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
                        button.className += 'folder-btn';
                        button.onclick = function() {
                           <?php if(isset($folderName)){ ?>
                            window.location.href = `https://crm.mysaamp.com/folders/${id}/${encodeURIComponent(name)}/${lastPathId}/<?php echo $client_id?>`;
                            <?php }else{ ?>
                            window.location.href = `https://crm.mysaamp.com/folders/${id}/${encodeURIComponent(name)}/${lastPathId}/<?php echo $client_id?>`;
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
                            data: <?php echo json_encode($folderContent['data']); ?>
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
                            const itemNameEscaped = item.name.replace(/'/g, "\\'");
                            <?php
                            if(auth()->user()->user_role==1 || auth()->user()->user_role==2 || auth()->user()->user_role==5 ){
                            ?>
                            div.innerHTML = `
                                <div class="file" onclick="viewItem(${item.id})"></div>
                                <div class="file-title"> ${item.name}</div>
                                <div>
                                    <span onclick="viewItem(${item.id})"><img class="view mr-2" title="Visualiser" width="25" src="{{ URL::asset('img/view.png')}}"></span>
                                    <span onclick="downloadItem('${item.id}')"><img class="download mr-2" title="Télecharger" width="25" src="{{ URL::asset('img/download.png')}}"></span>
                                    <span onclick="editItem('${item.id}','${itemNameEscaped}')"><img class="replace mr-2" title="Remplacer" width="28" src="{{ URL::asset('img/edit-file.png')}}"></span>
                                    <span onclick="return confirm('Êtes-vous sûrs ?')?deleteItem('${item.id}'):'';"  ><img class="ml-2 delete" title="Supprimer" width="26" src="{{ URL::asset('img/delete.png')}}"></span>
                                    </div>
                                `;
                            <?php
                            }else{
                            ?>
                            div.innerHTML = `
                                <div class="file" onclick="viewItem(${item.id})"></div>
                                <div class="file-title"> ${item.name}</div>
                                <div>
                                    <span onclick="viewItem(${item.id})"><img class="view mr-2" title="Visualiser" width="25" src="{{ URL::asset('img/view.png')}}"></span>
                                    <span onclick="downloadItem('${item.id}')"><img class="download mr-2" title="Télecharger" width="25" src="{{ URL::asset('img/download.png')}}"></span>
                                    </div>
                                `;
                                <?php
                            }
                            ?>
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


                        function deleteFolder(itemId) {

                            var _token = $('input[name="_token"]').val();
                            $.ajax({
                                url: `https://crm.mysaamp.com/delete_folder/${itemId}`,
                                method: "get",
                                //data: {  _token: _token,id:itemId},
                                success:function(data){
                                    console.log(data);
                                    if(data==1){
                                        $.notify({
                                            message: 'Dossier supprimé !',
                                            icon: 'glyphicon glyphicon-check'
                                        }, {
                                            type: 'success',
                                            delay: 2000,
                                            timer: 1000,
                                            placement: {
                                                from: "top",
                                                align: "right"
                                            },
                                        });

                                        setTimeout(function (){
                                            window.location.href =`https://crm.mysaamp.com/clients/folder/<?php echo $id;?>`;
                                        }, 3000);

                                    }else{
                                        alert('erreur lors de suppression du dossier');
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
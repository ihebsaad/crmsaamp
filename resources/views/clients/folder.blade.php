@extends('layouts.back')
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>

@section('content')

<?php

?>
<style>
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
        font-size: 13px;
        background-color: transparent;
        text-align: center;
        padding-top: 50px;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        font-family: 'Roboto';
        font-weight: bold;
    }

    .title-btn {
        background-color: transparent;
        font-size: 12px;
        border: none;
        max-width: 120px;

    }

    .expiration-date {
        position: absolute;
        bottom: -20px;
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
    .replace .delete {
        cursor: pointer;
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
                    <input type="hidden" name="id" value="{{$client->id}}" required>
                    <input type="hidden" name="cl_ident" value="{{$client->cl_ident}}" required>

                    <div class="row pt-1">
                        <div class="col-md-4">
                            <label for="files">{{__('msg.Select files')}}</label>
                            <input class="form-control" type="file" id="files" name="files[]" multiple required accept="image/jpeg,image/gif,image/png,application/pdf"><br>
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



                <div class="row pl-5" id="folders-container"></div>
                <div class="row pl-5" id="files-container"></div>
                <div class="legend" style="margin-bottom: 15px; text-align: center;">
                        <span class="legend-item" style="margin-right: 15px;">
                            <span class="legend-color" style="background-color: #1cc88a; display:inline-block; width: 15px; height: 15px; margin-right: 5px; border-radius: 3px;"></span>
                            Valide
                        </span>
                        <span class="legend-item" style="margin-right: 15px;">
                            <span class="legend-color" style="background-color: orange; display:inline-block; width: 15px; height: 15px; margin-right: 5px; border-radius: 3px;"></span>
                            Proche expiration (< 2 mois) </span>
                                <span class="legend-item">
                                    <span class="legend-color" style="background-color: #e74a3b; display:inline-block; width: 15px; height: 15px; margin-right: 5px; border-radius: 3px;"></span>
                                    Expiré
                                </span>
                    </div>
                <script>
                    <?php if (isset($folders) && $files == false) { ?>

                        const expirationDates = <?php echo json_encode($expDates); ?>;

                        const apiResponse = {
                            data: <?php echo json_encode($folders); ?>
                        };

                        // Sélectionner l'élément conteneur des boutons
                        const buttonsContainer = document.getElementById('folders-container');

                        function getExpirationDate(folderName) {
                            const lowerName = folderName.toLowerCase();
                            if (lowerName.includes("demande")) return expirationDates["DOCUMENTS OUVERTURE DE COMPTE POIDS"];
                            if (lowerName.includes("principes") || lowerName.includes("code")) return expirationDates["PRINCIPES ET CODE DES PRATIQUES DU RJC ET DE SAAMP"];
                            if (lowerName.includes("due diligence")) return expirationDates["DECLARATION DUE DILIGENCE"];
                            if (lowerName.includes("cni") || lowerName.includes("passeport")) return expirationDates["CNI OU PASSEPORT"];
                            if (lowerName.includes("kbis") || lowerName.includes("répertoire")) return expirationDates["KBIS DE MOINS DE 3 MOIS OU REPERTOIRE DES METIERS"];
                            if (lowerName.includes("existence") || lowerName.includes("garantie")) return expirationDates["DECLARATION D'EXISTENCE AUPRES DE LA GARANTIE"];
                            if (lowerName.includes("fusion")) return expirationDates["LETTRE DE FUSION"];
                            if (lowerName.includes("rib")) return expirationDates["RIB"];
                            // Convention OCA n'a pas de date
                            return null;
                        }
                        /*
                                                // Fonction pour créer un bouton avec redirection
                                                function createButton(id, name, pathList) {
                                                    const button = document.createElement('button');

                                                    const pathIds = pathList.split(',');
                                                    const lastPathId = pathIds[pathIds.length - 1];

                                                    var count = 26;
                                                    var result = name.slice(0, count) + (name.length > count ? "..." : "");
                                                    button.textContent = result;
                                                    button.className += 'folder-btn';
                                                    button.onclick = function() {
                                                        <?php if (isset($folderName)) { ?>
                                                            window.location.href = `https://crm.mysaamp.com/folders/${id}/${encodeURIComponent(name)}/${lastPathId}/<?php echo $client->id ?>`;
                                                        <?php } else { ?>
                                                            window.location.href = `https://crm.mysaamp.com/folders/${id}/${encodeURIComponent(name)}/${lastPathId}/<?php echo $client->id ?>`;
                                                        <?php } ?>

                                                    };
                                                    return button;
                                                }
                        */
                        function createButton(id, name, pathList) {
                            // Créer le conteneur de la carte
                            const buttonContainer = document.createElement('div');
                            buttonContainer.className = 'folder-btn col-lg-2 col-md-4 col-sm-6 d-flex flex-column align-items-center';
                            buttonContainer.style.cursor = 'pointer'; // Change le curseur pour indiquer que c'est cliquable

                            // Ajouter l'événement onclick au conteneur
                            buttonContainer.onclick = function() {
                                const pathIds = pathList.split(',');
                                const lastPathId = pathIds[pathIds.length - 1];
                                window.location.href = `https://crm.mysaamp.com/folders/${id}/${encodeURIComponent(name)}/${lastPathId}/<?php echo $client->id ?>`;
                            };

                            // Créer le bouton (titre)
                            const button = document.createElement('button');
                            button.textContent = name;
                            button.className = 'title-btn';

                            // Ajouter le bouton au conteneur
                            buttonContainer.appendChild(button);

                            // Utiliser la fonction de mapping pour obtenir la date d'expiration
                            const expDate = getExpirationDate(name);
                            if (expDate) {
                                const expDiv = document.createElement('div');
                                expDiv.className = 'expiration-date mt-2 p-1 rounded text-white';
                                expDiv.textContent = "Exp : " + expDate;

                                // Conversion de la date "JJ/MM/YYYY" en objet Date
                                const parts = expDate.split("/");
                                const day = parseInt(parts[0]);
                                const month = parseInt(parts[1]) - 1; // Mois en JS => 0-indexé
                                const year = parseInt(parts[2]);
                                const expirationDateObj = new Date(year, month, day);
                                const currentDate = new Date();
                                const diff = expirationDateObj - currentDate;
                                const twoMonthsInMs = 60 * 24 * 3600 * 1000; // J => millisecondes

                                if (diff < 0) {
                                    expDiv.style.backgroundColor = '#e74a3b';
                                } else if (diff < twoMonthsInMs) {
                                    expDiv.style.backgroundColor = 'orange';
                                } else {
                                    expDiv.style.backgroundColor = '#1cc88a';
                                }

                                // Ajouter la date d'expiration au conteneur
                                buttonContainer.appendChild(expDiv);
                                console.log(expDiv);
                            }

                            return buttonContainer;
                        }
                        // Générer les boutons à partir des données de l'API
                        apiResponse.data.forEach(item => {
                            const button = createButton(item.id, item.name, item.virtualPathIdList);
                            buttonsContainer.appendChild(button);
                        });


                    <?php }
                    if (isset($folderContent)) { ?>
                        const apiResponseContent = {
                            data: <?php echo json_encode($folderContent['data']); ?>
                        };

                        // Sélectionner l'élément conteneur du contenu
                        const contentContainer = document.getElementById('files-container');

                        // Fonction pour créer un élément de contenu
                        function createContentItem(item) {
                            const div = document.createElement('div');
                            div.id += 'item-' + item.id;
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

                        function editItem(itemId, name) {
                            //window.location.href =`https://mysaamp.com/view/${itemId}`;
                            window.open(`https://crm.mysaamp.com/edit_file/${itemId}/<?php echo $client_id; ?>/${name}`, '_self');
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

@endsection
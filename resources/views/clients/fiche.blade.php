@extends('layouts.back')

@section('content')

<?php

// Traitement d'un appel
if (isset($_GET['call'])) {
    $number = $_GET['number'];
    $autoanswer = isset($_GET['autoanswer']) ? 'false' : 'true';

    $callUrl = "https://api.telavox.se/dial/{$number}?autoanswer={$autoanswer}";

    $callCh = curl_init($callUrl);
    curl_setopt($callCh, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $client->token_phone));
    curl_setopt($callCh, CURLOPT_RETURNTRANSFER, true);

    $callResponse = curl_exec($callCh);
    $callResult = json_decode($callResponse, true);
    curl_close($callCh);

    if ($callResult && $callResult['message'] === 'OK') {
        echo '<p class="text-success">Appel initié avec succès !</p>';
    } else {
        echo '<p class="text-danger">Échec de l\'initiation de l\'appel.</p>';
    }
}
/*
function compare_func($a, $b)
{
    // CONVERT $a AND $b to DATE AND TIME using strtotime() function
    $t1 = strtotime($a->date_cmde);
    $t2 = strtotime($b->date_cmde);

    return ($t2 - $t1);
}

if (is_array($commandes) || is_object($commandes)) {
    usort($commandes, "compare_func");
}
*/
?>


<style>
    h6,#stats {
        color: black;
        font-weight: bold;
    }

</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Fiche du client {{$client->id}} - {{$client->Nom}} - {{$client->cl_ident}} </h6>
            </div>
            <div class="card-body">
            <a href="{{route('rendezvous.create',['id'=>$client->id])}}" class="btn btn-primary mb-3 mr-3 float-left"><i class="fas fa-calendar-day"></i> Rendez-vous</a><a href="{{route('taches.create',['id'=>$client->id])}}" class="btn btn-primary mb-3 mr-3 float-left"><i class="fas fa-tasks"></i> Prise de Contact</a> <a href="{{route('offres.client_list',['id'=>$client->id])}}" class="btn btn-primary mb-3 mr-3 float-left"><i class="fas fa-gift"></i> Offres</a> @if($client->Client_Prospect!='CLIENT SAAMP') <!--<a href="{{route('compte_client.show',['id'=>$client->id])}}" class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-user-edit"></i> Modifier</a>--> @endif @if($client->cl_ident > 0 )<a href="{{route('compte_client.folder',['id'=>$client->id])}}" class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-folder"></i> Mon Dossier</a> @endif <a href="{{route('finances',['id'=>$client->id])}}" class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-money-bill-wave"></i> Finances</a>
                <div class="clearfix"></div>
                <form id="">
                    <div class="row pt-1">
                        <div class="col-md-2">
                            <div class="">
                                <label for="Nom">Nom:</label>
                                <h6>{{$client->Nom}}</h6>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="postalCode">CP:</label>
                                <h6>{{$client->postalCode}}</h6>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="BillingAddress_city">Ville:</label>
                                <h6>{{$client->ville}}</h6>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="Pays">Pays:</label>
                                <h6>{{$client->pays_code}}</h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="Rue">Adresse:</label>
                                <h6>{{$client->adresse1}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">

                        <div class="col-md-2">
                            <div class="">
                                <label for="agence">Agence:</label>
                                <h6>{{$agence_name}}</h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Commercial">Commercial:</label>
                                <h6>{{$client->commercial}}</h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Commercial">Commercial support:</label>
                                <h6>{{$client->commercial_support}}</h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="ADV">ADV:</label>
                                <h6>{{$client->ADV}}</h6>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-2">
                            <div class="">
                                <label for="Client_Prospect">Clientèle:</label>
                                <h6>{{$client->Fidelite_du_client_c}}</h6>
                            </div>
                        </div>-->

                        <div class="col-md-2">
                            <div class="">
                                <label for="Client_Prospect">Activité:</label>
                                <h6>{{$client->activite}} - {{$client->sous_activite}} </h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Client_Prospect">Type:</label>
                                <h6>{{$client->Client_Prospect}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-2">
                            <div class="">
                                <label for="Phone">Télephone:</label>
                                <h6>{{$client->Phone}}</h6>
                            </div>

                        </div>
                        <!--
                        <div class="col-md-1 pt-4">
                            <form method="get" class="phone-form">
                                <input type="hidden" id="number" name="number" value="{{str_replace(' ', '', $client->Phone)}}">
                                <button type="submit" name="call" class="btn btn-success" title="Appeler"><i class="fa-2x mt-2 fas fa-phone-square"></i></button>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="">Email:</label>
                                <h6>{{$client->email}}</h6>
                            </div>
                        </div>
                        -->
                        <div class="col-md-2">
                            <div class="">
                                <label for="">Site Web:</label>
                                <h6>{{$client->url}}</h6>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-4">
                            <button type="submit" class="btn-primary btn">Ajouter</button>
                        </div>
                        -->
                    </div>

                </form>


            </div>
        </div>
    </div>

    <div class="col-lg-5 col-sm-12 mb-4">

        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statistiques </h6>
            </div>

            <div class="card-body" style="min-height:400px">
                <div class="table-container">
                    <input id="mois" type="checkbox" value="1" onchange="show_stats('{{$client->cl_ident}}')"  >
                    <label class="" for="mois">Afficher les années pleines</label>
                    </input>
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="">Métier</th>
                                <th class="">{{ date('Y')-3; }}</th>
                                <th class="">{{ date('Y')-2; }}</th>
                                <th class="">{{ date('Y')-1; }}</th>
                                <th class="">{{ date('Y'); }}</th>
                            </tr>
                        </thead>
                        <tbody id="stats">
                            <?php if (is_array($stats) || is_object($stats)) {     ?>
                                @foreach($stats as $stat)
                                <tr>
                                    <td>{{$stat->metier}}</td>
                                    <td>{{$stat->N_3}}</td>
                                    <td>{{$stat->N_2}}</td>
                                    <td>{{$stat->N_1}}</td>
                                    <td>{{$stat->N}}</td>
                                </tr>
                                @endforeach
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


    <div class="col-lg-7 col-sm-12 mb-4">

        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Commandes en cours </h6>
            </div>

            <div class="card-body" style="min-height:400px;margin-left:25px">

                <a style="float:right;right:20px;background-color:#e6d685;color:black;font-weight:bold;padding:5px 10px 5px 10px;margin-top:15px;margin-bottom:15px;border-radius:10px;" href="#" data-toggle="modal" data-target="#Modal1">{{__('msg.Complete list')}}</a>
                <div class="clearfix"></div>

                <?php $i = 0;
                if (is_array($commandes) || is_object($commandes)) {  ?>
                    @foreach($commandes as $cmd)
                    <?php
                    $etat = trim(strtoupper($cmd->etat));
                    if ($etat == 'ENCOURS' || $etat == 'EN COURS') {
                        $i++;
                        if ($i < 4) {
                    ?>
                            <span style="color:lightgrey;font-weight:bold;"><?php echo  date('d/m/Y', strtotime($cmd->date_cmde)); ?></span>
                            <div class="row   pl-30" style="padding-bottom:3px;;margin-top:-4px">
                                <div class="col-md-4" style="border-left:2px solid #e6d685">
                                    <b style="color:black;">{{__('msg.Type')}}:</b> <?php echo $cmd->type_cmde; ?><div class="clearfix"></div>
                                    <b style="color:black;">{{__('msg.Qty')}}:</b> <?php echo $cmd->qte; ?>
                                </div>
                                <div class="col-md-4" style="border-left:2px solid #e6d685">
                                    <b style="color:black;">{{__('msg.Weight')}}:</b> <?php echo $cmd->poids; ?> g<div class="clearfix"></div>
                                    <b style="color:black;">{{__('msg.Labour cost')}}:</b> <?php if ($cmd->facon > 0) {
                                                                                                echo $cmd->facon . ' €';
                                                                                            } ?>
                                </div>
                                <div class="col-md-2">
                                    <?php
                                    if (trim(strtoupper($cmd->type_cmde)) == 'AFFINAGE') {
                                        $lien = URL("commande/" . $cmd->id);
                                    }
                                    if (trim(strtoupper($cmd->type_cmde)) == 'PRODUIT') {
                                        $lien = URL("commandeprod/" . $cmd->id);
                                    }
                                    if (trim(strtoupper($cmd->type_cmde)) == 'LABORATOIRE') {
                                        $lien = URL("commandelab/" . $cmd->id);
                                    }
                                    if (trim(strtoupper($cmd->type_cmde)) == 'RACHAT METAUX') {
                                        $lien = URL("commandermp/" . $cmd->id);
                                    }
                                    ?>
                                    <small><a href="<?php echo $lien; ?>">{{__('msg.More details')}}</a></small>
                                </div>
                            </div>


                    <?php }
                    }  ?>
                    @endforeach
                <?php } ?>


                <div class="modal fade" id="Modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="width: 75%;margin: 0 auto;">
                        <div class="modal-content" >
                            <div class="modal-header">
                                <h5 class="modal-title text-center">Commandes en cours</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="table-container">
                                    <table class="table table-bordered table-striped mb-40" style="width:100%">
                                        <thead>
                                            <tr id="headtable">
                                                <th class="text-center">ID</th>
                                                <th class="text-center">{{__('msg.Date')}}</th>
                                                <th class="text-center">{{__('msg.Qty')}}</th>
                                                <th class="text-center">{{__('msg.Weight')}}</th>
                                                <th class="text-center hidemobile">{{__('msg.Labour cost')}}</th>
                                                <th class="text-center hidemobile">{{__('msg.Type')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (is_array($commandes) || is_object($commandes)) {     ?>
                                                @foreach($commandes as $cmd)
                                                <?php
                                                $etat = trim(strtoupper($cmd->etat));
                                                if ($etat == 'ENCOURS' || $etat == 'EN COURS') { ?>
                                                    <?php
                                                    if (trim(strtoupper($cmd->type_cmde)) == 'AFFINAGE') {
                                                        $lien = URL("commande/" . $cmd->id);
                                                    }
                                                    if (trim(strtoupper($cmd->type_cmde)) == 'PRODUIT') {
                                                        $lien = URL("commandeprod/" . $cmd->id);
                                                    }
                                                    if (trim(strtoupper($cmd->type_cmde)) == 'LABORATOIRE') {
                                                        $lien = URL("commandelab/" . $cmd->id);
                                                    }
                                                    if (trim(strtoupper($cmd->type_cmde)) == 'RACHAT METAUX') {
                                                        $lien = URL("commandermp/" . $cmd->id);
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><a href="<?php echo $lien; ?>"><?php echo sprintf("%04d",  $cmd->id); ?></td>
                                                        <td class="text-center"><?php echo  date('d/m/Y', strtotime($cmd->date_cmde)); ?></td>
                                                        <td class="text-center"><?php echo $cmd->qte; ?></td>
                                                        <td class="text-center"><?php echo $cmd->poids; ?>g</td>
                                                        <td class="text-center  hidemobile"><?php if ($cmd->facon > 0) {
                                                                                                echo $cmd->facon . '€';
                                                                                            } ?></td>
                                                        <td class="text-center  hidemobile"><?php echo $cmd->type_cmde; ?></td>
                                                    </tr>
                                                <?php }  ?>
                                                @endforeach
                                            <?php }  ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Fermer</button>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>

    <div class="col-lg-4 col-sm-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Réclamations </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">
                @if($client->Client_Prospect=='CLIENT SAAMP')
                @endif

                @if($client->cl_ident >0)
                <a href="{{route('retours.create',['id'=>$client->id])}}" class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-plus"></i> Ajouter</a>
                <div class="table-container">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Date d'ouverture</th>
                                <th>Date de clôture</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($retours as $retour)
                            <tr>
                                <td><a href="{{route('retours.show',['id'=>$retour->id])}}">{{$retour->Name}}</a></td>
                                <td>{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</td>
                                <td> @if($retour->Date_cloture!='0000-00-00' && $retour->Date_cloture!='') {{date('d/m/Y', strtotime($retour->Date_cloture))}} @endif </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

    </div>

    <div class="col-lg-4 col-sm-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Évènements </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">

                <div class="table-container">
                    <h6 style="width:100%;cursor:pointer" class="black" onclick="$('#prochain').toggle();" >Prochains Rendez Vous</h6>
                    <div id="prochain" style="width:100%">
                        <table class="table table-bordered table-striped mb-40" style="min-height:120px">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Titre</th>
                                    @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                    <th>Supp</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Proch_rendezvous as $rv)
                                <tr>
                                    <td>{{date('d/m/Y H:i', strtotime($rv->Started_at))}}</td><td><a href="{{route('rendezvous.show',['id'=>$rv->id])}}">{{$rv->Subject}}</a></td>
                                    @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                        <td>
                                            <a title="Supprimer" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('rendezvous.destroy', $rv->id )}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                                <span class="fa fa-fw fa-trash-alt"></span>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <h6  style="width:100%;cursor:pointer" class="black"  onclick="$('#ancien').toggle();" >Anciens Rendez Vous</h6>
                    <div id="ancien"  >
                        <table class="table table-bordered table-striped mb-40" >
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Titre</th>
                                    @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                    <th>Supp</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Anc_rendezvous as $rv)
                                <tr>
                                    <td>{{date('d/m/Y H:i', strtotime($rv->Started_at))}}</td><td><a href="{{route('rendezvous.show',['id'=>$rv->id])}}">{{$rv->Subject}}</a></td>
                                        @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                            <td>
                                                <a title="Supprimer" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('rendezvous.destroy', $rv->id )}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                                    <span class="fa fa-fw fa-trash-alt"></span>
                                                </a>
                                            </td>
                                        @endif
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <div class="col-lg-4 col-sm-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Contacts </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">
                @if($client->Client_Prospect=='CLIENT SAAMP')
                @endif
                @if($client->cl_ident >0)
                <a href="{{route('contacts.create',['id'=>$client->id])}}" class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-plus"></i> Ajouter</a>

                <div class="table-container">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Tél</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                            <tr>
                                <td><a href="{{route('contacts.show',['id'=>$contact->id])}}">{{$contact->Nom}}</td>
                                <td>{{$contact->Prenom}}</td>
                                <td>{{$contact->MobilePhone}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

    </div>

    <script>
        function show_stats(cl_id){
            var _token = $('input[name="_token"]').val();
            var mois = 1;
		    if ($('#mois').is(':checked')){
                mois = 0;
            };
            console.log('mois: '+mois);
            $.ajax({
                url: "{{ route('stats_client') }}",
                method: "get",
                data: {  _token: _token,cl_id:cl_id,mois:mois},
                success:function(data){
                    console.log(data);
                    var html='';
                    data.forEach(item => {
                        html+='<tr><td>'+item.metier+'</td><td>'+item.N_3+'</td><td>'+item.N_2+'</td><td>'+item.N_1+'</td><td>'+item.N+'</td></tr>';
                    });
                    $("#stats").html(html);
                }
            });
        }
    </script>
    @endsection
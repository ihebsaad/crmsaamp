@extends('layouts.back')

@section('content')

<style>
    h6,#stats {
        color: black;
        font-weight: bold;
    }
    .status {
        padding: 2px 2px;
        border-radius: 5px;
        margin-bottom: 5px;
        font-size:8px
    }
    #ModalTrading h2{
        font-size:16px!important;
    }
    #ModalTrading h2{
        font-size:16px!important;
    }
    #ModalTrading th  {
        background-color:#e7d686;
    }
    @media (min-width: 1025px){
        #ModalTrading .modal-content{
            width:800px!important;
            /*margin-left:10%;*/
        }
    }
    .comment-cell {
        max-width: 150px; /* ou la valeur adaptée */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .comment-cell:hover {
        white-space: normal;
        overflow: visible;
        position: absolute;
        z-index: 100;
        background: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        max-width: 400px;
    }
    .pd2 td{
        padding:4px 2px 4px 2px!important;
    }
</style>

<div class="pt-2 pl-2 pr-2" style="background-color:#fef2d8">
    <a href="{{route('rendezvous.create',['id'=>$client->id])}}" class="btn btn-sm btn-primary mb-3 mr-3 float-left"><i class="fas fa-calendar-day hidemobile"></i> {{__('msg.Appointment')}}</a><a href="{{route('taches.create',['id'=>$client->id])}}" class="btn btn-sm btn-primary mb-3 mr-3 float-left"><i class="fas fa-tasks hidemobile"></i> {{__('msg.Tasks')}}</a> <a href="{{route('offres.client_list',['id'=>$client->id])}}" class="btn btn-sm  btn-primary mb-3  float-left"><i class="fas fa-gift hidemobile"></i> {{__('msg.Offers')}}</a>                 <a href="{{route('retours.create',['id'=>$client->id])}}" class="btn btn-sm btn-primary mb-3 ml-3 float-left"><i class="fas fa-comment-alt"></i> {{__('msg.Complaint')}}</a>
        @if($client->etat_id==1) <a href="{{route('compte_client.show',['id'=>$client->id])}}" class="btn btn-sm btn-primary mb-3 ml-3 float-left"><i class="fas fa-user-edit hidemobile"></i> {{__('msg.Edit')}}</a> @endif @if($client->cl_ident > 0 ) <a  href="#" data-toggle="modal" data-target="#ModalTrading" class="btn btn-sm btn-primary mb-3 ml-3 float-left"><i class="fas fa-coins hidemobile"></i> Trading</a> @endif <a href="{{route('finances',['id'=>$client->id])}}" class="btn btn-sm  btn-primary mb-3 ml-3 float-left"><i class="fas fa-money-bill-wave hidemobile"></i> {{__('msg.Finances')}}</a> @if($client->cl_ident > 0)  @if($complet) <a  href="{{route('compte_client.folder',['id'=>$client->id])}}"  class="btn btn-sm btn-success ml-4"> ✅ Dossier Complet </a> @else <a href="{{route('compte_client.folder',['id'=>$client->id])}}"  class="btn btn-sm btn-secondary ml-4"> ❌ Dossier incomplet </a>   @endif   @endif
        @if(  $client->etat_id==1  )
            <a title="{{__('msg.Delete')}}"   onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('clients.destroy', $client->id )}}" class="btn btn-sm  btn-danger btn-sm btn-responsive ml-3 mr-2 float-left" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                <span class="fa fa-fw fa-trash-alt hidemobile"></span> {{__('msg.Delete')}}
            </a>
        @endif
</div>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <div class="card shadow mb-1">
            <div class="card-header py-4">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Customer sheet')}} : {{$client->Nom}} - {{$client->cl_ident}}    <b data-title="Ressenti du client">{{ $ressenti }}</b> <b class="float-right text-info " ><i>{{$login}}</i></b> </h6>
            </div>
            <div class="card-body">
                <div class="row" id="">
                    <div class="col-xl-6 col-md-12 col-lg-12" id="">
                        <div class="row pt-1">
                            <div class="col-md-2">
                                <div class="">
                                    <label for="Nom">{{__('msg.Name')}}:</label>
                                    <h6>{{$client->Nom}}</h6>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="">
                                    <label for="postalCode">{{__('msg.Postal code')}}:</label>
                                    <h6>{{$client->zip ?? $client->postalCode}}</h6>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="">
                                    <label for="BillingAddress_city">{{__('msg.City')}}:</label>
                                    <h6>{{$client->ville}}</h6>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="">
                                    <label for="Pays">{{__('msg.Country')}}:</label>
                                    <h6>{{$client->pays_code}}</h6>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="">
                                    <label for="Rue">{{__('msg.Address')}}:</label>
                                    <h6>{{$client->adresse1}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-1">

                            <div class="col-md-2">
                                <div class="">
                                    <label for="agence">{{__('msg.Agency')}}:</label>
                                    <h6>{{$agence_name}}</h6>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="">
                                    <label for="Commercial">Commercial:</label>
                                    <h6>{{$commercial}}</h6>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="">
                                    <label for="Commercial">Commercial support:</label>
                                    <h6>{{$support}}</h6>
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
                                    <label for="Client_Prospect">{{__('msg.Activity')}}:</label>
                                    <h6>{{$client->activite}} - {{$client->sous_activite}} </h6>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="">
                                    <label for="Client_Prospect">Type:</label>
                                    @php $color='';$type_c='';
                                        switch ($client->etat_id) {
                                        case 2 :  $color='#2660c3'; $type_c='Client' ; break;
                                        case 1 : $color='#2ab62c'; $type_c='Prospect' ;break;
                                        case 3 : $color='#ff2e36'; $type_c='Fermé' ; break;
                                        case 4 : $color='#ff2e36';  $type_c='Inactif' ; break;
                                        }
                                    @endphp
                                    <h6 style="color:{{$client->couleur_html ?? 'gray'}}">{{$type_c}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-1">
                            <div class="col-md-4">
                                <div class="">
                                    <label for="Phone">{{__('msg.Phone')}}:</label>
                                    <h6>{{$client->Phone ??  $client->Tel}}</h6>
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
                            <div class="col-md-4">
                                <div class="">
                                    <label for="">{{__('msg.Website')}}:</label>
                                    <h6>{{$client->url}}</h6>
                                </div>
                            </div>


                            <!--
                            <div class="col-md-4">
                                <button type="submit" class="btn-primary btn">Ajouter</button>
                            </div>
                            -->
                        </div>

                    </div>
                    <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12">
                        <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Comments')}} <button type="button" class="btn-primary btn btn-sm mb-2 float-right" data-toggle="modal" data-target="#ModalComments"><i class="fas fa-plus"></i></button></h6>
                        <table class="table table-bordered table-striped mb-40 pd2">
                            <thead>
                                <tr id="headtable">
                                    <th class="" style="width:15%">Date</th>
                                    <th class="" style="width:65%">{{__('msg.Comment')}}</th>
                                    <th class="" style="width:15%">{{__('msg.By')}}</th>
                                </tr>
                            </thead>
                            <tbody id="comments" >
                                @foreach($commentaires as $comment)
                                    @php  $user= \App\Models\User::find($comment->user); @endphp
                                    <tr id="comment-{{ $comment->id}}"><td>{{date('d/m/Y H:i', strtotime($comment->date)) }} <a href="#" onclick="delete_comment({{$comment->id}})"><span class="btn btn-sm"><i class="fa fa-trash"></i></span></a></td><td class="comment-cell">{{$comment->comment}}</td><td>@if($comment->user > 0) {{$user->name.' '.$user->lastname}} @endif</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12">
                        <h6 class="m-0 font-weight-bold text-primary">Contacts
                        @if( /* $client->etat_id==1 || auth()->user()->user_type=='admin' */ true )
                            <a href="{{route('contacts.create',['id'=>$client->id])}}" class="btn btn-sm btn-primary mb-2 ml-3 float-right"><i class="fas fa-plus"></i></a>
                        @endif
                        </h6>
                        <div class="table-container-">
                            <table class="table table-bordered table-striped mb-40 pd2">
                                <thead>
                                    <tr id="headtable">
                                        <th>Email</th>
                                        <th>{{__('msg.Name')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contacts as $contact)
                                    <tr>
                                        <td><a href="{{route('contacts.show',['id'=>$contact->id])}}">{{$contact->email}}</td>
                                        <td><a href="{{route('contacts.show',['id'=>$contact->id])}}">{{$contact->Prenom}} {{$contact->Nom}}</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!--
    <div class="col-lg-4 col-sm-12 mb-4">

        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Comments')}} <button type="button" class="btn-primary btn float-right" data-toggle="modal" data-target="#ModalComments">+</button></h6>
            </div>

            <div class="card-body" style="min-height:400px">
                <table class="table table-bordered table-striped mb-40">
                    <thead>
                        <tr id="headtable">
                            <th class="" style="width:15%">Date</th>
                            <th class="" style="width:65%">{{__('msg.Comment')}}</th>
                            <th class="" style="width:15%">{{__('msg.By')}}</th>
                            <th class="" style="width:5%">{{__('msg.Del')}}</th>
                        </tr>
                    </thead>
                    <tbody id="comments">
                        @foreach($commentaires as $comment)
                            @php  $user= \App\Models\User::find($comment->user); @endphp
                            <tr id="comment-{{ $comment->id}}"><td>{{date('d/m/Y H:i', strtotime($comment->date)) }}</td><td>{{$comment->comment}}</td><td>@if($comment->user > 0) {{$user->name.' '.$user->lastname}} @endif</td><td><a href="#" onclick="delete_comment({{$comment->id}})"><span class="btn btn-sm"><i class="fa fa-trash"></i></span></a></td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
-->
    <div class="col-lg-4 col-sm-12 mb-4">

        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Statistics')}} </h6>
            </div>

            <div class="card-body" style="min-height:400px">
                <div class="table-container-">
                    <input id="mois" type="checkbox" value="1" onchange="show_stats('{{$client->cl_ident}}')"  >
                    <label class="" for="mois">{{__('msg.Show full years')}}</label>
                    </input>
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="">{{__('msg.Job')}}</th>
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

    <div class="col-lg-4 col-sm-12 mb-4">

        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Historique des interactions<a class="btn btn-sm btn-primary float-right" target="_blank" href="{{ route('taches.index') }}">Voir plus</a></h6>
            </div>

            <div class="card-body" style="min-height:400px">
                <div class="table-container">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th>Type</th>
                                <th>Date</th>
                                <th>{{__('msg.Subject')}}</th>
                                <!--<th>Contact</th>-->
                                <th>{{__('msg.Amount')}}</th>
                                <th>{{__('msg.Weight')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taches as $tache)
                            @php
                                    $color='';
                                    switch ( $tache->Status ) {
                                    case 'Not Started':
                                    $color = '#82e2e8';$statut='Pas commencée';
                                    break;
                                    case 'Waiting on someone e':
                                    $color = '#ea922b';$statut='En attente  de quelqu\'un';
                                    break;
                                    case 'In Progress':
                                    $color = '#5f9fff';$statut='En cours';
                                    break;
                                    case 'Deferred':
                                    $color = '#a778c9';$statut='Reportée';
                                    break;
                                    case 'Completed':
                                    $color = '#40c157';$statut='Terminée';
                                    break;
                                    default:
                                    $color = '';
                                    }

                                    $class='';
                                    switch ( $tache->Priority ) {
                                    case 'Normal':
                                    $class = 'primary';$priority='Normale';
                                    break;
                                    case 'High':
                                    $class = 'danger';$priority='Haute';
                                    break;
                                    case 'Low':
                                    $class = 'info';$priority='Basse';
                                    break;

                                    default:
                                    $class = 'primary';$priority='Normale';
                                    }

                                    $icon='';
                                    switch ( $tache->Type ) {
                                    case 'Acompte / Demande de paiement':
                                    $icon = 'img/invoice.png';
                                    break;
                                    case 'Appel téléphonique':
                                    $icon = 'img/call.png';
                                    break;
                                    case 'Envoyer email':
                                    $icon = 'img/email.png';
                                    break;

                                    case 'Envoyer courrier':
                                    $icon = 'img/mail.png';
                                    break;


                                    default:
                                    $class = '';
                                    }
                                    @endphp
                                <tr>
                                    <td>{{$tache->Type}}</td>
                                    <td>{{ date('d/m/Y', strtotime($tache->DateTache)) }} {{$tache->heure_debut}}</td>
                                    <td>@if($tache->as400 == 0)<a href="{{route('taches.show',['id'=>$tache->id])}}">{{ $tache->Subject }}</a>@else  {{ $tache->Subject }} @endif</td>
                                    <td style="padding-left:2px!important">
<!--
                                        @if($tache->as400 == 0)
                                        <span class="float-right status ml-2" style="color:white;font-weight:bold;background-color:{{$color}}" title="Statut"><i class="fas fa-flag"></i> {{ $statut ?? '' }}</span>
                                        <span class="float-right status bg-{{$class}} ml-2" style="color:white;" title="Priorité"><i class="fas fa-bell"></i> {{ $priority ?? '' }}</span>
                                        @endif
                                -->
                                        @if(isset($tache->montant))
                                            {{ $tache->montant>0  ? $tache->montant : '' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($tache->poids))
                                            {{ $tache->poids>0  ? $tache->poids : '' }}
                                        @endif
                                    </td>
                                    <!--
                                    @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                        <td>
                                            @if($tache->as400 == 0)
                                            <a title="{{__('msg.Delete')}}" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('taches.destroy', $tache->id )}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                                <span class="fa fa-fw fa-trash-alt"></span>
                                            </a>
                                            @endif
                                        </td>
                                    @endif
                                    -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

<!--
    <div class="col-lg-4 col-sm-6 mb-4">

         <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Contacts </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">
                @if($client->etat_id==1 || auth()->user()->user_type=='admin' )
                    <a href="{{route('contacts.create',['id'=>$client->id])}}" class="btn btn-primary mb-3 ml-3 float-right"><i class="fas fa-plus"></i> {{__('msg.Add')}}</a>
                @endif

                <div class="table-container">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>{{__('msg.Name')}}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                            <tr>
                                <td><a href="{{route('contacts.show',['id'=>$contact->id])}}">{{$contact->email}}</td>
                                <td><a href="{{route('contacts.show',['id'=>$contact->id])}}">{{$contact->Prenom}} {{$contact->Nom}}</a></td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
                                -->

    <div class="col-lg-4 col-sm-12 mb-4">

        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Current orders')}} </h6>
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
                                <div class="col-md-4">
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
                                <h5 class="modal-title text-center">{{__('msg.Current orders')}}</h5>
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
                                                <th class="text-center">{{__('msg.Labour cost')}}</th>
                                                <th class="text-center">{{__('msg.Type')}}</th>
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
                                                        <td class="text-center"><?php if ($cmd->facon > 0) {
                                                                                                echo $cmd->facon . '€';
                                                                                            } ?></td>
                                                        <td class="text-center"><?php echo $cmd->type_cmde; ?></td>
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



                <div class="modal fade" id="ModalTrading" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="width: 75%;margin: 0 auto;">
                        <div class="modal-content" >
                            <div class="modal-header">
                                <h5 class="modal-title text-center">{{__('msg.Trading of customer')}}</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div id="tot" class="row mt-2 mb-2"></div>
                                <div id="solde" class="row"></div>

                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__('msg.Close')}}</button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal fade" id="ModalComments" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="width: 75%;margin: 0 auto;">
                        <div class="modal-content" >
                            <div class="modal-header">
                                <h5 class="modal-title text-center">Ajouter un commentaire</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <input type="hidden" id="client" value="{{$client->id}}" />
                                <label>{{__('msg.Comment')}} :</label><br>
                                <textarea  class="form-control" id="comment" placeholder="Votre commentaire" ></textarea>

                                <div class="col-md-12 mb-3 text-right">
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="add_comment()">Créer</button>
                                <!--<button class="btn btn-secondary" type="button" data-dismiss="modal">{{__('msg.Close')}}</button>-->
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        </div>

    <div class="col-lg-6 col-sm-12 mb-4">

        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Events')}} </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">

                <div class="table-container">
                    <h6 style="width:100%;cursor:pointer" class="black" onclick="$('#prochain').toggle();" >{{__('msg.Coming appointments')}}</h6>
                    <div id="prochain" style="width:100%">
                        <table class="table table-bordered table-striped mb-40" style="min-height:120px">
                            <thead>
                                <tr id="headtable">
                                    <th>Date</th>
                                    <th>{{__('msg.Title')}}</th>
                                    @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                    <th>{{__('msg.Del')}}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Proch_rendezvous as $rv)
                                <tr>
                                    <td>{{date('d/m/Y', strtotime($rv->Started_at))}} {{$rv->heure_debut}}</td><td><a href="{{route('rendezvous.show',['id'=>$rv->id])}}">{{$rv->Subject}}</a></td>
                                    @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                        <td>
                                            <a title="{{__('msg.Delete')}}" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('rendezvous.destroy', $rv->id )}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                                <span class="fa fa-fw fa-trash-alt"></span>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <h6  style="width:100%;cursor:pointer" class="black"  onclick="$('#ancien').toggle();" >{{__('msg.Old appointments')}}</h6>
                    <div id="ancien"  >
                        <table class="table table-bordered table-striped mb-40" >
                            <thead>
                                <tr id="headtable">
                                    <th>Date</th>
                                    <th>{{__('msg.Title')}}</th>
                                    @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                    <th>{{__('msg.Del')}}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Anc_rendezvous as $rv)
                                <tr>
                                    <td>{{date('d/m/Y', strtotime($rv->Started_at))}} {{$rv->heure_debut}}</td><td><a href="{{route('rendezvous.show',['id'=>$rv->id])}}">{{$rv->Subject}}</a></td>
                                        @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                            <td>
                                                <a title="{{__('msg.Delete')}}" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('rendezvous.destroy', $rv->id )}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
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

    <div class="col-lg-6 col-sm-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-1">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Complaints')}} </h6>
            </div>

            <div class="card-body" style="min-height:400px;width:100%">

                @if($client->cl_ident >0)
                <div class="table-container">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th>{{__('msg.Title')}}</th>
                                <th>{{__('msg.Open date')}}</th>
                                <th>{{__('msg.Closing date')}}</th>
                                @if(auth()->user()->user_type=='admin' || auth()->user()->email=='directeur.qualite@saamp.com')
                                    <th>{{__('msg.Del')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($retours as $retour)
                            <tr>
                                <td><a href="{{route('retours.show',['id'=>$retour->id])}}">{{$retour->Name}}</a></td>
                                <td>{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</td>
                                <td> @if($retour->Date_cloture!='0000-00-00' && $retour->Date_cloture!='') {{date('d/m/Y', strtotime($retour->Date_cloture))}} @endif </td>
                                    @if(auth()->user()->user_type=='admin' || auth()->user()->email=='directeur.qualite@saamp.com')
                                        <td>
                                            <a title="{{__('msg.Delete')}}" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('retours.destroy', $retour->id )}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                                <span class="fa fa-fw fa-trash-alt"></span>
                                            </a>
                                        </td>
                                    @endif
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

        function delete_comment(comment) {
            if (!confirm("Êtes vous sûres?")) {
                return false;
            }

            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('delete_comment') }}",
                method: "POST",
                data: {
                    comment: comment,
                    _token: _token
                },
                success: function(data) {
                    if(data==1)
                    $('#comment-' + comment).hide('slow');
                }
            });
        }

        function add_comment() {

        var _token = $('input[name="_token"]').val();
        var comment = $("#comment").val();
        var client = parseInt($("#client").val());

        $.ajax({
            url: "{{ route('add_comment') }}",
            method: "POST",
            async: false,
            data: {
                client: client,
                comment: comment,
                _token: _token
            },
            success: function(data) {
                if (data != '') {
                    var row = '<tr><td>' + data.date + '</td><td>' + comment + ' </td><td>'+data.user+'</td></tr>';
                    $('#comments').append(row);
                    $('#ModalComments').modal('hide');
                } else {
                    alert('erreur !')
                }

            }
        });

        }
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

        function load_data()
        {
	        var _token = $('input[name="_token"]').val();
	        $.ajax({
                url: "https://mysaamp.com/solde/1/{{$client->cl_ident}}",
                method: "get",
                dataType: "json", // Use jsonp to bypass CORS
                //data: {  _token: _token},
                success:function(data){
                    $("#solde").html(data.solde);
                    $("#tot").html(data.tot);
                    $("#trading").html(data.trading+' €');
                    $("#lots").html(data.lots+' €');
                    $(".total").val(data.total);
                    $("#weight").html(data.weight+' €');
					console.log('data : '+data);
                }
            });
        }

        load_data();
    </script>
    @endsection
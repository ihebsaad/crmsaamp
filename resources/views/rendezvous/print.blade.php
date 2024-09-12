@extends('layouts.print')

@section('content')

<?php

?>

<style>
    h6 {
        color: black;
        font-weight: bold;
    }
</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <h6 class="m-0 font-weight-bold text-primary mb-4 mt-4 ml-3">Rendez Vous N° <b style="font-weight:900">{{$rendezvous->id}}</b> </h6>

        <div class="row pt-1">
            <div class="col-md-3">
                <div class="">
                    <label for="Account_Name">Client:</label>
                    <h6>{{$rendezvous->Account_Name}}</h6>
                    <h6><small>{{$client->adresse1}}</small></h6>
                </div>
            </div>
            <div class="col-md-2">
                <div class="">
                    <label for="Started_at">Date de début:</label>
                    <h6>{{date('d/m/Y', strtotime($rendezvous->Started_at))}}</h6>
                </div>
            </div>
            <div class="col-md-2">
                <div class="">
                    <label for="heure_fin">Heure de début:</label>
                    <h6>{{$rendezvous->heure_debut}}</h6>
                </div>
            </div>
            <div class="col-md-2">
                <div class="">
                    <label for="End_at">Date de fin:</label>
                    <h6>@if($rendezvous->End_at!='') {{date('d/m/Y', strtotime($rendezvous->End_at))}} @endif</h6>
                </div>
            </div>
            <div class="col-md-2">
                <div class="">
                    <label for="heure_fin">Heure de fin:</label>
                    <h6>{{$rendezvous->heure_fin}}</h6>
                </div>
            </div>
        </div>

        <div class="row pt-1">
            <div class="col-md-3">
                <div class="">
                    <label for="Type">Type:</label>
                    <h6>{{$rendezvous->Type}}</h6>
                </div>
            </div>

            <div class="col-md-6">
                <div class="">
                    <label for="Location">Lieu:</label>
                    <h6>{{$rendezvous->Location}}</h6>
                </div>
            </div>

        </div>

        <div class="row pt-1">
            <div class="col-md-3">
                <div class="">
                    <label for="Subject">Sujet:</label>
                    <h6>{{$rendezvous->Subject}}</h6>
                </div>
            </div>

            <div class="col-md-3">
                <div class="">
                    <label for="Date_creation">Attribué à:</label>
                    @if($rendezvous->user_id > 0 )
                    <?php $user = \App\Models\User::find($rendezvous->user_id); ?>
                    <h6>{{ $user->name}} {{ $user->lastname}}</h6>
                    @else
                    <h6>{{ $rendezvous->Attribue_a}}</h6>
                    @endif

                </div>
            </div>

            <div class="col-md-6">
                <div>
                    <label for="Description">Description :</label>
                    <h6>{{$rendezvous->Description}}</h6>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
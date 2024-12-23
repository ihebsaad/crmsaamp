@extends('layouts.print')

@section('content')

<?php

?>

<style>
    h1,h2,.black {
        color: black;
        font-weight: bold;
    }

</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <div class="row pb-1" style="background-color:#f9eed8">
            <div class="col-md-3">
                <h6 class="m-0 font-weight-bold text-primary ">{{__('msg.Appointment')}} {{__('msg.Num')}} <b style="font-weight:900">{{$rendezvous->id}}</b> </h6>
            </div>
            <div class="col-md-5">

            </div>
            <div class="col-md-4 float-right text-right">
                {{$user->email ?? ''}}
            </div>
        </div>
        <div class="row pt-3">
            <div class="col-md-3">
                <img width="100"  src="{{  URL::asset('img/rv.png') }}" /><br><br>
                <b><i class="black">{{__('msg.Created by')}} : {{$user->name ?? '' }} {{$user->lastname ?? '' }}</i></b>
            </div>
            <div class="col-md-4">
                <h1>{{$rendezvous->Account_Name}}</h1>
            </div>
            <div class="col-md-4">
                <label for="Location">{{__('msg.Customer address')}}:</label>
                <h2>{{$client->ville ?? ''}}, {{$client->adresse1 ?? ''}} - {{$client->zip ?? ''}}</h2><br>
                <label for="Location">{{__('msg.Place')}}:</label>
                <h2>{{$rendezvous->Location}}</h2>
            </div>

        </div>

        <div class="row pt-1">

            <div class="col-md-12">
                <div class="">
                    <label for="Started_at">{{__('msg.Start date')}}:</label>
                    <h2>{{ ucfirst(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $rendezvous->Started_at)->translatedFormat('l d F Y')) }} {{$rendezvous->heure_debut}}</h2>
                </div>
            </div>

            <div class="col-md-12">
                <div class="">
                    <label for="End_at">{{__('msg.End date')}}:</label>
                    <h2>@if($rendezvous->End_at!='') {{ ucfirst(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $rendezvous->End_at)->translatedFormat('l d F Y')) }} {{$rendezvous->heure_fin}}@endif</h2>
                </div>
            </div>

        </div>

        <div class="row pt-1">
            <div class="col-md-12">
                <div class="">
                    <label for="Subject">{{__('msg.Subject')}}:</label>
                    <h2>{{$rendezvous->Subject}}</h2>
                </div>
            </div>
        </div>

        <div class="row pt-1">

            <div class="col-md-12">
                <div>
                    <label for="Description">{{__('msg.Description')}}:</label>
                    <h2>{{$rendezvous->Description}}</h2>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
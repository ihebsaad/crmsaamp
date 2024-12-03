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

        <h6 class="m-0 font-weight-bold text-primary mb-4 mt-4 ml-3">{{__('msg.Appointments list')}} {{__('msg.of')}}  <b>{{$name}}</b> - @if($date_debut!=$date_fin) de {{date('d/m/Y', strtotime($date_debut))}} à {{ date('d/m/Y', strtotime($date_fin))}} @else  le <b>{{date('d/m/Y', strtotime($date_debut))}}</b>  @endif </h6>

                 <table id="" class="table table-striped" style="width:100%">
                    <thead>
                        <tr id="">
                            <th >ID </th>
                            <th >{{__('msg.Customer')}}</th>
                            <th style="width:30%" >{{__('msg.Subject')}}</th>
                            <th >{{__('msg.Date')}}</th>
                            <th >{{__('msg.Hour')}}</th>
                            <th >{{__('msg.Place')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rendezvous as $rv)
                            @php
                                $client=\App\Models\CompteClient::find($rv->mycl_id);
                                $location='';
                                if(isset($client))
                                    $location=$client->ville.' ('.$client->adresse1.')';
                            @endphp
                            <tr>
                                <td>{{ $rv->id }}</td>
                                <td>{{ $rv->Account_Name }}</td>
                                <td>{{ $rv->Subject }}</td>
                                <td>{{ date('d/m/Y', strtotime($rv->Started_at)) }}</td>
                                <td>{{ $rv->heure_debut }}</td>
                                <td>{{ $rv->Location ?? $location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

    </div>

</div>

@endsection
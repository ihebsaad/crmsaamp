@extends('layouts.back')

@section('content')

<?php

?>

<style>
h6{
    color:black;
    font-weight:bold;
}

</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Appointment')}} {{$rendezvous->id}} </h6>
            </div>

            <div class="card-body" style="min-height:500px">

                <form action="{{ route('rendezvous.update', $rendezvous->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="edited_by" value="{{auth()->user()->id}}" >

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Account_Name">@if($client!= null) {{__('msg.Customer')}}: @else {{__('msg.Name')}}: @endif </label>
                                @if($rendezvous->mycl_id>0)
                                    <h6><a href="{{route('fiche',['id'=>$rendezvous->mycl_id])}}">{{$rendezvous->Account_Name}}</a></h6>
                                @else
                                    <h6>{{$rendezvous->Account_Name}}</h6>
                                @endif
                                <h6><small>{{$adresse ?? ''}} </small></h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Started_at">{{__('msg.Start date')}}:</label>
                                <input type="text" id="Started_at" class="form-control datepicker" name="Started_at"  value="{{date('Y-m-d', strtotime($rendezvous->Started_at))}}"><br><br>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="heure_fin">{{__('msg.Start hour')}}:</label>
                                <input type="time" id="heure_debut" class="form-control" name="heure_debut"  value="{{$rendezvous->heure_debut}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="End_at">{{__('msg.End date')}}:</label>
                                <input type="text" id="End_at" class="form-control datepicker" name="End_at"  value="{{ $rendezvous->End_at !='' ? date('Y-m-d', strtotime($rendezvous->End_at)) : '' }}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="heure_fin">{{__('msg.End hour')}}:</label>
                                <input type="time" id="heure_fin" class="form-control" name="heure_fin"  value="{{$rendezvous->heure_fin}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                            <label for="Type">{{__('msg.Type')}}:</label>
                                <h6>{{$rendezvous->Type}}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                        <div class="">
                                <label for="Location">{{__('msg.Place')}}:</label>
                                <h6>{{$rendezvous->Location}}</h6>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Subject">{{__('msg.Subject')}}:</label>
                                <input type="text" id="Subject" class="form-control" name="Subject"  value="{{$rendezvous->Subject}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_creation">{{__('msg.Attributed to')}}:</label>
                                @if($rendezvous->user_id > 0 )
                                    <?php $user=\App\Models\User::find($rendezvous->user_id); ?>
                                    <h6>{{ $user->name}} {{ $user->lastname}}</h6>
                                @else
                                    <h6>{{ $rendezvous->Attribue_a}}</h6>
                                @endif

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div >
                                <label for="Description">{{__('msg.Description')}}:</label>
                                <textarea id="Description" class="form-control" name="Description" style="min-height:150px">{{$rendezvous->Description}}</textarea><br><br>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            @if($rendezvous->fichier!= null)
                                @php $fileNames = unserialize($rendezvous->fichier); @endphp
                                <div class="">
                                    <label for="Description">{{__('msg.File(s)')}}:</label><br>
                                    <table style="border:none">

                                        @foreach ($fileNames as $fichier)
                                        <tr style="border:none">
                                            <td><label><b class="black mr-4">{{$fichier}}</b></label></td>
                                            <td><a href="https://crm.mysaamp.com/fichiers/{{$fichier}}" target="_blank" ><img class="view mr-2" title="Visualiser" width="30" src="{{ URL::asset('img/view.png')}}"></a></td>
                                            <td><a href="https://crm.mysaamp.com/fichiers/{{$fichier}}" download ><img class="download mr-2" title="Télecharger" width="30" src="{{ URL::asset('img/download.png')}}"></a></td>
                                            <td>
                                                <form method="POST" class="delete-file-form" action="{{ route('fichier.delete', $rendezvous->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="file_name" value="{{ $fichier }}">
                                                    <button type="submit" class="btn btn-danger" title="Supprimer ce fichier"  style="line-height: 18px;font-size: 15px;padding: 5px;" onclick="return confirm('Êtes-vous sûrs ?')"><i class="fa fa-fw fa-trash-alt"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach

                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right" >{{__('msg.Edit')}}</button>
                            @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                <a title="{{__('msg.Delete')}}" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('rendezvous.destroy', $rendezvous->id )}}" class="btn btn-danger btn-sm btn-responsive mr-2 float-right" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                    <span class="fa fa-fw fa-trash-alt"></span> {{__('msg.Delete')}}
                                </a>
                            @endif
                        </div>
                    </div>
                    @if($rendezvous->created_by > 0)
                        <div class="row pt-1">
                            <div class="col-md-12">
                                <?php $creator=\App\Models\User::find($rendezvous->user_id); ?>
                                <b><i>{{__('msg.Created by')}} : {{$creator->name}} {{$creator->lastname}}</i></b>
                            </div>
                        </div>
                    @elseif($rendezvous->user_id > 0)
                        <div class="row pt-1">
                            <div class="col-md-12">
                                <?php $creator=\App\Models\User::find($rendezvous->user_id); ?>
                                <b><i>{{__('msg.Created by')}} : {{$creator->name}} {{$creator->lastname}}</i></b>
                            </div>
                        </div>
                    @endif
                    @if($rendezvous->edited_by > 0)
                        <div class="row pt-1">
                            <div class="col-md-12">
                                <?php $User=\App\Models\User::find($rendezvous->edited_by); ?>
                                <b><i>{{__('msg.Last update by')}} : {{$User->name}} {{$User->lastname}}</i></b>
                            </div>
                        </div>
                    @endif
                    <div class="row pt-1">
                        <div class="col-md-12">
                            <a  href="{{route('rendezvous.print',['id'=>$rendezvous->id])}}" target="_blank" class="btn btn-secondary" ><i class="fa fa-print"></i> Imprimer</a>
                        </div>
                    </div>
                </form>

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
                //minDate:0
            });
        });

    </script>

    @endsection
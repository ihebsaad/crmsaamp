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
                <h6 class="m-0 font-weight-bold text-primary">Rendez Vous {{$rendezvous->id}} </h6>
            </div>

            <div class="card-body" style="min-height:500px">

                <form action="{{ route('rendezvous.update', $rendezvous->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Account_Name">Nom du client:</label>
                                <h6>{{$rendezvous->Account_Name}}</h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Started_at">Date de début:</label>
                                <input type="text" id="Started_at" class="form-control datepicker" name="Started_at"  value="{{date('Y-m-d', strtotime($rendezvous->Started_at))}}"><br><br>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="heure_fin">Heure de début:</label>
                                <input type="time" id="heure_debut" class="form-control" name="heure_debut"  value="{{$rendezvous->heure_debut}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="End_at">Date de fin:</label>
                                <input type="text" id="End_at" class="form-control datepicker" name="End_at"  value="{{ $rendezvous->End_at !='' ? date('Y-m-d', strtotime($rendezvous->End_at)) : '' }}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="heure_fin">Heure de fin:</label>
                                <input type="time" id="heure_fin" class="form-control" name="heure_fin"  value="{{$rendezvous->heure_fin}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Nom">Contact:</label>
                                <select  id="ID_Contact" class="form-control" name="ID_Contact" required  >
                                @foreach($contacts as $contact)
                                    <option @if($rendezvous->ID_Contact==$contact->id) selected="selected" @endif value="{{$contact->id}}">{{$contact->Nom}} {{$contact->Prenom}}  @if($contact->Title!='') ( {{$contact->Title}} ) @endif</option>
                                @endforeach
                            </select>
                            </div>
                        </div>

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
                                <input type="text" id="Subject" class="form-control" name="Subject"  value="{{$rendezvous->Subject}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Date_creation">Attribué à:</label>
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
                                <label for="Description">Description :</label>
                                <textarea id="Description" class="form-control" name="Description" style="min-height:150px">{{$rendezvous->Description}}</textarea><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right">Modifier</button>
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
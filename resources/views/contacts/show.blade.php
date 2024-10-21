@extends('layouts.back')

@section('content')

<?php

?>

<style>


</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Contact {{ $contact->id}} </h6>
            </div>

            <div class="card-body" style="min-height:500px">
                <div class="row">
                    <div class="col-sm-12">
                    </div>
                </div>
                <form action="{{ route('contacts.update', $contact->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Nom">Nom:</label>
                                <input type="text" id="Nom" class="form-control" name="Nom"  value="{{$contact->Nom}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Prenom">Prénom:</label>
                                <input type="text" id="Prenom" class="form-control" name="Prenom"  value="{{$contact->Prenom}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="">
                                <label for="Title">Titre:</label>
                                <input type="text" id="Title" class="form-control" name="Title"  value="{{$contact->Title}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Motif_retour">Client ID:</label>
                                {{$contact->cl_ident}}
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">

                        <div class="col-md-3">
                            <div class="">
                                <label for="MobilePhone">Mobile:</label>
                                <input type="text" id="MobilePhone" class="form-control" name="MobilePhone"  value="{{$contact->MobilePhone}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Phone">Tél:</label>
                                <input type="text" id="Phone" class="form-control" name="Phone"  value="{{$contact->Phone}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Email">Email:</label>
                                <input type="text" id="Email" class="form-control" name="Email"  value="{{$contact->email}}"><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">

                        <div class="col-md-4">
                            <div class="">
                                <label for="Description">Compte:</label>
                                <input type="text" id="Compte" class="form-control" name=""  readonly value="{{$client->Nom}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="Description">Description:</label>
                                <textarea  id="Description" class="form-control" name="Description"  >{{$contact->Description}}</textarea><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">
                            @if($client->etat_id==1 || 1 )
                                <button type="submit" class="btn-primary btn float-right">Modifier</button>
                            @endif
                            @if(auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv')
                                <a title="Supprimer" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('contacts.destroy', $contact->id )}}" class="btn btn-danger btn-sm btn-responsive mr-2 float-right" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                    <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                                </a>
                            @endif
                        </div>
                    </div>


                </form>

            </div>
        </div>

    </div>

</div>


@endsection
@extends('layouts.back')

@section('content')

<?php

?>

<style>

[data-title]:hover:after {
    opacity: 1;
    transition: all 0.1s ease 0.5s;
    visibility: visible;
  }

  [data-title]:after {
    content: attr(data-title);
    position: absolute;
    /*bottom: -1.6em;*/
    right: 100%;
    padding: 4px 4px 4px 8px;
    white-space: nowrap;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -moz-box-shadow: 0px 0px 4px #222;
    -webkit-box-shadow: 0px 0px 4px #222;
    box-shadow: 0px 0px 4px #222;
    opacity: 0;
    z-index: 99999;
    visibility: hidden;
    background-color: #2e3e4e;
    color: white;
    padding: 5px 5px;
    font-size: 15px;
  }

  [data-title] {
    position: relative;
  }

</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">
        <form action="{{ route('contacts.update', $contact->id) }}" method="post">
        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Contact')}}  {{ $contact->id}}  -  {{$client->Nom}}
                    @if($client->etat_id==1 || 1 )
                        <button type="submit" class="btn-primary btn float-right">{{__('msg.Edit')}}</button>
                    @endif
                    @if($client->etat_id==1 )
                        <a title="{{__('msg.Delete')}}" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('contacts.destroy', $contact->id )}}" class="btn btn-danger btn-sm btn-responsive mr-2 float-right" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                            <span class="fa fa-fw fa-trash-alt"></span> {{__('msg.Delete')}}
                        </a>
                    @else
                    <button data-title="Pour supprimer ce contact merci de passer par l'AS400" disabled  class="btn btn-danger btn-sm btn-responsive mr-2 float-right" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                        <span class="fa fa-fw fa-trash-alt"></span>
                    </button>
                    @endif
                </h6>
            </div>

            <div class="card-body" style="min-height:500px">
                <div class="row">
                    <div class="col-sm-12">
                    </div>
                </div>

                    @csrf
                    @method('PUT')

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Nom">{{__('msg.Contact last name')}}:</label>
                                <input type="text" id="Nom" class="form-control" name="Nom"  value="{{$contact->Nom}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Prenom">{{__('msg.Contact first name')}}:</label>
                                <input type="text" id="Prenom" class="form-control" name="Prenom"  value="{{$contact->Prenom}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Title">{{__('msg.Title')}}:</label>
                                <input type="text" id="Title" class="form-control" name="Title"  value="{{$contact->Title}}"><br><br>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-2">
                            <div class="">
                                <label for="Motif_retour">{{__('msg.Client ID')}}:</label>
                                {{$contact->cl_ident}}
                            </div>
                        </div>-->
                        <input type="hidden" name="cl_ident" value="{{$contact->cl_ident}}" />

                    </div>

                    <div class="row pt-1">

                        <div class="col-md-3">
                            <div class="">
                                <label for="MobilePhone">{{__('msg.Mobile')}}:</label>
                                <input type="text" id="MobilePhone" class="form-control" name="MobilePhone"  value="{{$contact->MobilePhone}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Phone">{{__('msg.Phone')}}:</label>
                                <input type="text" id="Phone" class="form-control" name="Phone"  value="{{$contact->Phone}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Email">{{__('msg.Email')}}:</label>
                                <input type="text" id="Email" class="form-control" name="email"  value="{{$contact->email}}"><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">


                        <div class="col-md-9">
                            <div class="">
                                <label for="Description">{{__('msg.Description')}}:</label>
                                <textarea  id="Description" class="form-control" name="Description"  >{{$contact->Description}}</textarea><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">


                            <!--
                            @if(auth()->user()->role=='admin'  )
                                <a title="{{__('msg.Delete')}}" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('contacts.destroy', $contact->id )}}" class="btn btn-danger btn-sm btn-responsive mr-2 float-right" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                    <span class="fa fa-fw fa-trash-alt"></span> {{__('msg.Delete')}}
                                </a>
                            @endif
                            -->
                        </div>
                    </div>


                </form>

            </div>
        </div>

    </div>

</div>


@endsection
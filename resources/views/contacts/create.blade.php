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
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Add a contact')}}Ajouter un contact </h6>
            </div>

            <div class="card-body" style="min-height:500px">


                <form action="{{ route('contacts.store') }}" method="post">

                <input type="hidden" id="mycl_ident"   name="mycl_ident"  value="{{$client->id}}">
                <input type="hidden" id="cl_ident"   name="cl_ident"  value="{{$client->cl_ident}}">

                    @csrf

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Nom">{{__('msg.Last name')}}:</label>
                                <input type="text" id="Nom" class="form-control" name="Nom"  value="{{old('Nom')}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Prenom">{{__('msg.First name')}}:</label>
                                <input type="text" id="Prenom" class="form-control" name="Prenom"  value="{{old('Prenom')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="">
                                <label for="Title">{{__('msg.Title')}}Titre:</label>
                                <input type="text" id="Title" class="form-control" name="Title"  value="{{old('Title')}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Title">{{__('msg.Client ID')}}:</label>
                                <input type="text" id="Title" class="form-control" name="cl_ident"  readonly value="{{$client->cl_ident}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">

                        <div class="col-md-3">
                            <div class="">
                                <label for="MobilePhone">{{__('msg.Mobile')}}:</label>
                                <input type="text" id="Phone" class="form-control" name="Phone"  value="{{old('MobilePhone')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Phone">{{__('msg.Phone')}}:</label>
                                <input type="text" id="Phone" class="form-control" name="Phone"  value="{{old('Phone')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Email">{{__('msg.Email')}}:</label>
                                <input type="text" id="Email" class="form-control" name="email"  value="{{old('email')}}"><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">

                        <div class="col-md-4">
                            <div class="">
                                <label for="Description">{{__('msg.Customer')}}:</label>
                                <input type="text" id="Compte" class="form-control" name="" readonly value="{{ $client->Nom }}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="Description">{{__('msg.Description')}}:</label>
                                <textarea  id="Description" class="form-control" name="Description"  >{{old('Description')}}</textarea><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right">{{__('msg.Add')}}</button>
                        </div>
                    </div>


                </form>

            </div>
        </div>

    </div>

</div>


@endsection
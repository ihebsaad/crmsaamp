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
                <h6 class="m-0 font-weight-bold text-primary">Ajouter un contact </h6>
            </div>

            <div class="card-body" style="min-height:500px">


                <form action="{{ route('contacts.store') }}" method="post">

                <input type="hidden" id="mycl_ident"   name="mycl_ident"  value="{{$client->id}}">
                <input type="hidden" id="cl_ident"   name="cl_ident"  value="{{$client->cl_ident}}">

                    @csrf

                    <div class="row pt-1">
                        <div class="col-md-3">
                            <div class="">
                                <label for="Nom">Nom:</label>
                                <input type="text" id="Nom" class="form-control" name="Nom"  value="{{old('Nom')}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="">
                                <label for="Prenom">Prénom:</label>
                                <input type="text" id="Prenom" class="form-control" name="Prenom"  value="{{old('Prenom')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="">
                                <label for="Title">Titre:</label>
                                <input type="text" id="Title" class="form-control" name="Title"  value="{{old('Title')}}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Title">Client ID:</label>
                                <input type="text" id="Title" class="form-control" name="cl_ident"  readonly value="{{$client->cl_ident}}"><br><br>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-1">

                        <div class="col-md-3">
                            <div class="">
                                <label for="MobilePhone">Mobile:</label>
                                <input type="text" id="Phone" class="form-control" name="Phone"  value="{{old('MobilePhone')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Phone">Tél:</label>
                                <input type="text" id="Phone" class="form-control" name="Phone"  value="{{old('Phone')}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="">
                                <label for="Email">Email:</label>
                                <input type="text" id="Email" class="form-control" name="email"  value="{{old('email')}}"><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">

                        <div class="col-md-4">
                            <div class="">
                                <label for="Description">Client:</label>
                                <input type="text" id="Compte" class="form-control" name="" readonly value="{{ $client->Nom }}"><br><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="Description">Description:</label>
                                <textarea  id="Description" class="form-control" name="Description"  >{{old('Description')}}</textarea><br><br>
                            </div>
                        </div>

                    </div>

                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right">Ajouter</button>
                        </div>
                    </div>


                </form>

            </div>
        </div>

    </div>

</div>


@endsection
@extends('layouts.back')

@section('content')

<?php

?>

<div class="row">

    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Offer')}} {{$offre->id}} </h6>
            </div>
            <div class="card-body" style="min-height:300px">
            <h5 class="black">{{__('msg.Edit the document')}}<b>{{$name}}</b></h5>
                <form action="{{route('offres.editFile')}}" method="post" enctype="multipart/form-data" style="margin:30px 0px 50px 50px">
                    {{ csrf_field() }}
                    <input   type="hidden"   name="id" value="{{$id}}"  required>
                    <input   type="hidden"   name="item_id" value="{{$item}}"  required>

                    <div class="row pt-1">
                        <div class="col-md-4">
                            <label for="files">{{__('msg.Select files (PDF only)')}}</label>
                            <input class="form-control" type="file" id="file" name="file" multiple required accept="application/pdf">
                        </div>

                         <div class="col-md-4">
                            <button type="submit" class="btn-primary btn mt-4 ml-5">{{__('msg.Edit')}}</button>
                         </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
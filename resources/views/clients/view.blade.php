@extends('layouts.back')

@section('content')

<?php

?>

<div class="row">

    <div class="col-lg-12 mb-4">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Voir le fichier</h6>
            </div>
            <div class="card-body">
                <iframe src="{{ route('showPdf', ['id' => $id]) }}" width="100%" height="600px">
                    This browser does not support PDFs. Please download the PDF to view it: <a href="{{ route('showPdf', ['id' => $id]) }}">Download PDF</a>
                </iframe>
            </div>
        </div>



    </div>

</div>

@endsection
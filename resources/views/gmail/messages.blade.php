
@extends('layouts.admin')

@section('content')
    <a href="{{ route('google.auth') }}" class="btn btn-primary text-right float-right">Se connecter à Gmail</a>

    <div class="container">
    <h1>Vos messages Gmail</h1>
    <ul>
        @foreach($messages as $message)
            <li>{{ $message->getId() }}</li>
        @endforeach
    </ul>
    </div>

@endsection

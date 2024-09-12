@extends('layouts.print')

@section('content')

<table class="table table-striped mb-40">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Ville</th>
            <th>Agence</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clients as $client)

        @php $color='';
        //$agenceLib = $client->agence ? $client->agence->agence_lib : '';
        $agenceLib = $agences[$client->agence_ident] ?? '';
        $type_c='';
        switch ($client->etat_id) {
        case 2 : $color='#2660c3'; $type_c='Client' ; break;
        case 1 : $color='#2ab62c'; $type_c='Prospect' ;break;
        case 3 : $color='#ff2e36'; $type_c='Fermé' ; break;
        case 4 : $color='#ff2e36'; $type_c='Inactif' ; break;

        }

        @endphp
        <tr>
            <td><a href="{{route('fiche',['id'=>$client->id])}}">{{$client->Nom}}</a></td>
            <td>{{$client->ville}}</td>
            <td>{{$agenceLib}}</td>
            <td style="color:{{$color}}">{{$type_c}}</td>
        </tr>
        @endforeach
    <tbody>
</table>
@endsection
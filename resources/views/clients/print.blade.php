@extends('layouts.print')


@section('content')
<style>
        @page {
            size: landscape;
        }

        /* Optionally, you can also adjust table styles for better readability in landscape mode */
        table {
            width: 100%;
            font-size: 12px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }
 </style>
<table class="table table-striped mb-40">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Adresse</th>
            <th>Ville</th>
            <th>Tél</th>
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

        $tel= $client->Phone ??  $client->Tel;
        if(trim($tel)==''){
            $contact=App\Models\Contact::where('mycl_ident',$client->id)->first();
            if(isset($contact)){
                $tel= $contact->Phone ?? $contact->MobilePhone   ;
            }
            else{
                $contact=App\Models\Contact::where('cl_ident',$client->cl_ident)->first();
                $tel= $contact->Phone ?? $contact->MobilePhone ?? ''   ;
            }
        }
        @endphp
        <tr>
            <td><a href="{{route('fiche',['id'=>$client->id])}}">{{$client->Nom}}</a></td>
            <td>{{$client->adresse1}}</td>
            <td>{{$client->ville}}</td>
            <td>{{$tel}}</td>
            <td>{{$agenceLib}}</td>
            <td style="color:{{$color}}">{{$type_c}}</td>
        </tr>
        @endforeach
    <tbody>
</table>
@endsection
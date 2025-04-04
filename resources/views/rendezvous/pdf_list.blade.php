@extends('layouts.pdf')

@section('content')

<script type="text/php">

if (isset($pdf)) {
 //Shows number center-bottom of A4 page with $x,$y values
    $x = 520;  //X-axis i.e. vertical position
    $y = 820; //Y-axis horizontal position
    $text = "Page {PAGE_NUM} / {PAGE_COUNT}";  //format of display message
    $font =  $fontMetrics->get_font("helvetica", "bold");
    $size = 8;
    $color = array(0,0,0);
    $color2 = array(136,136,136);
    $word_space = 0.0;  //  default
    $char_space = 0.0;  //  default
    $angle = 0.0;   //  default
    $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    $pdf->page_text(30, $y,'liste des rendez-vous' , $font, $size, $color, $word_space, $char_space, $angle);
}

</script>



<style>
    h6 {
        color: black;
        font-weight: bold;
    }
    .event{
        border-bottom:1px solid #c3af7a;
        padding-bottom:25px;
    }
    body{
        font-family:'Nunito';
    }
    footer {
		   position: fixed;
		   bottom: -120px;
		   left: 0px;
		   right: 0px;
		   height: 100px;
		   text-align: center;
		   font-size:10px;
		   line-height:11px;
		   font-weight:normal;
		   page-break-inside: avoid;
	   }
</style>

<b style="font-size:22px;width:100%">{{__('msg.Appointments list')}} {{__('msg.of')}}  {{$name}} <img style="width:150px;position:fixed;right:0px;" src="{{ URL::asset('img/logo.png')}}" /></b>
<b style="position:fixed;top:35px;font-size:18px;">Période : @if($date_debut!=$date_fin) du {{date('d/m/Y', strtotime($date_debut))}} au {{ date('d/m/Y', strtotime($date_fin))}} @else le <b>{{date('d/m/Y', strtotime($date_debut))}} @endif </b><br>


<div class="events">
    @php $i=0; @endphp
    @foreach ($rendezvous as $rv)

    @php
    $i++;
    $client=\App\Models\CompteClient::find($rv->mycl_id);
    $location = $color='';
    $type_c='Agence';
    if(isset($client)){
        $location=$client->ville.' ('.$client->adresse1.')';
        $color=$client->couleur_html;
        switch ($client->etat_id) {
            case 2 :  $color='#2660c3'; $type_c='Client' ; break;
            case 1 : $color='#2ab62c'; $type_c='Prospect' ;break;
            case 3 : $color='#ff2e36'; $type_c='Client Fermé' ; break;
            case 4 : $color='#d32230';  $type_c='Client Inactif' ; break;
        }
    }
    $status = [1 => "Planifié", 2 => "Réalisé",3=> 'Annulé'];
    $files= \App\Models\File::where('parent_id',$rv->id)->where('parent','rendezvous')->count();
    @endphp
    <ul class="event">
        <b style="color:#808080;font-size:19px;margin-left:-25px">{{ $i }}. Rendez vous du {{ date('d/m/Y', strtotime($rv->Started_at)) }}</b><br>
        <li><i><b>Heure: </b> {{ $rv->heure_debut }} - {{ $rv->heure_fin ?? 'N/A' }}</i></li>
        <li><b >{{$type_c}}</b> : <span style="color:{{$color ?? 'gray'}}"> {{ $rv->Account_Name }} {{$client!='' ? $client->cl_ident : ''}} </span> </li>
        <li><b>Lieu: </b>{{ $rv->Location ?? $location }}</li>
        <li><b>Statut: </b>{{ $rv->statut > 0 ? $status[$rv->statut] : '' }}</li>
        <li><b>Type: </b>{{ $rv->Type }}</li>
        <!--<li><b>Sujet: </b>{{ $rv->Subject }}</li>-->
        <!--<li><b>Sujet: </b>{{ $rv->Subject }}</li>-->
        @if($rv->Description!='') <li style="margin-left:15px;list-style:none"><b>Compte Rendu: </b> {{$rv->Description}} </li> @endif
    </ul>
    @endforeach
</div>
<footer>
CRM SAAMP - Document généré le {{date('d/m/Y H:i')}}
</footer>
@endsection
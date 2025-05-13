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


       .rdv-item {
            margin-left: 20px;
            margin-bottom: 5px;
        }
        .total {
            text-align: right;
            margin-top: 15px;
            margin-bottom: 15px;
            font-style: italic;
        }
        hr {
            border: 0.5px solid #ddd;
            margin: 15px 0;
        }
        h3{
            color:#808080;
        }
        .category-header {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 18px;
            color: #333;
            border-left: 4px solid #c3af7a;
        }
        .text-primary{
          color: #4e73dc ;
          font-size:12px;
        }
        .text-danger{
           color: #e74a37;
           font-size:12px;
        }
        .text-success{
            color:#1cc88a;
            font-size:12px;
        }
</style>

<b style="font-size:22px;width:100%">Synthèse des rendez vous {{__('msg.of')}} {{$name}} <img style="width:150px;position:fixed;right:0px;" src="{{ URL::asset('img/logo.png')}}" /></b>
<b style="position:fixed;top:35px;font-size:18px;">Période : @if($date_debut!=$date_fin) du {{date('d/m/Y', strtotime($date_debut))}} au {{ date('d/m/Y', strtotime($date_fin))}} @else le <b>{{date('d/m/Y', strtotime($date_debut))}} @endif </b><br>
<br>
<div class="events">
    <!-- 1. Rendez-vous hors clientèles -->
    <div class="category-header">1. Rendez-vous hors clientèles</div>
    <ul class="event">
    @if(count($rendezVousHorsClientelesByType) > 0)
        @foreach($rendezVousHorsClientelesByType as $type => $rendezVous)
            <div class="rdv-list">
                <h3>Rendez-vous de type : <u>{{ $type ?: 'Non défini' }}</u></h3>
                <ul>
                    @foreach($rendezVous as $rdv)
                        <li class="rdv-item">
                            {{ date('d/m/y', strtotime($rdv->Started_at)) }} @if($rdv->Started_at != $rdv->End_AT &&  $rdv->End_AT!='' ) - {{ date('d/m/y', strtotime($rdv->End_AT)) }} @endif
                            {{ $rdv->Subject }} - 
                            {{ $rdv->heure_debut }} à {{ $rdv->heure_fin }}  
                            @if($rdv->statut==2) <span class="text-success"> Réalisé</span>  @elseif($rdv->statut==3) <span class="text-danger"> Annulé</span>  @else <span class="text-primary"> Planifié</span> @endif
                        </li>
                    @endforeach
                </ul>
                <div class="total">Total : <b>{{ count($rendezVous) }}</b> Rendez-vous</div>
            </div>
            @if(!$loop->last)
                <hr>
            @endif
        @endforeach
    @else
        <p style="margin-left: 20px; font-style: italic;">Aucun rendez-vous hors clientèles pour cette période.</p>
    @endif
    </ul>

    <div style="height: 30px;"></div>

    <!-- 2. Rendez-vous clientèles -->
    <div class="category-header">2. Rendez-vous clientèles</div>
    <ul class="event">
    @if(count($rendezVousClientelesByType) > 0)
        @foreach($rendezVousClientelesByType as $type => $rendezVous)
            <div class="rdv-list">
                <h3>Rendez-vous de type : <u>{{ $type ?: 'Non défini' }}</u></h3>
                <ul>
                    @foreach($rendezVous as $rdv)
                        <li class="rdv-item">
                            {{ date('d/m/y', strtotime($rdv->Started_at)) }} @if($rdv->Started_at != $rdv->End_AT &&  $rdv->End_AT!='') - {{ date('d/m/y', strtotime($rdv->End_AT)) }} @endif
                            {{ $rdv->Subject }} - 
                            <b>{{ $rdv->Account_Name }} | 
                            @php $client=\App\Models\CompteClient::find($rdv->AccountId); @endphp
                            {{ $client->cl_ident ?? '' }}</b> - 
                            {{ $rdv->heure_debut }} à {{ $rdv->heure_fin }}  
                            @if($rdv->statut==2) <span class="text-success"> Réalisé</span>  @elseif($rdv->statut==3) <span class="text-danger"> Annulé</span>  @else <span class="text-primary"> Planifié</span> @endif
                        </li>
                    @endforeach
                </ul>
                <div class="total">Total : <b>{{ count($rendezVous) }}</b> Rendez-vous</div>
            </div>
            @if(!$loop->last)
                <hr>
            @endif
        @endforeach
    @else
        <p style="margin-left: 20px; font-style: italic;">Aucun rendez-vous avec des clients pour cette période.</p>
    @endif
    </ul>
</div>

<footer>
CRM SAAMP - Document généré le {{date('d/m/Y H:i')}}
</footer>

@endsection
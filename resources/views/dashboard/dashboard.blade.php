@extends('layouts.back')

@section('content')

<?php

?>
<style>
  h5,
  b {
    width: 100%;
  }

  h5 {
    color: black;
  }

  .circle {
    padding: 1%;
    background-color: #2e3e4e;
    width: 150px;
    height: 150px;
    border-radius: 100%;

    text-align: center;
    font-size: 50px;
    line-height: 1em;
    color: white;
    font-weight: 100;
    margin-left: auto;
    margin-right: auto;
    margin-top: 5%;
    margin-bottom: 5%;
    /*Want to add some cut-out lines? Uncomment to view.
    border:2px #F2F2DF dashed; */
  }


  .circle2 {
    padding: 1%;
    background-color: #2e3e4e;
    width: 75px;
    height: 75px;
    border-radius: 100%;
    text-align: center;
    font-size: 25px;
    line-height: 25px;
    color: white;
    font-weight: 100;
    /*
		margin-left: auto;
		margin-right: auto;
    margin-left: 50px;
    margin-right: 50px;*/
    margin-top: 1%;
    margin-bottom: 3%;
    display: inline-block;
    cursor: pointer;
  }

  [data-title]:hover:after {
    opacity: 1;
    transition: all 0.1s ease 0.5s;
    visibility: visible;
  }

  [data-title]:after {
    content: attr(data-title);
    position: absolute;
    /*bottom: -1.6em;*/
    left: 100%;
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

  th {
    cursor: pointer;
  }
</style>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {
    'packages': ['corechart']
  });
  google.charts.setOnLoadCallback(drawChart);
  var options = {
    responsive: true,
    //title: 'TOP CLIENTS',
    colors: ['#e5e7e6', '#EEE6D8', '#DAAB3A', '#B67332', '#93441A'],
    is3D: true,
    titleTextStyle: {
      color: 'black',
      fontName: 'Nunito',
      fontSize: 18
    },
    legendTextStyle: {
      color: 'black',
      fontName: 'Nunito'
    },
    pieSliceTextStyle: {
      color: 'black',
      fontName: 'Nunito'
    },
    backgroundColor: 'transparent',
    //chartArea: {width: '90%', height: '90%'}
  };

  <?php if (auth()->user()->user_role == 3 || auth()->user()->user_role == 4 || auth()->user()->user_role == 6 || auth()->user()->user_role == 7 || auth()->user()->user_role == 8) { ?>

    function drawChart1() {

      <?php foreach ($customers as $key => $customer) {
      ?>

        var data = google.visualization.arrayToDataTable([
          ['Client', 'Chiffre d\'affaire'],
          <?php
          foreach ($customer as $cl) {
            echo '[' . json_encode($cl->nom) . ', ' . str_replace(' ', '', $cl->CA) . '],';
          }
          ?>

        ]);
        var chart = new google.visualization.PieChart(document.getElementById('piechart-' + <?php echo $key; ?>));
        chart.draw(data, options);
      <?php } ?>
    }

    google.charts.setOnLoadCallback(drawChart1);

  <?php

  }   ?>

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Client', 'Chiffre d\'affaire'],
      <?php
      foreach ($clients as $cl) {
        echo '[' . json_encode($cl->nom) . ', ' . str_replace(' ', '', $cl->CA) . '],';
      }
      ?>

    ]);
    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data, options);
  }
  <?php   ?>
</script>

<div class="" style="padding-left:5%;padding-right:5%;padding-top:2%;padding-bottom:2%">
  <div class="row">
    <div class="col-md-6 col-sm-12"></div>
    <div class="col-md-6 col-sm-12">
      @if(session()->get('hasClonedUser') == 1)
      <div class="alert alert-info">
        Connecté en tant que : <b>{{ auth()->user()->name }} {{ auth()->user()->lastname }}</b>
        <a href="{{ route('revert.login', session('previoususer')) }}" class="btn btn-warning btn-sm float-right">Revenir à l'utilisateur précédent</a>
      </div>
      @endif
    </div>
  </div>
  <div class="row mt-2 mb-3">
    @if(!$userToken)
    <div class="col-md-12 float-right mr-2 ml-2">
      <a href="{{ route('google.auth.redirect') }}" class="btn btn-primary float-right"><img width="40" style="width:40" src="{{  URL::asset('img/calendar.png') }}" /> Lier les rendez-vous à mon Agenda</a>
    </div>
    @endif
  </div>
  @if( auth()->user()->user_role==5)
  @php $totaux_clients=\App\Http\Controllers\DashboardController::totaux_clients(); @endphp
  <div class="row mb-5">
    <div class="col-md-6">
      <div class="col-md-3 col-lg-3 col-sm-6 text-center mb-4 " style="display:inline-block">
        <h5 class="text-center">Paris</h5>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_1']  }}</p>
        </div>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_1']   }}</p>
        </div>
      </div>

      <div class="col-md-3 col-lg-3 col-sm-6 text-center mb-4 " style="display:inline-block">
        <h5 class="text-center">Lyon</h5>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_2']   }}</p>
        </div>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_2']   }}</p>
        </div>
      </div>

      <div class="col-md-3 col-lg-3 col-sm-6 text-center mb-4 " style="display:inline-block">
        <h5 class="text-center"> Marseille</h5>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_3']   }}</p>
        </div>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_3']   }}</p>
        </div>
      </div>

      <div class="col-md-3 col-lg-3 col-sm-6 text-center mb-4 " style="display:inline-block">
        <h5 class="text-center"> Aubagne</h5>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_4']   }}</p>
        </div>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_4']   }}</p>
        </div>
      </div>

      <div class="col-md-3 col-lg-3 col-sm-6 text-center mb-4 " style="display:inline-block">
        <h5 class="text-center"> Varsovie</h5>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_5']  }}</p>
        </div>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_5']   }}</p>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-6 text-center mb-4 " style="display:inline-block">
        <h5 class="text-center"> Cayenne</h5>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_6']   }}</p>
        </div>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_6']   }}</p>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-6 text-center mb-4 " style="display:inline-block">
        <h5 class="text-center"> Nice</h5>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_7']  }}</p>
        </div>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_7']   }}</p>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-6 text-center mb-4 " style="display:inline-block">
        <h5 class="text-center"> Toulouse</h5>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_8']   }}</p>
        </div>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_8']  }}</p>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-6 text-center mb-4 " style="display:inline-block">
        <h5 class="text-center"> Bordeaux</h5>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Total des clients">{{ $totaux_clients['total_clients_9']  }}</p>
        </div>
        <div class="circle2">
          <p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $totaux_clients['total_9']  }}</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <h4 class="text-center">{{__('msg.Unclosed complaints')}}  <i class="fas fa-exclamation-triangle text-danger"></i></h4>
      <div class="table-container" style="margin-top:36px">
        <table id="" class="table table-striped" style="width:100%!important">
          <thead>
            <tr style="background-color:#2e3e4e;color:white;" id="">
              <th>{{__('msg.Title')}}</th>
              <th>{{__('msg.Open date')}}</th>
              <th>{{__('msg.Customer')}}</th>
              <th>{{__('msg.Contact')}}</th>
              <th>{{__('msg.Reason')}}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($retours as $retour)
            <tr>
              <td><a href="{{route('retours.show',['id'=>$retour->id])}}">{{$retour->Name}}</a></td>
              <td>{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</td>
              <td>{{$retour->Nom_du_compte}}</td>
              <td>{{$retour->Nom_du_contact}}</td>
              <td>{{$retour->Motif_retour}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>


  </div>
  @endif
  <div class="row">

    @if(auth()->user()->user_role==3 || auth()->user()->user_role==4 || auth()->user()->user_role==6 || auth()->user()->user_role== 7 || auth()->user()->user_role== 8)
      @if( auth()->user()->user_role== 6 || auth()->user()->user_role== 7 || auth()->user()->user_role== 8 )
        <div class="col-md-6 col-lg-6 col-sm-12 text-center  mb-5">
      @else
        <div class="col-md-4 col-lg-4 col-sm-12 text-center  mb-5">
      @endif
      <h4 class="black">{{__('msg.Number of customers')}}</h4>
      @if(auth()->user()->user_role!=7 && auth()->user()->user_role!=8  )
      <h5>{{$agence->agence_lib}}</small></h5>
      <div class="circle2">
        <p style="margin-top:revert" data-title="Total des clients">{{ $total_clients }}</p>
      </div>
      <div class="circle2">
        <p style="margin-top:revert" data-title="Clients ayant un chiffre d'affaire l'année courante">{{ $total_1 }}</p>
      </div>
      @else

      <div class="circle">
        <p style="margin-top:revert">{{ $total_clients }}</p>
      </div>
      @endif
    </div>

    @foreach($customers as $rep => $val)

      @php $user_id =DB::table("representant")->where('id',$rep)->first()->users_id ?? 0;
      $comm=\App\Models\User::find($user_id) ; @endphp
      @if(auth()->user()->user_role==7)
        @if($user_id== auth()->id()  )
        <div class="col-md-6 col-lg-6 col-sm-12">
          <h4 class="text-center">{{__('msg.Top customers')}}</h4>
          <div id="piechart-{{$rep}}" style="width:100%!important; height: 300px;"></div>
        </div>
        @endif
      @else
        <div class="col-md-4 col-lg-4 col-sm-12">
          <h4 class="text-center">{{__('msg.Top customers')}} {{__('msg.of')}} {{$comm->name ?? ''}} {{$comm->lastname ?? ''}}</h4>
          <div id="piechart-{{$rep}}" style="width:100%!important; height: 300px;"></div>
        </div>
      @endif

    @endforeach
    @else
    <!--
    <div class="col-md-12 col-lg-12 col-sm-12"></div>

    <div class="col-md-4 col-lg-4 col-sm-12">
      <h4 class="text-center black">{{__('msg.Top customers')}}</h4>
      <div id="piechart" style="width:100%!important; height: 300px;"></div>
    </div>
    -->
    @endif

    <!--

-->
    @if(auth()->user()->user_role==3)
    <!--
    <div class="col-md-4 col-lg-4 col-sm-12">
      <h4 class="text-center">  @if(auth()->user()->role=='respAG') {{__('msg.Top customers')}} @endif</h4>
      <table id="" class="table table-striped" style="width:100%!important">
        <thead>
          <tr style="background-color:#2e3e4e;color:white;" id="">
            <th>{{__('msg.Customer')}}</th>
            <th>{{__('msg.Turnover')}}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($clients as $cl)
          <tr>
            <td>{{$cl->nom}}</td>
            <td>{{$cl->CA}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

    </div>
-->
    @endif
    @if(auth()->user()->user_role==6)
    <div class="col-lg-12"></div>
    <div class="col-md-6 col-lg-6 col-sm-12">
      <h4 class="text-center">{{__('msg.Coming appointments')}}</h4>
      <br>
      <div class="table-container">
        <table id="" class="table table-striped" style="width:100%!important">
          <thead>
            <tr style="background-color:#2e3e4e;color:white;" id="">
              <th>ID</th>
              <th>{{__('msg.Customer')}}</th>
              <th>{{__('msg.Subject')}}</th>
              <th>{{__('msg.Date')}}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rendezvous as $rv)
            <tr>
              <td><a href="{{route('rendezvous.show',['id'=>$rv->id])}}">{{ $rv->id }}</a></td>
              <td>{{ $rv->Account_Name }}</td>
              <td>{{ $rv->Subject }}</td>
              <td>{{ date('d/m/Y', strtotime($rv->Started_at)) }} {{$rv->heure_debut}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-md-6 col-lg-6 col-sm-12 mb-5">
      <h4 class="text-center">{{__('msg.Unclosed complaints')}}  <i class="fas fa-exclamation-triangle text-danger"></i></h4>
      <div class="table-container" style="margin-top:36px">
        <table id="" class="table table-striped" style="width:100%!important">
          <thead>
            <tr style="background-color:#2e3e4e;color:white;" id="">
              <th>{{__('msg.Title')}}</th>
              <th>{{__('msg.Open date')}}</th>
              <th>{{__('msg.Customer')}}</th>
              <th>{{__('msg.Contact')}}</th>
              <th>{{__('msg.Reason')}}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($retours as $retour)
            <tr>
              <td><a href="{{route('retours.show',['id'=>$retour->id])}}">{{$retour->Name}}</a></td>
              <td>{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</td>
              <td>{{$retour->Nom_du_compte}}</td>
              <td>{{$retour->Nom_du_contact}}</td>
              <td>{{$retour->Motif_retour}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
    @if(auth()->user()->id== 10 || auth()->user()->id== 39 || auth()->user()->user_role < 4 ) <div class="col-md-4 col-lg-4 col-sm-12">
      <h4 class="text-center">{{__('msg.Price offers to be validated')}} <i class="fas fa-exclamation-triangle text-danger"></i></h4>
      @if(auth()->user()->id== 10 || auth()->user()->id== 39 )
      @if(count($offres)>0)<div class="text-danger">Vous avez <b>{!!count($offres)!!}</b> offres en attente de votre validation ! </div> @endif
      @endif
      <div class="table-container">
        <table id="" class="table table-striped" style="width:100%!important">
          <thead>
            <tr style="background-color:#2e3e4e;color:white;" id="">
              <th>ID</th>
              <th>{{__('msg.Creation')}}</th>
              <th>{{__('msg.Name')}}</th>
              <th>{{__('msg.Customer')}}</th>
              <th>{{__('msg.By')}}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($offres as $offre)
            @php $user=\App\Models\User::find($offre->user_id); @endphp
            <tr>
              <td><a href="{{route('offres.show',['id'=>$offre->id])}}">{{$offre->id}}</a></td>
              <td>{{ date('d/m/Y', strtotime($offre->Date_creation))}}</td>
              <td>{{$offre->Nom_offre}}</td>
              <td>{{$offre->nom_compte}}</td>
              <td>{{$user->name ?? ''}} {{$user->lastname ?? ''}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

  </div>
  @endif

  @if( auth()->user()->user_role == 3 || auth()->user()->user_role == 4 )
  <div class="col-md-4 col-lg-4 col-sm-12 mb-5">
    <h4 class="text-center">{{__('msg.Unclosed complaints')}}  <i class="fas fa-exclamation-triangle text-danger"></i></h4>
    <div class="table-container" style="margin-top:36px">
      <table id="" class="table table-striped" style="width:100%!important">
        <thead>
          <tr style="background-color:#2e3e4e;color:white;" id="">
            <th>{{__('msg.Title')}}</th>
            <th>{{__('msg.Open date')}}</th>
            <th>{{__('msg.Customer')}}</th>
            <th>{{__('msg.Contact')}}</th>
            <th>{{__('msg.Reason')}}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($retours as $retour)
          <tr>
            <td><a href="{{route('retours.show',['id'=>$retour->id])}}">{{$retour->Name}}</a></td>
            <td>{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</td>
            <td>{{$retour->Nom_du_compte}}</td>
            <td>{{$retour->Nom_du_contact}}</td>
            <td>{{$retour->Motif_retour}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif


  @if(auth()->user()->user_role < 5) 
    <div class="col-md-4 col-lg-4 col-sm-12 mb-5">
      <h4 class="text-center">Prospects</h4><br>
      <div class="table-container">
        <table id="prospects-table" class="table table-striped" style="width:100%!important">
          <thead>
            <tr style="background-color:#2e3e4e;color:white;" id="">
              <th data-field="Nom">{{__('msg.Name')}} <span class="sort-indicator"></span></th>
              <th data-field="ville">{{__('msg.City')}} <span class="sort-indicator"></span></th>
              <th data-field="zip">{{__('msg.Postal code')}} <span class="sort-indicator"></span></th>
              <th data-field="adresse1">{{__('msg.Address')}} <span class="sort-indicator"></span></th>
            </tr>
          </thead>
          <tbody>
            @foreach($prospects as $prospect)
            <tr>
              <td><a href="{{route('fiche',['id'=>$prospect->id])}}">{{$prospect->Nom}}</a></td>
              <td>{{ $prospect->ville }}</td>
              <td>{{ $prospect->zip }}</td>
              <td>{{ $prospect->adresse1 }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <a href="{{route('prospects')}}" target="_blank" class="btn btn-info mr-2 mt-5 float-right" id="print-prospects-btn">
        <i class="fa fa-print"></i> {{__('msg.Print')}}
      </a>
    </div>
  @endif

  <style>
    th {
      cursor: pointer;
      user-select: none;
      position: relative;
    }
    
    th:hover {
      background-color: rgba(255,255,255,0.1) !important;
    }
    
    th.asc .sort-indicator:after {
      content: ' ↑';
      color: #17a2b8;
    }
    
    th.desc .sort-indicator:after {
      content: ' ↓';
      color: #17a2b8;
    }
    
    .sort-indicator {
      float: right;
      margin-left: 5px;
    }
  </style>
@if(count($rendezvous_passes)>0 && auth()->user()->user_role==7 )
<div class="col-md-6 col-lg-6 col-sm-12">
  <h4 class="text-center">Rendez vous planifiés, mais passés</h4>

  <div class="table-container">
    <table id="" class="table table-striped" style="width:100%!important">
      <thead>
        <tr style="background-color:#2e3e4e;color:white;" id="">
          <th>ID</th>
          <th>{{__('msg.Customer')}}</th>
          <th>{{__('msg.Subject')}}</th>
          <th>{{__('msg.Date')}}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rendezvous_passes as $rv)
        <tr>
          <td><a href="{{route('rendezvous.show',['id'=>$rv->id])}}">{{ $rv->id }}</a></td>
          <td>{{ $rv->Account_Name }}</td>
          <td>{{ $rv->Subject }}</td>
          <td>{{ date('d/m/Y', strtotime($rv->Started_at)) }} {{$rv->heure_debut}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

@if( auth()->user()->user_role==7 || auth()->user()->user_role==8 || auth()->user()->user_role==4 )

  @if( auth()->user()->user_role==7 || auth()->user()->user_role==8 )
  <div class="col-md-6 col-lg-6 col-sm-12">
  @endif
  @if(  auth()->user()->user_role==4 )
  <div class="col-md-4 col-lg-4 col-sm-12">
  @endif
  <h4 class="text-center">{{__('msg.Coming appointments')}}</h4>
  <br>
  <div class="table-container">
    <table id="" class="table table-striped" style="width:100%!important">
      <thead>
        <tr style="background-color:#2e3e4e;color:white;" id="">
          <th>ID</th>
          <th>{{__('msg.Customer')}}</th>
          <th>{{__('msg.Subject')}}</th>
          <th>{{__('msg.Date')}}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rendezvous as $rv)
        <tr>
          <td><a href="{{route('rendezvous.show',['id'=>$rv->id])}}">{{ $rv->id }}</a></td>
          <td>{{ $rv->Account_Name }}</td>
          <td>{{ $rv->Subject }}</td>
          <td>{{ date('d/m/Y', strtotime($rv->Started_at)) }} {{$rv->heure_debut}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

  @if( auth()->user()->user_role==8 )
  <div class="col-md-6 col-lg-6 col-sm-12">
    <h4 class="text-center">Rendez vous planifiés, mais passés</h4>
    <br>
    <div class="table-container">
      <table id="" class="table table-striped" style="width:100%!important">
        <thead>
          <tr style="background-color:#2e3e4e;color:white;" id="">
            <th>ID</th>
            <th>{{__('msg.Customer')}}</th>
            <th>{{__('msg.Subject')}}</th>
            <th>{{__('msg.Date')}}</th>
          </tr>
        <tbody>
          @foreach($rendezvous_passes as $rv)
          <tr>
            <td><a href="{{route('rendezvous.show',['id'=>$rv->id])}}">{{ $rv->id }}</a></td>
            <td>{{ $rv->Account_Name }}</td>
            <td>{{ $rv->Subject }}</td>
            <td>{{ date('d/m/Y', strtotime($rv->Started_at)) }} {{$rv->heure_debut}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif

@endif

</div>

</div><!-- end -->


 
<div class="terms-popup-overlay" id="termsPopup" style="display: none;">
    <div class="terms-popup-container">
        <div class="terms-content" id="termsContent">
			<h1 class="text-center">Conditions d'utilisation du CRM</h1><br>
            
				<i>Version : 1.0 </i><br>
				<i>Date d'entrée en vigueur : 01 septembre 2024</i><br>
				<h5>Préambule</h5>
				Le système de Gestion de la Relation Client (CRM) de la SAAMP est un outil de travail essentiel destiné à optimiser la gestion de nos prospects, clients, partenaires et activités commerciales associées. Son utilisation est un privilège et implique des responsabilités. Les présentes Conditions Générales d'Utilisation et Politique de Confidentialité (ci-après "la Politique") définissent les règles que tout utilisateur doit respecter. L'accès et l'utilisation du CRM valent acceptation sans réserve de cette Politique.<br>
				<h5>Article 1 : Définitions</h5>
				CRM : Désigne l'outil logiciel et les bases de données associées fournis par SAAMP pour la gestion de la relation client.<br>
				Utilisateur : Toute personne employée ou mandatée par SAAMP autorisée à accéder et utiliser le CRM.<br>
				Données : Toute information enregistrée dans le CRM, incluant, sans s'y limiter, les informations sur les prospects, clients, contacts, entreprises, opportunités, interactions, contrats, ainsi que les données internes de l'entreprise.<br>
				Données Confidentielles : Toute Donnée non publique, incluant notamment : les données personnelles des contacts (conformément au RGPD), les détails commerciaux (tarifs spécifiques, conditions contractuelles), les informations financières (chiffres d'affaires, marges, coûts - même agrégés ou partiels), les stratégies commerciales, les notes internes, et toute information marquée comme confidentielle ou dont la nature est intrinsèquement confidentielle.<br>
				<h5>Article 2 : Accès et Sécurité</h5>
				2.1. L'accès au CRM est nominatif, personnel et strictement réservé aux Utilisateurs autorisés par SAAMP dans le cadre de leurs fonctions.<br>
				2.2. Les identifiants de connexion (nom d'utilisateur et mot de passe) sont strictement personnels et confidentiels. L'Utilisateur est seul responsable de leur conservation et de leur  sécurité. Il est interdit de les communiquer à des tiers, y compris à d'autres collaborateurs. 2.3. L'Utilisateur s'engage à choisir un mot de passe robuste et à le renouveler conformément aux politiques de sécurité de l'entreprise.<br>
				2.4. Toute suspicion d'accès non autorisé à son compte doit être immédiatement signalée au service Informatique et au supérieur hiérarchique.<br>
				2.5. L'Utilisateur s'engage à verrouiller sa session CRM lorsqu'il quitte son poste de travail, même momentanément.<br>
				<h5>Article 3 : Utilisation Autorisée</h5>
				3.1. Le CRM doit être utilisé exclusivement à des fins professionnelles, dans le cadre des missions confiées à l'Utilisateur par SAAMP.<br>
				3.2. Les utilisations autorisées incluent notamment :<br>
				* La saisie et la mise à jour des informations prospects, clients, contacts et entreprises.<br>
				* Le suivi des interactions commerciales et marketing (appels, emails, rendez-vous).<br>
				* La gestion du portefeuille d'opportunités commerciales.<br>
				* La génération de rapports standards autorisés via les fonctionnalités prévues.<br>
				* La collaboration interne sur les dossiers clients/prospects.<br>
				3.3. L'utilisation doit être loyale, éthique et respectueuse des lois et règlements en vigueur, notamment le Règlement Général sur la Protection des Données (RGPD).<br>
				<h5>Article 4 : Utilisation Interdite</h5>
				4.1. Il est strictement interdit d'utiliser le CRM à des fins personnelles, illégales, frauduleuses, diffamatoires, obscènes ou contraires à l'éthique.<br>
				4.2. Il est interdit de :<br>
				* Tenter d'accéder à des Données ou fonctionnalités pour lesquelles l'Utilisateur n'a pas reçu d'autorisation explicite.<br>
				* Introduire volontairement des virus, malwares ou tout autre code nuisible.<br>
				* Perturber ou tenter de perturber le bon fonctionnement du CRM.<br>
				* Stocker des informations sans rapport avec l'activité professionnelle légitime.<br>
				* Usurper l'identité d'un autre Utilisateur.<br>
				* Procéder à une extraction de données en dehors des fonctionnalités d'export standards et explicitement autorisées au sein du CRM pour les besoins directs de sa fonction.<br>
				Toute extraction massive ou non directement liée à une tâche opérationnelle immédiate requiert une autorisation préalable de la hiérarchie.<br>
				<h5>Article 5 : Qualité et Intégrité des Données</h5>
				5.1. L'Utilisateur est responsable de l'exactitude, de la complétude et de la mise à jour des Données qu'il saisit dans le CRM.<br>
				5.2. Les informations doivent être enregistrées de manière professionnelle, objective et factuelle.<br>
				5.3. La saisie des informations doit être effectuée en temps opportun pour garantir la fiabilité des Données pour l'ensemble des Utilisateurs et pour la prise de décision.<br>
				<h5>Article 6 : Confidentialité des Données</h5>
				6.1. Toutes les Données contenues dans le CRM sont considérées comme strictement confidentielles et constituent la propriété exclusive de la SAAMP. <br>
				6.2. L'Utilisateur s'engage à ne consulter que les Données nécessaires à l'accomplissement de ses missions (principe du "besoin d'en connaître").<br>
				6.3. Il est formellement interdit de communiquer, de divulguer ou de partager des Données Confidentielles (telles que définies à l'Article 1), que ce soit verbalement, par écrit, ou par voie électronique :<br>
				* À des tiers externes à l'entreprise (sauf autorisation expresse de la hiérarchie et dans le respect des accords de confidentialité éventuels).<br>
				* À d'autres collaborateurs internes qui n'ont pas un besoin légitime d'en connaître pour leurs propres fonctions.<br>
				6.4. Cette interdiction de communication s'applique de manière particulièrement stricte aux données financières sensibles, notamment les chiffres d'affaires et les marges, conformément aux notes internes spécifiques déjà diffusées. Leur communication est restreinte aux canaux de reporting officiels.<br>
				6.5. L'Utilisateur s'engage à respecter la confidentialité des Données même après la cessation de son contrat de travail.<br>
				<h5>Article 7 : Extraction et Partage de Données</h5>
				7.1. Réitération : L'extraction de Données est limitée aux fonctionnalités standards du CRM autorisées pour le profil de l'Utilisateur et uniquement pour des besoins professionnels légitimes et directs. Toute autre forme d'extraction est prohibée sans autorisation formelle.<br>
				7.2. Le partage de Données extraites (même autorisées) doit respecter scrupuleusement les règles de confidentialité énoncées à l'Article 6.<br>
				<h5>Article 8 : Propriété Intellectuelle</h5>
				Toutes les Données saisies, générées ou stockées dans le CRM, ainsi que la structure de la base de données et les développements spécifiques éventuels, sont et demeurent la propriété intellectuelle exclusive de la SAAMP.<br>
				<h5>Article 9 : Surveillance et Audit</h5>
				La SAAMP se réserve le droit de surveiller l'utilisation du CRM à des fins de sécurité, de maintenance, d'amélioration des performances et de vérification du respect de la présente Politique, dans le respect de la législation applicable en France et dans l'Union Européenne concernant la vie privée des employés.<br>
				<h5>Article 10 : Non-Respect de la Politique et Sanctions</h5>
				Tout manquement aux dispositions de la présente Politique constitue une faute et expose l'Utilisateur à des mesures disciplinaires pouvant aller jusqu'au licenciement pour faute grave, sans préjudice d'éventuelles poursuites judiciaires civiles ou pénales si le manquement cause un préjudice à l'entreprise ou contrevient à la loi.<br>
				<h5>Article 11 : Modifications de la Politique</h5>
				La SAAMP se réserve le droit de modifier la présente Politique à tout moment. Les Utilisateurs seront informés des modifications substantielles par les canaux de communication internes habituels. La poursuite de l'utilisation du CRM après notification des modifications vaut acceptation de la nouvelle Politique.<br>
				<h5>Article 12 : Droit Applicable</h5>
				La présente Politique est régie par le droit français.<br>

        </div>
        
        <button id="acceptTermsButton" class="btn btn-primary float-right" disabled>
            J'accepte les conditions
        </button>
    </div>
</div>

<style>
    .terms-popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.8);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .terms-popup-container {
        background: white;
        width: 80%;
        max-width: 800px;
        max-height: 80vh;
        padding: 20px;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .terms-content {
        overflow-y: auto;
        margin-bottom: 20px;
        flex-grow: 1;
    }
    
    #acceptTermsButton:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    body.terms-popup-open {
        overflow: hidden;
    }
	.terms-content h5{
		margin-top:10px;
	}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si la popup doit être affichée
	let _token = $('input[name="_token"]').val();

	$.ajax({
        url: "{{ route('terms.check') }}",
        method: "GET",
        data: {  _token: _token},
        success: function (data) {
			 
			if (data==0) {
				showTermsPopup();
            	}
			}
	});
 
  function showTermsPopup() {
      const popup = document.getElementById('termsPopup');
      const content = document.getElementById('termsContent');
      const acceptButton = document.getElementById('acceptTermsButton');
      
      // Display popup and block scroll
      popup.style.display = 'flex';
      document.body.classList.add('terms-popup-open');
      
      // Initially disable the button
      acceptButton.disabled = true;
      
      // Check scroll with a more reliable method
      content.addEventListener('scroll', function() {
          // More reliable cross-browser way to check if scrolled to bottom
          // Add a small buffer (2px) to account for browser rounding differences
          const scrolledToBottom = 
              Math.abs((content.scrollHeight - content.scrollTop) - content.clientHeight) < 2;
          
          if (scrolledToBottom) {
              acceptButton.disabled = false;
          }
      });
      
      // Handle acceptance
      acceptButton.addEventListener('click', function() {
          $.ajax({
              url: "{{ route('terms.accept') }}",
              method: "POST",
              data: { _token: _token },
              success: function(data) {
                  if (data == 1) {
                      popup.style.display = 'none';
                      document.body.classList.remove('terms-popup-open');
                  }
              }
          });
      });
  }
});
</script>
  



<!-- maintenance Modal-->
<div class="modal fade" id="maintenance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Bienvenue sur le CRM </h6>
        <!--<button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>-->
      </div>
      <div class="modal-body  ">
        <div style="font-size:14px;">
          <h5>1. Workflow pour la gestion des offres dans le CRM</h5>
          Vos offres de prix suivent désormais un scénario précis:<br>
          <b>Si votre offre concerne du "TG"</b>, elle est automatiquement validée.<br>
          <b>Si elle concerne du "Hors TG"</b>, elle sera mise en attente de validation par <b>Sébastien Cannesson</b>. Vous recevrez une notification par mail vous informant si l'offre est validée ou à corriger.<br>
          <b>Si l'offre concerne les apprêts, la bijouterie ou le DP</b>, même principe, mais c’est <b>Christelle Correia</b> qui se chargera de la valider.<br>
          <br>
          <h5>2. Enfin un support !</h5>
          Vous l’avez peut-être remarqué : un nouvel onglet <b>Support</b> est disponible!<br>
          Il vous permet de créer des <b>tickets</b> pour poser vos questions, signaler des bugs ou faire des suggestions.<br>
          <b>Fini les mails et les appels !</b> Vous bénéficiez désormais d’un suivi clair de vos tickets et d’un système de <b>chat intégré</b> pour plus de réactivité.<br>
          <br>
          <h5>3. Votre agenda évolue !</h5>
          <b>Nouveauté 1:</b> Vous pouvez désormais sélectionner une plage de dates et imprimer la liste de vos rendez-vous. Une solution idéale pour joindre cette liste en pièce jointe à vos notes de frais!<br>
          <b>Nouveauté 2:</b> Vous avez sûrement remarqué un nouveau bouton sur votre tableau de bord: il permet de <strong style="color:black">lier votre Google Agenda à votre Agenda CRM</strong>. Désormais, lorsque vous créez un rendez-vous dans la CRM, celui-ci est automatiquement ajouté à votre Google Agenda.<br>
          <b>Et bientôt:</b> Nous travaillons sur une synchronisation dans l’autre sens: les rendez-vous créés dans votre Google Agenda seront automatiquement intégrés à votre Agenda CRM.<br>
          <br>
          <b>Restez connectés pour découvrir encore plus de nouveautés lors de nos prochaines mises à jour!</b><br>

        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {
  // Variables pour stocker l'état du tri
  let currentSortColumn = null;
  let currentSortOrder = null;

  // Function to detect and parse dates in various common formats, including dd/mm/yyyy
  function parseDate(dateString) {
    const datePattern = /^\d{2}\/\d{2}\/\d{4}$/;
    if (datePattern.test(dateString)) {
      // Parse dd/mm/yyyy format
      const [day, month, year] = dateString.split('/');
      return new Date(year, month - 1, day); // JavaScript months are 0-based
    } else {
      // Fallback: try parsing with Date constructor if another format is detected
      const parsedDate = new Date(dateString);
      return isNaN(parsedDate) ? null : parsedDate;
    }
  }

  // Fonction pour mettre à jour l'URL d'impression avec les paramètres de tri
  function updatePrintUrl() {
    const printButton = $('.btn-info[href*="prospects"]');
    if (printButton.length && currentSortColumn !== null && currentSortOrder !== null) {
      const baseUrl = printButton.attr('href').split('?')[0];
      const newUrl = `${baseUrl}?sort_column=${currentSortColumn}&sort_order=${currentSortOrder}`;
      printButton.attr('href', newUrl);
    }
  }

  // Fonction pour mapper l'index de colonne au nom de champ
  function getColumnFieldName(columnIndex) {
    const fieldMapping = {
      0: 'Nom',        // Nom
      1: 'ville',      // City
      2: 'zip',        // Postal code
      3: 'adresse1'    // Address
    };
    return fieldMapping[columnIndex] || null;
  }

  // Sorting function for tables
  $('th').on('click', function() {
    const $header = $(this);
    const $table = $header.closest('table');
    const $tbody = $table.find('tbody');
    const rows = $tbody.find('tr').toArray();
    const columnIndex = $header.index();
    const order = $header.hasClass('asc') ? 'desc' : 'asc';

    // Stocker l'état du tri
    currentSortColumn = getColumnFieldName(columnIndex);
    currentSortOrder = order;

    // Remove sorting classes from other headers in the same table
    $header.siblings().removeClass('asc desc');
    $header.addClass(order);

    // Sort rows with enhanced date handling
    rows.sort(function(rowA, rowB) {
      const cellA = $(rowA).find('td').eq(columnIndex).text().trim();
      const cellB = $(rowB).find('td').eq(columnIndex).text().trim();

      // Detect if both cells contain valid dates
      const dateA = parseDate(cellA);
      const dateB = parseDate(cellB);

      let valA, valB;

      if (dateA && dateB) {
        // Both are dates, compare them
        valA = dateA;
        valB = dateB;
      } else if ($.isNumeric(cellA) && $.isNumeric(cellB)) {
        // Parse and compare numeric values
        valA = parseFloat(cellA);
        valB = parseFloat(cellB);
      } else {
        // Compare as strings alphabetically
        valA = cellA.toLowerCase();
        valB = cellB.toLowerCase();
      }

      if (order === 'asc') {
        return valA > valB ? 1 : -1;
      } else {
        return valA < valB ? 1 : -1;
      }
    });

    // Append sorted rows to the table
    $.each(rows, function(index, row) {
      $tbody.append(row);
    });

    // Mettre à jour l'URL d'impression
    updatePrintUrl();
  });

  // Ajouter un indicateur visuel de tri dans les en-têtes
  $('th').css('cursor', 'pointer').append(' <span class="sort-indicator"></span>');
  
  // Styles CSS pour les indicateurs de tri
  $('<style>')
    .prop('type', 'text/css')
    .html(`
      th.asc .sort-indicator:after { content: ' ↑'; }
      th.desc .sort-indicator:after { content: ' ↓'; }
      th:hover { background-color: rgba(255,255,255,0.1); }
    `)
    .appendTo('head');
});

</script>

@endsection
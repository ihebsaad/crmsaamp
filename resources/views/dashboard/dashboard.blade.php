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
		margin-right: auto;*/
    margin-left: 50px;
    margin-right: 50px;
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

  <?php if (auth()->user()->role == 'respAG') { ?>

    function drawChart1() {

      <?php foreach ($customers as $key => $customer) {
      ?>

        var data = google.visualization.arrayToDataTable([
          ['Client', 'Chiffre d\'affaire'],
          <?php
          foreach ($customer as $cl) {
            echo '[' . json_encode($cl->nom) . ', ' . str_replace(' ','',$cl->CA). '],';
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
        echo '[' . json_encode($cl->nom) . ', ' . str_replace(' ','',$cl->CA) . '],';
      }
      ?>

    ]);
    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data, options);
  }
  <?php   ?>
</script>

<div class="" style="padding-left:5%;padding-right:5%;padding-top:2%;padding-bottom:2%">
  <div class="row mt-2 mb-3">
    @if(!$userToken)
    <div class="col-md-12 float-right mr-2 ml-2">
      <a href="{{ route('google.auth.redirect') }}" class="btn btn-primary float-right"><img width="40" style="width:40" src="{{  URL::asset('img/calendar.png') }}" /> Lier les rendez-vous à mon Agenda</a>
    </div>
    @endif
  </div>
  <div class="row">
    <div class="col-md-6 col-lg-6 col-sm-12 text-center  mb-5">
      <h4 class="black">{{__('msg.Number of customers')}}</h4>
      @if(auth()->user()->role=='adv' || auth()->user()->role=='respAG' )
      <h5>{{$agence->lib}}</small></h5>
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
      <!--<h1><b>{{ $total_clients }}</b></h1>-->
    </div>


    @if(auth()->user()->role=='respAG')
    @foreach($customers as $rep => $val)
    <div class="col-md-6 col-lg-6 col-sm-12">
      @php $user_id =DB::table("representant")->where('id',$rep)->first()->users_id ?? 0;
      $comm=\App\Models\User::find($user_id) ; @endphp

      <h4 class="text-center">{{__('msg.Top customers')}} {{__('msg.of')}} {{$comm->name ?? ''}} {{$comm->lastname ?? ''}} </h4>
      <div id="piechart-{{$rep}}" style="width:100%!important; height: 300px;"></div>
    </div>
    @endforeach
    <div class="col-md-12 col-lg-12 col-sm-12"></div>
    @else
    <div class="col-md-6 col-lg-6 col-sm-12">
      <h4 class="text-center black">{{__('msg.Top customers')}}</h4>
      <div id="piechart" style="width:100%!important; height: 300px;"></div>
    </div>
    @endif

    <div class="col-md-6 col-lg-6 col-sm-12">
      <h4 class="text-center">{{__('msg.Coming appointments')}}</h4>
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
    @if(auth()->user()->role!='respAG')
    <div class="col-md-6 col-lg-6 col-sm-12">
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
    @endif

    @if(auth()->user()->id== 10 || auth()->user()->id== 39 || auth()->user()->user_type=='admin'|| auth()->user()->role=='admin' )
    <div class="col-md-6 col-lg-6 col-sm-12">
      <h4 class="text-center">{{__('msg.Price offers to be validated')}}</h4>
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

    @if( auth()->user()->role=='adv'|| auth()->user()->role=='admin' || auth()->user()->role=='respAG' )
    <div class="col-md-6 col-lg-6 col-sm-12 mb-5">
      <h4 class="text-center">{{__('msg.Unclosed complaints')}}</h4>
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


    @if(auth()->user()->role=='respAG' || auth()->user()->role=='respAG')
    <div class="col-md-6 col-lg-6 col-sm-12 mb-5">
      <h4 class="text-center">Prospects</h4>
      <div class="table-container">
        <table id="" class="table table-striped" style="width:100%!important">
          <thead>
            <tr style="background-color:#2e3e4e;color:white;" id="">
              <th>{{__('msg.Name')}}</th>
              <th>{{__('msg.City')}}</th>
              <th>{{__('msg.Postal code')}}</th>
              <th>{{__('msg.Address')}}</th>
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
      <a href="{{route('prospects')}}" target="_blank" class="btn btn-info mr-2 mt-5 float-right"><i class="fa fa-print"></i> {{__('msg.Print')}}</a>

    </div>
    @endif
  @if(count($rendezvous_passes)>0)
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
  </div>

</div>


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
    setTimeout(function() {
      $('#maintenance').modal('show');
    }, 5000); // 5000 milliseconds = 5 seconds

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

    // Sorting function for tables
    $('th').on('click', function() {
      const $header = $(this);
      const $table = $header.closest('table');
      const $tbody = $table.find('tbody');
      const rows = $tbody.find('tr').toArray();
      const columnIndex = $header.index();
      const order = $header.hasClass('asc') ? 'desc' : 'asc';

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
    });
  });
</script>


@endsection
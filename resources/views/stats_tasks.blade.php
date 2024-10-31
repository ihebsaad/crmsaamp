@extends('layouts.back')
<style>
    table td:not(.text) {
        text-align: right;
    }

    .mn5 {
        min-height: 500px !important;
    }

    .table-container {
        position: relative;
        height: 400px;
        /* Ajustez cette hauteur selon vos besoins */
        overflow-y: auto;
    }

    .table-container thead th {
        position: sticky;
        top: 0;
        background-color: #e6d685;
        /* Couleur de fond pour l'en-tête */
        z-index: 10;
        /* S'assurer que l'en-tête reste au-dessus des autres éléments */
    }

    #stats tr:first-child,
    #stats2 tr:first-child,
    #stats3 tr:first-child,
    #stats4 tr:first-child,
    #stats5 tr:first-child,
    #stats6 tr:first-child ,
    #stats7 tr:first-child {
        background-color: cornsilk;
    }

    table td:first-child {
        font-weight: bold;
    }
    #ui-datepicker-div{
        z-index:99!important;
    }

    /* Mobiles
@media (max-width: 767px) {
    .table td{
        font-size:9px!important;
    }
}
*/
</style>


@section('content')

<div class="row">

    <input type="hidden" id="user_id" value="{{ auth()->user()->id }}" />

</div>

<div class="row">

    <div class="col-lg-6 col-sm-12  mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Activités des agences par période</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <form method="get"  action="{{route('stats_tasks')}}" id="myform" style="display:inline-block;width:70%" >
                        <span class="text-primary mr-2">Début</span><input   class="form-control datepicker" id="debut" name="debut" value="{{$debut}}" style="width:150px" onchange="update_stats();" /><span class="text-primary ml-3 mr-2">Fin</span><input  class="form-control datepicker" id="fin" name="fin" value="{{$fin}}" style="width:150px" onchange="update_stats();" />
                    </form>
                </div>
                <div class="table-container mn5">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                            <th class="">Type de contact </th>
                                <th class="">PARIS</th>
                                <th class="">LYON</th>
                                <th class="">MARSEILLE</th>
                                <th class="">NICE</th>
                                <th class="">TOULOUSE</th>
                                <th class="">BORDEAUX</th>
                                <th class="">LIMONEST</th>
                                <th class="">CAYENNE</th>
                                <th class="">VARSOVIE</th>
                                <th class="">AUBAGNE</th>
                            </tr>
                        </thead>
                        <tbody id="stats">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Activités par agence</h6>
            </div>
            <div class="card-body">
                    <span class="text-primary">{{__('msg.Agency')}}:</span>
                    <select class="form-control mb-20" id="agence" onchange="update_stats();" style="max-width:500px">
                        <option></option>
                        @foreach ($agences as $agence)
                            <option @selected(auth()->user()->agence_ident==$agence->agence_ident) value="{{$agence->agence_ident}}">{{$agence->agence_lib}}    |  <small>{{$agence->adresse1}}</small></option>
                        @endforeach
                    </select>
                <div class="table-container mn5">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="" scope="col">Type de contact </th>
                                <th class="">S0</th>
                                <th class="">S1</th>
                                <th class="">S2</th>
                                <th class="">S3</th>
                                <th class="">S4</th>
                                <th class="">S5</th>
                                <th class="">S6</th>
                                <th class="">S7</th>
                                <th class="">S8</th>
                                <th class="">S9</th>
                                <th class="">S10</th>
                            </tr>
                        </thead>
                        <tbody id="stats2">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!--
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
-->
<script>
    function update_stats() {
        var _token = $('input[name="_token"]').val();
        var agence = $('#agence').val();
        var debut = $('#debut').val();
        var fin = $('#fin').val();

        $.ajax({
            url: "{{ route('stats_actvivites_semaine') }}",
            method: "get",
            data: {
                _token: _token,
                debut: debut,
                fin: fin,
            },
            success: function(data) {
                //onsole.log(data);
                var html = '';
                data.forEach(item => {
                    html += '<tr><td class="text">' + item.titre_type_contact + '</td><td>' + item.PARIS + '</td><td>' + item.LYON + '</td><td>' + item.MARSEILLE + '</td><td>' + item.NICE + '</td><td>' + item.TOULOUSE + '</td><td>' + item.BORDEAUX + '</td><td>' + item.LIMONEST + '</td><td>' + item.CAYENNE + '</td><td>' + item.VARSOVIE + '</td><td>' + item.AUBAGNE + '</td></tr>';
                });
                $("#stats").html(html);
            }
        });


        $.ajax({
            url: "{{ route('stats_actvivites') }}",
            method: "get",
            data: {
                _token: _token,
                agence: agence,
            },
            success: function(data) {
                //console.log(data);
                var html = '';
                data.forEach(item => {
                    html += '<tr><td class="text">' + item.titre_type_contact + '</td><td>' + item.S0 + '</td><td>' + item.S1 + '</td><td>' + item.S2 + '</td><td>' + item.S3 + '</td><td>' + item.S4 + '</td><td>' + item.S5 + '</td><td>' + item.S6 + '</td><td>' + item.S7 + '</td><td>' + item.S8 + '</td><td>' + item.S9 + '</td><td>' + item.S10 + '</td></tr>';
                });
                $("#stats2").html(html);
            }
        });

    }

    update_stats();

    $(function () {

        $( ".datepicker" ).datepicker({

            //altField: "#datepicker",
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            buttonImage: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAATCAYAAAB2pebxAAABGUlEQVQ4jc2UP06EQBjFfyCN3ZR2yxHwBGBCYUIhN1hqGrWj03KsiM3Y7p7AI8CeQI/ATbBgiE+gMlvsS8jM+97jy5s/mQCFszFQAQN1c2AJZzMgA3rqpgcYx5FQDAb4Ah6AFmdfNxp0QAp0OJvMUii2BDDUzS3w7s2KOcGd5+UsRDhbAo+AWfyU4GwnPAYG4XucTYOPt1PkG2SsYTbq2iT2X3ZFkVeeTChyA9wDN5uNi/x62TzaMD5t1DTdy7rsbPfnJNan0i24ejOcHUPOgLM0CSTuyY+pzAH2wFG46jugupw9mZczSORl/BZ4Fq56ArTzPYn5vUA6h/XNVX03DZe0J59Maxsk7iCeBPgWrroB4sA/LiX/R/8DOHhi5y8Apx4AAAAASUVORK5CYII=",
            firstDay: 1,
            dateFormat: "yy-mm-dd",
            //minDate:0
        });
    });

</script>
@endsection
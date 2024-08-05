@extends('layouts.back')
<style>
table td:not(.text){
    text-align:right;
}
.mn5{
    min-height:500px!important;
}

.table-container {
    position: relative;
    height: 400px; /* Ajustez cette hauteur selon vos besoins */
    overflow-y: auto;
}

.table-container thead th {
    position: sticky;
    top: 0;
    background-color: #e6d685; /* Couleur de fond pour l'en-tête */
    z-index: 10; /* S'assurer que l'en-tête reste au-dessus des autres éléments */
}
#stats tr:first-child,
#stats2 tr:first-child,
#stats3 tr:first-child,
#stats4 tr:first-child,
#stats5 tr:first-child{
    background-color: cornsilk;
}
table td:first-child{
    font-weight:bold;
}
</style>
@section('content')

<div class="row">

<input type="hidden" id="user_id" value="{{ auth()->user()->id }}" />

    @if($commercial)
        <input type="hidden" id="commercial" value="{{ \DB::table('representant')->where('users_id',auth()->user()->id)->first()->id ?? 0 }}" />
    @else
        @if( auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv' )
            <div class="col-lg-4">
                <span class=" mr-2">Commercial:</span>
                <select class="form-control mb-20" id="commercial" onchange="update_stats();" style="max-width:300px">
                    @foreach ($representants as $rp)
                    <option @selected(auth()->user()->id==$rp->id) value="{{$rp->users_id}}" data-id="{{$rp->id}}">{{$rp->nom}}  {{$rp->prenom}}</option>
                    @endforeach
                </select>
            </div>

        @else
            <input type="hidden" id="commercial" value="{{ \DB::table('representant')->where('users_id',auth()->user()->id)->first()->id ?? 0 }}" >
        @endif
    @endif

    <div class="col-lg-4 mt-4">
        <input id="mois" type="checkbox" value="1" onchange="update_stats();" >
        <label class="mt-2" for="mois">Afficher les années pleines</label>
        </input>
    </div>
</div>

<div class="row">

    <div class="col-lg-6 col-sm-12  mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Par Métier</h6>
            </div>
            <div class="card-body">
                <div class="table-container mn5">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="">Métier</th>
                                <th class="text-center">{{ date('Y'); }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-1; }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-2; }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-3; }}</th>
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
                <h6 class="m-0 font-weight-bold text-primary">Par Client</h6>
            </div>
            <div class="card-body">
                <div class="table-container mn5">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="">Métier</th>
                                <th class="text-center">{{ date('Y'); }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-1; }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-2; }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-3; }}</th>
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

<div class="row">
    <div class="col-lg-4">
        <span class=" mr-2">Agence:</span><select class="form-control mb-20" id="agence" onchange="update_stats();" style="max-width:300px">
            <option></option>
            @foreach ($agences as $agence)
                <option @selected(auth()->user()->agence_ident==$agence->agence_ident) value="{{$agence->agence_ident}}">{{$agence->agence_lib}}    |  <small>{{$agence->adresse1}}</small></option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">

    <div class="col-lg-6 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Par Métier</h6>
            </div>
            <div class="card-body">
                <div class="table-container mn5">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="">Métier</th>
                                <th class="text-center">{{ date('Y'); }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-1; }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-2; }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-3; }}</th>
                            </tr>
                        </thead>
                        <tbody id="stats3">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Par Client</h6>
            </div>
            <div class="card-body">
                <div class="table-container mn5">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="">Métier</th>
                                <th class="text-center">{{ date('Y'); }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-1; }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-2; }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-3; }}</th>
                            </tr>
                        </thead>
                        <tbody id="stats4">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Toutes les agences</h6>
            </div>
            <div class="card-body">
                <div class="table-container mn5">
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="">Agence</th>
                                <th class="text-center">{{ date('Y'); }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-1; }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-2; }}</th>
                                <th class=""></th>
                                <th class="text-center">{{ date('Y')-3; }}</th>
                            </tr>
                        </thead>
                        <tbody id="stats5">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    function update_stats() {
        var _token = $('input[name="_token"]').val();
        var agence = $('#agence').val();
        var user = ($('#user_id').val());
        var representant = ($('#commercial').find(':selected').data('id'));
        if (typeof representant === 'undefined'){
            representant = $('#commercial').val();
        }
        var mois = 1;
		    if ($('#mois').is(':checked')){
                mois = 0;
            };

        $.ajax({
            url: "{{ route('stats_commercial') }}",
            method: "get",
            data: {
                _token: _token,
                agence: agence,
                mois: mois,
                user: representant,
            },
            success: function(data) {
                console.log(data);
                var html = '';
                data.forEach(item => {
                    html += '<tr><td class="text">' + item.metier + '</td><td>' + item.N + '</td><td>' + item.delta_1 + '</td><td>' + item.N_1 + '</td><td>' + item.delta_2 + '</td><td>' + item.N_2 + '</td><td>' + item.delta_3 + '</td><td>' + item.N_3 + '</td></tr>';
                });
                $("#stats").html(html);
            }
        });


        $.ajax({
            url: "{{ route('stats_commercial_client') }}",
            method: "get",
            data: {
                _token: _token,
                agence: agence,
                mois: mois,
                user: representant,
            },
            success: function(data) {
                console.log(data);
                var html = '';
                data.forEach(item => {
                    html += '<tr><td class="text">' + item.nom + '</td><td>' + item.N + '</td><td>' + item.delta_1 + '</td><td>' + item.N_1 + '</td><td>' + item.delta_2 + '</td><td>' + item.N_2 + '</td><td>' + item.delta_3 + '</td><td>' + item.N_3 + '</td></tr>';
                });
                $("#stats2").html(html);
            }
        });

        $.ajax({
            url: "{{ route('stats_agence') }}",
            method: "get",
            data: {
                _token: _token,
                agence: agence,
                mois: mois,
                user: user,
            },
            success: function(data) {
                console.log(data);
                var html = '';
                data.forEach(item => {
                    html += '<tr><td class="text">' + item.metier + '</td><td>' + item.N + '</td><td>' + item.delta_1 + '</td><td>' + item.N_1 + '</td><td>' + item.delta_2 + '</td><td>' + item.N_2 + '</td><td>' + item.delta_3 + '</td><td>' + item.N_3 + '</td></tr>';
                });
                $("#stats3").html(html);
            }
        });

        $.ajax({
            url: "{{ route('stats_agence_client') }}",
            method: "get",
            data: {
                _token: _token,
                agence: agence,
                mois: mois,
                user: user,
            },
            success: function(data) {
                console.log(data);
                var html = '';
                data.forEach(item => {
                    html += '<tr><td class="text">' + item.nom + '</td><td>' + item.N + '</td><td>' + item.delta_1 + '</td><td>' + item.N_1 + '</td><td>' + item.delta_2 + '</td><td>' + item.N_2 + '</td><td>' + item.delta_3 + '</td><td>' + item.N_3 + '</td></tr>';
                });
                $("#stats4").html(html);
            }
        });


        $.ajax({
            url: "{{ route('stats_agences') }}",
            method: "get",
            data: {
                _token: _token,
                agence: agence,
                mois: mois,
                user: user,
            },
            success: function(data) {
                console.log(data);
                var html = '';
                data.forEach(item => {
                    html += '<tr><td class="text">' + item.Agence + '</td><td>' + item.N + '</td><td>' + item.delta_1 + '</td><td>' + item.N_1 + '</td><td>' + item.delta_2 + '</td><td>' + item.N_2 + '</td><td>' + item.delta_3 + '</td><td>' + item.N_3 + '</td></tr>';
                });
                $("#stats5").html(html);
            }
        });

    }

    update_stats();
</script>
@endsection
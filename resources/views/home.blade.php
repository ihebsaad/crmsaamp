@extends('layouts.back')
<style>
    .exports,.export-buttons .btn {
        float:right;
        background-color: #1cc88a;
        padding:8px 8px 8px 8px;
        margin-bottom:10px;
    }
    .export-buttons i{
        font-size: 16px!important;
    }
	.card-body i{
		font-size:10px;
	}
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
        margin-bottom:10px;
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
    #stats6 tr:first-child,
    #stats7 tr:first-child {
        background-color: cornsilk;
    }

    table td:first-child {
        font-weight: bold;
    }
    .tab-pane{
        padding-top:25px;
        padding-bottom:25px;
        padding-left:15px;
        padding-right:15px;
    }
    .nav-link{
        color:#4e73df;width:125px;text-align:center;font-weight:bold;
    }
    #myTab1 .nav-link i, #myTab2 .nav-link i {
        color: #4e73df !important;
    }
    .nav-link.active{
        color:#4e73df!important;
    }

    .tab-pane i{
        font-size:9px;
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
    <div class="col-md-6"></div>
    <div class="col-md-6  ">
        @if(session()->get('hasClonedUser') == 1)
            <div class="alert alert-info">
                Connecté en tant que : <b>{{ auth()->user()->name }} {{ auth()->user()->lastname }}</b>
                <a href="{{ route('revert.login', session('previoususer')) }}" class="btn btn-warning btn-sm float-right">Revenir à l'utilisateur précédent</a>
            </div>
        @endif
    </div>
</div>
<div class="row">
    <input type="hidden" id="user_id" value="{{ auth()->user()->id }}" />

    <div class="col-lg-12 mt-4 text-right">
        <input id="mois" type="checkbox" value="1" onchange="update_checkbox('mois','mois_alt');">
        <label class="mt-2" for="mois">{{__('msg.Show full years')}}</label>
        </input>
    </div>
</div>
<div class="row">
    
    <div class="col-lg-6 col-sm-12  mb-4">
        <h3>Mes Statistiques</h3>
        <ul class="nav nav-tabs card-header" id="myTab2" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="clients-tab" data-toggle="tab" href="#clients" role="tab" aria-controls="clients" aria-selected="true" style="">{{__('msg.By')}} Client</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="jobs-tab" data-toggle="tab" href="#jobs" role="tab" aria-controls="jobs" aria-selected="false" style="">{{__('msg.By')}} {{__('msg.Job')}}</a>
			</li>
        @if( auth()->user()->user_role > 0 && auth()->user()->user_role < 5 || auth()->user()->user_role==8 || auth()->user()->id==10  /*&&  auth()->id() != 334*/ )
        <li class="nav-item">
            <span class="ml-2 mr-2">{{__('msg.Type')}}:</span>
            <select class="form-control" id="type" style="max-width:160px">
                <option value="Commercial terrain">Commercial terrain</option>
                <option value="Contact client siège">Contact client siège</option>
                <option value="Collecteur Externe">Collecteur Externe</option>
            </select>
        </li>
        <li class="nav-item">
            <span class="ml-2 mr-2">{{__('msg.Commercial')}}:</span>
            <select class="form-control" id="commercial" onchange="update_stats();" style="max-width:160px">
                @foreach ($representants as $rp)
                <option @selected(auth()->user()->id==$rp->id) value="{{$rp->users_id}}" data-id="{{$rp->id}}" data-type="{{$rp->type}}" >{{$rp->nom}}  {{$rp->prenom}}</option>
                @endforeach
            </select>
        </li>

        @elseif( false /*auth()->id() == 334*/ )
        <span class=" mt-4 ml-2 mr-2">{{__('msg.Commercial')}}:</span>
            <select class="form-control" id="commercial" onchange="update_stats();" style="max-width:150px">
                <option  value="334" data-id="400"  data-type="Commercial terrain" >Stéphane Devès</option>
				<option   value="141" data-id="40"  data-type="Commercial terrain" >Patricia Delmas</option>
            </select>
        @else
            <input type="hidden" id="commercial" value="{{ \DB::table('representant')->where('users_id',auth()->user()->id)->first()->id ?? 0 }}">
        @endif


		</ul>
		<div class="tab-content" style=" ">
    		<div class="tab-pane active" id="clients" role="tabpanel" aria-labelledby="clients-tab">
                <div class="table-container mn5" id="stats2-container">
                    @if( auth()->user()->user_role== 1 ||  auth()->user()->user_role== 2 || auth()->user()->user_role==5)
                        <div class="export-buttons">
                            <a href="#" id="export2-btn" class="btn btn-success exports btn-sm">
                                <i class="fa fa-file-excel"></i> Exporter en Excel
                            </a>
                        </div>
                    @endif
                    <table class="table table-bordered -table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="">{{__('msg.Customer')}}</th>
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
                <div class="row" style="font-size:14px"><div class="col-md-4"><b style="background-color:#f6f13f;padding:3px 3px;">   </b> Commercial support uniquement</div><div class="col-md-4"><b style="background-color:#bef4fe;padding:3px 3px;">   </b> Partagé avec un autre commercial support</div><div class="col-md-4"><b style="border:1px solid black;padding:3px 3px;">   </b> Je suis le seul représentant</div></div>
                <i>*Les données fournies dans ce document sont basées sur des estimations et restent sujettes à modification jusqu'à leur approbation finale par la direction.</i><br>
				<i>Les données prennent en compte la double fonction de commercial et de commercial support, contrairement à l'AS400(source de données) qui ne comptabilise qu'un seul statut.</i>

            </div>

			<div class="tab-pane" id="jobs" role="tabpanel" aria-labelledby="jobs-tab">
                <div class="table-container mn5" id="stats-container">
                    @if( auth()->user()->user_role== 1 ||  auth()->user()->user_role== 2 || auth()->user()->user_role==5)
                    <div class="export-buttons">
                        <a href="#" id="export-btn" class="btn btn-success exports btn-sm">
                            <i class="fa fa-file-excel"></i> Exporter en Excel
                        </a>
                    </div>
                    @endif
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="">{{__('msg.Job')}}</th>
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
                <i>*Les données fournies dans ce document sont basées sur des estimations et restent sujettes à modification jusqu'à leur approbation finale par la direction.</i><br>
				<i>Les données prennent en compte la double fonction de commercial et de commercial support, contrairement à l'AS400(source de données) qui ne comptabilise qu'un seul statut.</i>
            
            </div>
        </div> 
    </div>

     <div class="col-lg-6 col-sm-12  mb-4">
        <h3>Statistiques de mes agences de rattachement</h3>
        <ul class="nav nav-tabs card-header" id="myTab3" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="client-tab" data-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="true"  style="width:150px">{{__('msg.By')}} Client</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="job-tab" data-toggle="tab" href="#job" role="tab" aria-controls="job" aria-selected="false" style="width:150px">{{__('msg.By')}} {{__('msg.Job')}}</a>
			</li>
            <li class="nav-item pl-4">
                @if(auth()->user()->user_role==1|| auth()->user()->user_role==2 || auth()->user()->user_role==3 || auth()->user()->user_role==8 )
                <span class=" mr-2">{{__('msg.Agency')}}:</span>
                <select class="form-control" id="agence" onchange="update_stats();" style="max-width:300px">
                    <option></option>
                    @foreach ($agences as $agence)
                    <option @selected(auth()->user()->agence_ident==$agence->agence_ident) value="{{$agence->agence_ident}}">{{$agence->agence_lib}}    |  <small>{{$agence->adresse1}}</small></option>
                    @endforeach
                </select>
                @else
                    @php 
                        $agences_id= \DB::table('representant')->where('users_id',auth()->id())->value('agence');
                        $agences_array = explode(',', $agences_id);

                        //dd($agences_id);
                    @endphp
                        @if(count($agences_array)>1)
                            @php $agences= \App\Models\Agence::whereIn('agence_ident',$agences_array)->get(); @endphp
                            <select class="form-control" id="agence" onchange="update_stats();" style="max-width:300px">
                            @foreach ($agences as $agence)
                            <option @selected(auth()->user()->agence_ident==$agence->agence_ident) value="{{$agence->agence_ident}}"><small>{{$agence->agence_ident}}</small> | {{$agence->agence_lib}}  | <small>{{$agence->adresse1}}</small></option>
                            @endforeach
                            </select>
                        @else
                            <input type="hidden" id="agence" value="{{ auth()->user()->agence_ident }}"/>
                        @endif                    
                    
                @endif
            </li>
		</ul>
		<div class="tab-content" style=" ">
    		<div class="tab-pane active" id="client" role="tabpanel" aria-labelledby="client-tab">
                <div class="table-container mn5" id="stats4-container">      
                    @if( auth()->user()->user_role== 1 ||  auth()->user()->user_role== 2 || auth()->user()->user_role==5)              
                    <div class="export-buttons">
                        <a href="#" id="export4-btn" class="btn btn-success exports btn-sm">
                            <i class="fa fa-file-excel"></i> Exporter en Excel
                        </a>
                    </div>   
                    @endif                 
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="">{{__('msg.Customer')}}</th>
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
                <i>*Les données fournies dans ce document sont basées sur des estimations et restent sujettes à modification jusqu'à leur approbation finale par la direction.</i><br>
				<i>Les données prennent en compte la double fonction de commercial et de commercial support, contrairement à l'AS400(source de données) qui ne comptabilise qu'un seul statut.</i>            
                
            </div>

			<div class="tab-pane" id="job" role="tabpanel" aria-labelledby="job-tab">
                <div class="table-container mn5" id="stats3-container">
                    <div class="export-buttons">
                        @if( auth()->user()->user_role== 1 ||  auth()->user()->user_role== 2 || auth()->user()->user_role==5)
                        <a href="#" id="export3-btn" class="btn btn-success  exports btn-sm">
                            <i class="fa fa-file-excel"></i> Exporter en Excel
                        </a>
                        @endif
                    </div>
                    <table class="table table-bordered table-striped mb-40">
                        <thead>
                            <tr id="headtable">
                                <th class="">{{__('msg.Job')}}</th>
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
                <i>*Les données fournies dans ce document sont basées sur des estimations et restent sujettes à modification jusqu'à leur approbation finale par la direction.</i><br>
				<i>Les données prennent en compte la double fonction de commercial et de commercial support, contrairement à l'AS400(source de données) qui ne comptabilise qu'un seul statut.</i>                
            </div>
        </div> 
    </div>

</div>

<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <ul class="nav nav-tabs card-header" id="myTab1" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="rolling-tab" data-toggle="tab" href="#rolling" role="tab" aria-controls="rolling" aria-selected="true" style="width:300px">{{__('msg.12-month rolling customer statistics')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="agencies-tab" data-toggle="tab" href="#agencies" role="tab" aria-controls="agencies" aria-selected="false" style="width:300px">{{__('msg.All agencies')}}</a>
					</li>
                    <li class="nav-item">
						<a class="nav-link" id="inactive-tab" data-toggle="tab" href="#inactive" role="tab" aria-controls="inactive" aria-selected="false" style="width:300px">{{__('msg.Inactive customers')}}</a>
					</li>
                    <li class="pl-3">
                        <input id="mois_alt" type="checkbox" value="1" onchange="update_checkbox('mois_alt','mois')">
                            <label class="mt-2" for="mois_alt">{{__('msg.Show full years')}}</label>
                        </input>
                    </li>
				</ul>
            </div>
            <div class="card-body">
				<div class="tab-content" style=" ">
					<div class="tab-pane active" id="rolling" role="tabpanel" aria-labelledby="rolling-tab">

                        @if( auth()->user()->user_role== 1 ||  auth()->user()->user_role== 2 || auth()->user()->user_role==5)
                        <div class="export-buttons">
                            <a href="#" id="export7-btn" class="btn btn-success exports mb-1" >
                                <i class="fa fa-file-excel"></i> Exporter en Excel
                            </a>
                        </div>
                        @endif
                        <div class="table-container mn5" id="stats7-container">
                            <table class="table table-bordered table-striped mb-40">
                                <thead>
                                    <tr id="headtable">
                                        <th class="">{{__('msg.Customer')}}</th>
                                        <?php
                                        $mois_francais = [
                                            'Jan' => 'Janvier',
                                            'Feb' => 'Février',
                                            'Mar' => 'Mars',
                                            'Apr' => 'Avril',
                                            'May' => 'Mai',
                                            'Jun' => 'Juin',
                                            'Jul' => 'Juillet',
                                            'Aug' => 'Août',
                                            'Sep' => 'Septembre',
                                            'Oct' => 'Octobre',
                                            'Nov' => 'Novembre',
                                            'Dec' => 'Décembre'
                                        ];

                                        for ($i = 0; $i <= 12; $i++) {
                                            // Calculez la date en fonction du nombre de mois précédents
                                            $month_date = strtotime("-$i months");

                                            // Récupérez le mois (en format court) et l'année
                                            $month = date('M', $month_date);
                                            $year = date('Y', $month_date);

                                            // Affichez le mois en français avec l'année correspondante
                                            echo "<th class='text-center'>{$mois_francais[$month]} $year</th>";
                                        }
                                        ?>
                                        <th class="text-center">TOTAL</th>
                                </thead>
                                <tbody id="stats7">

                                </tbody>
                            </table>
                        </div>
                        <i>*Les données fournies dans ce document sont basées sur des estimations et restent sujettes à modification jusqu'à leur approbation finale par la direction.</i><br>
                        <i>Les données prennent en compte la double fonction de commercial et de commercial support, contrairement à l'AS400(source de données) qui ne comptabilise qu'un seul statut.</i>
 
                    </div>

                    <div class="tab-pane " id="agencies" role="tabpanel" aria-labelledby="agencies-tab">
                        <div class="table-container mn5">
                            @if( auth()->user()->user_role== 1 ||  auth()->user()->user_role== 2 || auth()->user()->user_role==5)
                            <div class="export-buttons">
                                <a href="#" id="export5-btn" class="btn btn-success exports btn-sm">
                                    <i class="fa fa-file-excel"></i> Exporter en Excel
                                </a>
                            </div>
                            @endif
                            <table class="table table-bordered table-striped mb-40">
                                <thead>
                                    <tr id="headtable">
                                        <th class="">{{__('msg.Agency')}}</th>
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
                        <i>*Les données fournies dans ce document sont basées sur des estimations et restent sujettes à modification jusqu'à leur approbation finale par la direction.</i><br>
                        <i>Les données prennent en compte la double fonction de commercial et de commercial support, contrairement à l'AS400(source de données) qui ne comptabilise qu'un seul statut.</i>
                    </div>

                    <div class="tab-pane " id="inactive" role="tabpanel" aria-labelledby="inactive-tab">
                        <div class="table-container mn5" id="stats6-container">
                            @if( auth()->user()->user_role== 1 ||  auth()->user()->user_role== 2 || auth()->user()->user_role==5)
                            <div class="export-buttons">
                                <a href="#" id="export6-btn" class="btn btn-success  exports btn-sm">
                                    <i class="fa fa-file-excel"></i> Exporter en Excel
                                </a>
                            </div>
                            @endif
                            <div class="col-lg-4">
                                <span class=" mr-2">{{__('msg.Inactive since')}} :</span><input type="number" class="form-control mb-20" id="nb_mois" onchange="update_stats();" style="max-width:70px" value="2" /> {{__('msg.Month')}}
                            </div>
                            <table class="table table-bordered table-striped mb-40">
                                <thead>
                                    <tr id="headtable">
                                        <th class="">{{__('msg.Customer')}}</th>
                                        <th class="text-center">{{__('msg.Last invoice')}}</th>
                                        <th class="text-center">{{ date('Y'); }}</th>
                                        <th class="text-center">{{ date('Y')-1; }}</th>
                                        <th class="text-center">{{ date('Y')-2; }}</th>
                                        <th class="text-center">{{ date('Y')-3; }}</th>
                                    </tr>
                                </thead>
                                <tbody id="stats6">

                                </tbody>
                            </table>
                        </div>
                        <i>*Les données fournies dans ce document sont basées sur des estimations et restent sujettes à modification jusqu'à leur approbation finale par la direction.</i><br>
                        <i>Les données prennent en compte la double fonction de commercial et de commercial support, contrairement à l'AS400(source de données) qui ne comptabilise qu'un seul statut.</i>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

    <script>
    $(document).ready(function() {

        setupExportButtons();

        $('#export7-btn').click(function(e) {
            e.preventDefault(); // Empêcher la navigation immédiate

            var representant = ($('#commercial').find(':selected').data('id'));

            if (typeof representant === 'undefined') {
                representant = $('#commercial').val();
            }

            // Générer l'URL avec le user_id
            let exportUrl = "{{ route('export.stats.excel') }}?user=" + representant;

            // Rediriger vers l'URL d'exportation
            window.location.href = exportUrl;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const commercialSelect = document.getElementById('commercial');

        // Vérifier si les selects existent
        if (typeSelect && commercialSelect) {
            // Fonction pour filtrer les options
            function filterRepresentants() {
                const selectedType = typeSelect.value;

                // Afficher ou masquer les options en fonction du type
                Array.from(commercialSelect.options).forEach(option => {
                    if (option.dataset.type === selectedType) {
                        option.style.display = ''; // Afficher
                    } else {
                        option.style.display = 'none'; // Masquer
                    }
                });

                // Sélectionner la première option visible
                const firstVisibleOption = Array.from(commercialSelect.options).find(option => option.style.display === '');
                if (firstVisibleOption) {
                    commercialSelect.value = firstVisibleOption.value;
                }

                // Mettre à jour les stats
                update_stats();
            }

            // Appliquer le filtre au chargement de la page et lors du changement
            filterRepresentants();
            typeSelect.addEventListener('change', filterRepresentants);
        } else {
            update_stats();
            console.warn('Les éléments #type ou #commercial ne sont pas disponibles dans le DOM.');
        }
    });

    function update_checkbox(checkbox,target){
        if ($('#'+checkbox).is(':checked')) {
            $('#'+target).prop('checked',true);
        }else{
            $('#'+target).prop('checked',false);
        }
        update_stats();
    }

    function update_stats() {
        var _token = $('input[name="_token"]').val();
        var agence = $('#agence').val();
        var user = ($('#user_id').val());
        var representant = ($('#commercial').find(':selected').data('id'));
        var nb_mois = $('#nb_mois').val();
        if (typeof representant === 'undefined') {
            representant = $('#commercial').val();
        }
        var mois = 1;
        if ($('#mois').is(':checked')) {
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
                //onsole.log(data);
                var html = '';
                var class1 = class2 = class3 = '';
                data.forEach(item => {
                    if (data.length === 0) {
                        html = '<tr><td colspan="8" class="text-center">Aucune donnée disponible</td></tr>';
                    } else {
                        let delta1 = parseFloat((item.delta_1 && item.delta_1.includes('%')) ? item.delta_1.replace('%', '') : '0');
                        let delta2 = parseFloat((item.delta_2 && item.delta_2.includes('%')) ? item.delta_2.replace('%', '') : '0');
                        let delta3 = parseFloat((item.delta_3 && item.delta_3.includes('%')) ? item.delta_3.replace('%', '') : '0');

                        class1 = delta1 < 0 ? 'text-danger' : 'text-success';
                        class2 = delta2 < 0 ? 'text-danger' : 'text-success';
                        class3 = delta3 < 0 ? 'text-danger' : 'text-success';

                        html += '<tr><td class="text">' + item.metier + '</td><td>' + item.N + '</td><td  class="' + class1 + '">' + item.delta_1 + '</td><td>' + item.N_1 + '</td><td  class="' + class2 + '">' + item.delta_2 + '</td><td>' + item.N_2 + '</td><td  class="' + class3 + '">' + item.delta_3 + '</td><td>' + item.N_3 + '</td></tr>';
                    }
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
                //console.log(data);
                var html = '';
                var class1 = class2 = class3 = '';
                data.forEach(item => {
                    if (data.length === 0) {
                        html = '<tr ><td colspan="8" class="text-center">Aucune donnée disponible</td></tr>';
                    } else {
                        let delta1 = parseFloat((item.delta_1 && item.delta_1.includes('%')) ? item.delta_1.replace('%', '') : '0');
                        let delta2 = parseFloat((item.delta_2 && item.delta_2.includes('%')) ? item.delta_2.replace('%', '') : '0');
                        let delta3 = parseFloat((item.delta_3 && item.delta_3.includes('%')) ? item.delta_3.replace('%', '') : '0');
                        let bgcolor = item.couleur;

                        class1 = delta1 < 0 ? 'text-danger' : 'text-success';
                        class2 = delta2 < 0 ? 'text-danger' : 'text-success';
                        class3 = delta3 < 0 ? 'text-danger' : 'text-success';
                        let link = item.id > 0 ? 'https://crm.mysaamp.com/clients/fiche/' + item.id : '#'
                        html += '<tr style="background-color:'+bgcolor+'"><td class="text"><a href=' + link + '>' + item.nom + '</a></td><td>' + item.N + '</td><td  class="' + class1 + '">' + item.delta_1 + '</td><td>' + item.N_1 + '</td><td  class="' + class2 + '">' + item.delta_2 + '</td><td>' + item.N_2 + '</td><td  class="' + class3 + '">' + item.delta_3 + '</td><td>' + item.N_3 + '</td></tr>';
                    }
                });
                $("#stats2").html(html);
            }
        });


        $.ajax({
            url: "{{ route('stats_commercial_client_12') }}",
            method: "get",
            data: {
                _token: _token,
                agence: agence,
                mois: mois,
                user: representant,
            },
            success: function(data) {
                //console.log(data);
                var html = '';
                data.forEach(item => {
                    let link = item.id > 0 ? 'https://crm.mysaamp.com/clients/fiche/' + item.id : '#'
                    html += '<tr><td class="text"><a href=' + link + '>' + item.nom + '</a></td><td>' + item.M + '</td><td>' + item.M_1 + '</td><td>' + item.M_2 + '</td><td>' + item.M_3 + '</td><td>' + item.M_4 + '</td><td>' + item.M_5 + '</td><td>' + item.M_6 + '</td><td>' + item.M_7 + '</td><td>' + item.M_8 + '</td><td>' + item.M_9 + '</td><td>' + item.M_10 + '</td><td>' + item.M_11 + '</td><td>' + item.M_12 + '</td><td>' + item.TOTAL + '</td></tr>';
                });
                $("#stats7").html(html);
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
                //console.log(data);
                var html = '';
                var class1 = class2 = class3 = '';
                data.forEach(item => {
                    if (data.length === 0) {
                        html = '<tr><td colspan="8" class="text-center">Aucune donnée disponible</td></tr>';
                    } else {
                        let delta1 = parseFloat((item.delta_1 && item.delta_1.includes('%')) ? item.delta_1.replace('%', '') : '0');
                        let delta2 = parseFloat((item.delta_2 && item.delta_2.includes('%')) ? item.delta_2.replace('%', '') : '0');
                        let delta3 = parseFloat((item.delta_3 && item.delta_3.includes('%')) ? item.delta_3.replace('%', '') : '0');

                        class1 = delta1 < 0 ? 'text-danger' : 'text-success';
                        class2 = delta2 < 0 ? 'text-danger' : 'text-success';
                        class3 = delta3 < 0 ? 'text-danger' : 'text-success';
                        html += '<tr><td class="text">' + item.metier + '</td><td>' + item.N + '</td><td  class="' + class1 + '">' + item.delta_1 + '</td><td>' + item.N_1 + '</td><td  class="' + class2 + '">' + item.delta_2 + '</td><td>' + item.N_2 + '</td><td  class="' + class3 + '">' + item.delta_3 + '</td><td>' + item.N_3 + '</td></tr>';
                    }
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
                //console.log(data);
                var html = '';
                var class1 = class2 = class3 = '';
                data.forEach(item => {
                    if (data.length === 0) {
                        html = '<tr><td colspan="8" class="text-center">Aucune donnée disponible</td></tr>';
                    } else {
                        let delta1 = parseFloat((item.delta_1 && item.delta_1.includes('%')) ? item.delta_1.replace('%', '') : '0');
                        let delta2 = parseFloat((item.delta_2 && item.delta_2.includes('%')) ? item.delta_2.replace('%', '') : '0');
                        let delta3 = parseFloat((item.delta_3 && item.delta_3.includes('%')) ? item.delta_3.replace('%', '') : '0');

                        class1 = delta1 < 0 ? 'text-danger' : 'text-success';
                        class2 = delta2 < 0 ? 'text-danger' : 'text-success';
                        class3 = delta3 < 0 ? 'text-danger' : 'text-success';
                        let link = item.id > 0 ? 'https://crm.mysaamp.com/clients/fiche/' + item.id : '#'
                        html += '<tr><td class="text"><a href=' + link + '>' + item.nom + '</a></td><td>' + item.N + '</td><td  class="' + class1 + '">' + item.delta_1 + '</td><td>' + item.N_1 + '</td><td  class="' + class2 + '">' + item.delta_2 + '</td><td>' + item.N_2 + '</td><td  class="' + class3 + '">' + item.delta_3 + '</td><td>' + item.N_3 + '</td></tr>';
                    }
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
                //console.log(data);
                var html = '';
                var class1 = class2 = class3 = '';
                data.forEach(item => {
                    if (data.length === 0) {
                        html = '<tr><td colspan="8" class="text-center">Aucune donnée disponible</td></tr>';
                    } else {
                        let delta1 = parseFloat((item.delta_1 && item.delta_1.includes('%')) ? item.delta_1.replace('%', '') : '0');
                        let delta2 = parseFloat((item.delta_2 && item.delta_2.includes('%')) ? item.delta_2.replace('%', '') : '0');
                        let delta3 = parseFloat((item.delta_3 && item.delta_3.includes('%')) ? item.delta_3.replace('%', '') : '0');

                        class1 = delta1 < 0 ? 'text-danger' : 'text-success';
                        class2 = delta2 < 0 ? 'text-danger' : 'text-success';
                        class3 = delta3 < 0 ? 'text-danger' : 'text-success';
                        html += '<tr><td class="text">' + item.Agence + '</td><td>' + item.N + '</td><td  class="' + class1 + '">' + item.delta_1 + '</td><td>' + item.N_1 + '</td><td  class="' + class2 + '">' + item.delta_2 + '</td><td>' + item.N_2 + '</td><td  class="' + class3 + '">' + item.delta_3 + '</td><td>' + item.N_3 + '</td></tr>';
                    }
                });
                $("#stats5").html(html);
            }
        });


        $.ajax({
            url: "{{ route('stats_clients_inactifs') }}",
            method: "get",
            data: {
                _token: _token,
                mois: nb_mois,
                user: representant,
            },
            success: function(data) {
                console.log('stats 6 :' + data);
                var html = '';
                data.forEach(item => {
                    let mois = item.annee_mois.split(' - ')[1]; // Récupérer le mois
                    mois = mois.length === 1 ? '0' + mois : mois; // Ajouter un zéro si le mois est à un seul chiffre
                    let annee = item.annee_mois.split(' - ')[0]; // Récupérer l'année
                    let annee_mois = mois + '/' + annee; // Formater comme MM/YYYY
                    let link = item.id > 0 ? 'https://crm.mysaamp.com/clients/fiche/' + item.id : '#'
                    html += '<tr><td class="text"><a href=' + link + '>' + item.nom + '</a></td><td class="text-center">' + annee_mois + '</td><td class="text-center">' + item.N + '</td><td class="text-center">' + item.N_1 + '</td><td class="text-center">' + item.N_2 + '</td><td class="text-center">' + item.N_3 + '</td></tr>';
                });
                $("#stats6").html(html);
            }
        });

    }

    function setupExportButtons() {
        // Commercial par métier
        $('#export-btn').click(function(e) {
            e.preventDefault();
            
            var representant = $('#commercial').find(':selected').data('id') || $('#commercial').val();
            var mois = $('#mois').is(':checked') ? 0 : 1;
            
            let exportUrl = "{{ route('export.commercial.metier') }}?user=" + representant + "&mois=" + mois;
            window.location.href = exportUrl;
        });
        
        $('#export2-btn').click(function(e) {
            e.preventDefault();
            
            var representant = $('#commercial').find(':selected').data('id') || $('#commercial').val();
            var mois = $('#mois').is(':checked') ? 0 : 1;
            
            let exportUrl = "{{ route('export.commercial.client') }}?user=" + representant + "&mois=" + mois;
            window.location.href = exportUrl;
        });
/*
        $('#export-commercial-client12-btn').click(function(e) {
            e.preventDefault();
            
            var representant = $('#commercial').find(':selected').data('id') || $('#commercial').val();
            var mois = $('#mois').is(':checked') ? 0 : 1;
            
            let exportUrl = "{{ route('export.commercial.client12') }}?user=" + representant + "&mois=" + mois;
            window.location.href = exportUrl;
        });
*/
         $('#export3-btn').click(function(e) {
            e.preventDefault();
            
            var agence = $('#agence').val();
            var mois = $('#mois').is(':checked') ? 0 : 1;
            
            let exportUrl = "{{ route('export.agence.metier') }}?agence=" + agence + "&mois=" + mois;
            window.location.href = exportUrl;
        });
     
        $('#export4-btn').click(function(e) {
            e.preventDefault();
            
            var agence = $('#agence').val();
            var mois = $('#mois').is(':checked') ? 0 : 1;
            
            let exportUrl = "{{ route('export.agence.client') }}?agence=" + agence + "&mois=" + mois;
            window.location.href = exportUrl;
        });

        $('#export5-btn').click(function(e) {
            e.preventDefault();
            
            var representant = $('#commercial').find(':selected').data('id') || $('#commercial').val();
            var mois = $('#mois').is(':checked') ? 0 : 1;
            
            let exportUrl = "{{ route('export.agences') }}?user=" + representant + "&mois=" + mois;
            window.location.href = exportUrl;
        });
     
        $('#export6-btn').click(function(e) {
            e.preventDefault();
            
            var representant = $('#commercial').find(':selected').data('id') || $('#commercial').val();
            var mois = $('#mois').is(':checked') ? 0 : 1;
            
            let exportUrl = "{{ route('export.clients.inactifs') }}?user=" + representant + "&mois=" + mois;
            window.location.href = exportUrl;
        });
 
    }
</script>
@endsection
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
    #stats6 tr:first-child,
    #stats7 tr:first-child {
        background-color: cornsilk;
    }

    table td:first-child {
        font-weight: bold;
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

    @if($commercial)
    <input type="hidden" id="commercial" value="{{ \DB::table('representant')->where('users_id',auth()->user()->id)->first()->id ?? 0 }}" />
    @else
    @if( auth()->user()->user_type=='admin' || auth()->user()->user_type=='adv' )
    <div class="col-lg-3">
        <span class=" mr-2">{{__('msg.Type')}}:</span>
        <select class="form-control mb-20" id="type">
            <option value="Commercial terrain">Commercial terrain</option>
            <option value="Contact client siège">Contact client siège</option>
            <option value="Collecteur Externe">Collecteur Externe</option>
        </select>
    </div>
    <div class="col-lg-3">
        <span class=" mr-2">{{__('msg.Commercial')}}:</span>
        <select class="form-control mb-20" id="commercial" onchange="update_stats();" style="max-width:300px">
            @foreach ($representants as $rp)
            <option @selected(auth()->user()->id==$rp->id) value="{{$rp->users_id}}" data-id="{{$rp->id}}" data-type="{{$rp->type}}" >{{$rp->nom}}  {{$rp->prenom}}</option>
            @endforeach
        </select>
    </div>

    @else
    <input type="hidden" id="commercial" value="{{ \DB::table('representant')->where('users_id',auth()->user()->id)->first()->id ?? 0 }}">
    @endif
    @endif

    <div class="col-lg-4 mt-4">
        <input id="mois" type="checkbox" value="1" onchange="update_stats();">
        <label class="mt-2" for="mois">{{__('msg.Show full years')}}</label>
        </input>
    </div>
</div>

<div class="row">

    <div class="col-lg-6 col-sm-12  mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.By')}} {{__('msg.Job')}}</h6>
            </div>
            <div class="card-body">
                <div class="table-container mn5">
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
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.By')}} {{__('msg.Customer')}}</h6>
            </div>
            <div class="card-body">
                <div class="table-container mn5">
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
                        <tbody id="stats2">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.12-month rolling customer statistics')}} </h6>
            </div>
            <div class="card-body">
                <div class="table-container mn5">
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
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <span class=" mr-2">{{__('msg.Agency')}}:</span><select class="form-control mb-20" id="agence" onchange="update_stats();" style="max-width:300px">
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
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.By')}} {{__('msg.Job')}}</h6>
            </div>
            <div class="card-body">
                <div class="table-container mn5">
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
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.By')}} {{__('msg.Customer')}}</h6>
            </div>
            <div class="card-body">
                <div class="table-container mn5">
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
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.All agencies')}}</h6>
            </div>
            <div class="card-body">
                <div class="table-container mn5">
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
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Inactive customers')}}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <span class=" mr-2">{{__('msg.Inactive since')}} :</span><input type="number" class="form-control mb-20" id="nb_mois" onchange="update_stats();" style="max-width:70px" value="2" /> {{__('msg.Month')}}
                    </div>
                </div>
                <div class="table-container mn5">
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
            </div>
        </div>
    </div>
</div>


<script>
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

    //update_stats();
</script>
@endsection
@extends('layouts.back')
<link rel="stylesheet" href="{{ URL::asset('css/phone.css') }}">
<style>
    .keypad button{
        width:32%!important;
    }


</style>
@section('content')

<?php


// Traitement d'un appel
if (isset($_GET['call'])) {
    $number = $_GET['number'];
    $autoanswer = isset($_GET['autoanswer']) ? 'false' : 'true';

    $callUrl = "https://api.telavox.se/dial/{$number}?autoanswer={$autoanswer}";

    $callCh = curl_init($callUrl);
    curl_setopt($callCh, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
    curl_setopt($callCh, CURLOPT_RETURNTRANSFER, true);

    $callResponse = curl_exec($callCh);
    $callResult = json_decode($callResponse, true);
    curl_close($callCh);

    if ($callResult && $callResult['message'] === 'OK') {
        echo '<p class="text-success">Appel initié avec succès !</p>';
    } else {
        echo '<p class="text-danger">Échec de l\'initiation de l\'appel.</p>';
    }
}

//////////////////////////////////////// FONCTION //////////////////////////////////

function displayCalls($calls,$type) {
    echo '<ul class="calls-list '.$type.'">';
    foreach ($calls as $call) {
        $date= htmlspecialchars(date('d/m/Y H:i', strtotime($call['datetime'])));
        echo '<li>';
        echo    '<span class="date">'.$date . '</span><br>';
        echo '<i class="fas fa-phone-square-alt"></i> <b>' . htmlspecialchars($call['number']) . '</b><br>';
        echo '</li>';
    }
    echo '</ul>';
}

?>


    <style>


    </style>
    <div class="row">

    <!-- Content Column -->
    <div class="col-lg-4 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Initier un appel</h6>
            </div>
            <div class="card-body">
            <div class="phone-container">
            <form method="get" class="phone-form">
                <div class="display">
                    <input type="text" id="number" name="number" required>
                </div>
                <div class="keypad">
                    <button type="button" onclick="pressKey('1')">1</button>
                    <button type="button" onclick="pressKey('2')">2</button>
                    <button type="button" onclick="pressKey('3')">3</button>
                    <button type="button" onclick="pressKey('4')">4</button>
                    <button type="button" onclick="pressKey('5')">5</button>
                    <button type="button" onclick="pressKey('6')">6</button>
                    <button type="button" onclick="pressKey('7')">7</button>
                    <button type="button" onclick="pressKey('8')">8</button>
                    <button type="button" onclick="pressKey('9')">9</button>
                    <button type="button" onclick="pressKey('*')">*</button>
                    <button type="button" onclick="pressKey('0')">0</button>
                    <button type="button" onclick="pressKey('#')">#</button>
                </div>
                <button type="submit" name="call">Appeler</button>
            </form>
            </div>

            </div>
        </div>
    </div>
    <!-- Content Column -->
    <div class="col-lg-8 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"> Historiques des appels - {{$client->Nom}}</h6>
            </div>
            <div class="card-body">

            <div class="calls-container">
                <?php if ($callData): ?>
                    <div>
                        <h6><img  src="{{  URL::asset('img/entring.png') }}"  width="40"/>Appels Entrants</h6>
                        <?php displayCalls($callData['incoming'],'text-success'); ?>
                    </div>
                    <div>
                        <h6><img  src="{{  URL::asset('img/sorting.png') }}"  width="40"/>Appels Sortants</h6>
                        <?php displayCalls($callData['outgoing'],'text-primary'); ?>
                    </div>
                    <div>
                        <h6><img  src="{{  URL::asset('img/missed.png') }}"  width="40"/>Appels Manqués</h6>
                        <?php displayCalls($callData['missed'],'text-danger'); ?>
                    </div>
                <?php else: ?>
                    <p class="error">Erreur lors de la récupération des données ou données vides.</p>
                <?php endif; ?>
            </div>


            </div>
        </div>
    </div>

    <script>
    function pressKey(key) {
        var input = document.getElementById('number');
        input.value += key;
    }
    </script>
@endsection
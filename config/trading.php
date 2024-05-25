<?php

return [

    'provider' => env('TRADING_provider',null),
    'username' => env('TRADING_username',null),
    'password' => env('TRADING_password',null),

    'div_number' => 31.10348,
    'coeff_vente' => 0.9975,
    'coeff_achat' => 1.0025,
    'gold_string' => 'Gold',
    'silver_string' => 'Silver',
    'platine_string' => 'Platinum/USD',
    'pallad_string' => 'Palladium/USD',

    'server'  =>  env('TRADING_server',null),
    'user'  =>  env('TRADING_user',null),
    'pass'  =>  env('TRADING_pass',null),

 ]

?>
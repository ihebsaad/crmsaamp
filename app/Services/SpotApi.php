<?php

namespace App\Services;



class SpotApi
{

    public static function spot_connect($clOrdID,$pair,$deal,$quantity,$remarks,$unite,$conversion){

        $token=env('SPOT_TOKEN');
        $endpoint=env('SPOT_ENDPOINT');

        //$spotRates = self::spot_price($pair);
        $quantity=floatval($quantity);
        \Log::info(' Qté '. ($quantity) .'unite: '.$unite);
        //$quantity= self::convertToOunces($quantity,$unite);
        $quantity= $quantity * ( 1 / $conversion) ;

        \Log::info(' Qté en onces '. ($quantity) );

        $request_data = json_encode(array(
            "ClOrdId" => (string)$clOrdID,
            "Pair" => $pair,
            "Deal" => $deal,
            "Quantity" => $quantity,
            "Remarks" => $remarks
        ));

        $headers = array(
            'Content-Type: application/json',
            'TokenID: '.$token
        );

        $ch = curl_init($endpoint.'Trade');

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        $result=array();
        $data = json_decode($response, true);
        if (curl_errno($ch)) {
            echo 'Erreur cURL SpotApi: ' . curl_error($ch);
            \Log::info(' SpotApi error '. curl_error($ch));
        } else {
            curl_close($ch);
            \Log::info(' SpotApi response '. json_encode($response));

            $responseArray = json_decode($response, true);

            if(isset($responseArray['result']['Quantity']) && $responseArray['result']['Quantity']!=''){
                $result['Quantity'] = $responseArray['result']['Quantity'];
                $result['Rate'] = $responseArray['result']['Rate'];
                $result['EXID'] = $responseArray['result']['EXID'];
                return $result;
            }else{
                return $data;
            }


        }
    }


    public static function spot_price($pair){

        $token=env('SPOT_TOKEN');
        $endpoint=env('SPOT_ENDPOINT');

        $headers = array(
            'Content-Type: application/json',
            'TokenID: '.$token
        );

        $ch = curl_init($endpoint.'GetSpotRates/SPC/'.$pair);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Erreur cURL : ' . curl_error($ch);
        } else {
            curl_close($ch);
        }
        $result=array();
        $data = json_decode($response, true);
        \Log::info(' SpotApi Price '. json_encode($data));
        if (isset($data['result'][0]['Ask']) && $data['result'][0]['Ask']!== null) {
            $result['pair'] = $data['result'][0]['Pair'];
            $result['ask'] = $data['result'][0]['Ask'];
            $result['bid'] = $data['result'][0]['Bid'];

            return $result;
        } else {
            \Log::info(' SpotApi Price Error !');
            return $data;
        }
    }


    public static function spot_status($clOrdID){

        $token=env('SPOT_TOKEN');
        $endpoint=env('SPOT_ENDPOINT');

        $request_data = json_encode(array(
            "ClOrdId" => (string)$clOrdID,
        ));

        $headers = array(
            'Content-Type: application/json',
            'TokenID: '.$token
        );

        $ch = curl_init($endpoint.'GetRequestStatus');

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        $result=array();
        $data = json_decode($response, true);
        \Log::info(' Status  '. json_encode($data));

        return $data;

    }


    public static function convertToOunces($quantity, $unite) {
        $coefficients = [
            1 => 0.035274,   // Gramme
            2 => 1,          // Once
            3 => 35.274,     // Kilogramme
            4 => 35273.9619  // Tonne
        ];

        // Vérifier si l'unité est valide
        if (array_key_exists($unite, $coefficients)) {
            // Conversion en onces
            $ounces = $quantity * $coefficients[$unite];
            return $ounces;
        } else {
            \Log::info('Unité non valide');
            return 0;
        }
    }


}
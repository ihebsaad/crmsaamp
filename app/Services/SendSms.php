<?php

namespace App\Services;

use Illuminate\Http\Request;
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;
use Illuminate\Support\Facades\Session;


class SendSms
{

    public static function send($phone)
    {
        $configuration = new Configuration(host:  env('SMS_BASE_URL',null), apiKey:  env('SMS_API_KEY',null) );
        $sendSmsApi = new SmsApi(config: $configuration);
        $expectedCode = rand(1000, 9999);

        //destination
        $destination = new SmsDestination(
            to: $phone
        );

        //sender
        $sender= env('APP_NAME','MySAAMP');
        //message
        $message='Votre code de vérification est : ' . $expectedCode;

        $message = new SmsTextualMessage(destinations: [$destination], from: $sender, text: $message);
            $request = new SmsAdvancedTextualRequest(messages: [$message]);

        try {
            $smsResponse = $sendSmsApi->sendSmsMessage($request);

            echo $smsResponse->getBulkId() . PHP_EOL;

            foreach ($smsResponse->getMessages() ?? [] as $message) {
                echo sprintf('', $message->getMessageId(), $message->getStatus()?->getName()) . PHP_EOL;
            }

            //$currentTime = file_get_contents('http://worldtimeapi.org/api/timezone/Europe/Paris');
            //$time=  json_decode($currentTime)->unixtime;
            $time = time();
            \Log::info('sms sent! code : '.$expectedCode.' Time : '.$time);
            Session::put('verification_code', ['code' => $expectedCode, 'time' => $time,'verif'=>0,'verif_expire'=>0]);
            return $expectedCode;
        } catch (Throwable $apiException) {
        //} catch (\Exception $e) {
            \Log::info('error'.$apiException->getCode() . "\n");
            dd("HTTP Code: " . $apiException->getCode() . "\n");
        }

        return ['code'=>$expectedCode,'time'=>$time];
    }


}
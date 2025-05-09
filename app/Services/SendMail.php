<?php

namespace App\Services;

use Illuminate\Http\Request;
#use Swift_Mailer;
#use Mail;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class SendMail
{


    public static function send($to, $sujet, $contenu, $attachments = [],$reply_to=null, $cci = [])
    {
        $transport = new EsmtpTransport(env('MAIL_HOST'), env('MAIL_PORT'));
        $transport->setUsername(env('MAIL_USERNAME'));
        $transport->setPassword(env('MAIL_PASSWORD'));

        $mailer = new Mailer($transport);
        $from = env('MAIL_FROM_ADDRESS');
        $fromName = env('MAIL_FROM_NAME');
        if($reply_to!='')
            $ccAddress = $reply_to;
        else
            $ccAddress = 'mysaamp@saamp.com';

        // Check if $to is an array, if not, make it an array
        $recipients = is_array($to) ? $to : [$to];
        $recipients = array_filter(is_array($to) ? $to : [$to], function($email) {
            return !is_null($email) && trim($email) !== '';
        });

        $email = (new Email())
            ->from($from)
            ->to($ccAddress)
            ->replyTo($ccAddress)
            ->subject($sujet)
            ->html($contenu);

        // Traitement des destinataires (BCC)
        $recipients = is_array($to) ? $to : [$to];
        foreach ($recipients as $recipient) {
            if (!is_null($recipient) && trim($recipient) !== '' && filter_var(trim($recipient), FILTER_VALIDATE_EMAIL)) {
                $email->addBcc(trim($recipient));
            }
        }
        
        // Ajout des adresses CCI
        if (!empty($cci)) {
            foreach ($cci as $cciEmail) {
                if (filter_var(trim($cciEmail), FILTER_VALIDATE_EMAIL)) {
                    $email->addBcc(trim($cciEmail));
                }
            }
        }
        // Ajout des fichiers joints
        foreach ($attachments as $attachment) {
            if (file_exists($attachment)) {
                $email->attachFromPath($attachment);
            }
        }

        $mailer->send($email);
    }


/*
    public static function send_pdf($to,$sujet,$contenu,$id,$type)
    {
        try{
            $swiftTransport =  new \Swift_SmtpTransport( env('MAIL_HOST'), env('MAIL_PORT'), env('MAIL_ENCRYPTION'));
            $swiftTransport->setUsername( env('MAIL_USERNAME')); //adresse email
            $swiftTransport->setPassword( env('MAIL_PASSWORD')); // mot de passe email

            $swiftMailer = new Swift_Mailer($swiftTransport);
            Mail::setSwiftMailer($swiftMailer);
            $from= env('MAIL_FROM_ADDRESS');
            $fromname= env('MAIL_FROM_NAME');

            Mail::send([], [], function ($message) use ($to,$sujet, $contenu,$from,$fromname,$id,$type ) {
                    $message
                    ->to($to)
                    //->bcc($chunk ?: [])
                        ->subject($sujet)
                        ->setBody($contenu, 'text/html')
                        ->setFrom([$from => $fromname]);

                if($type=='model'){
                    $fullpath=storage_path().'/models/model-'.$id.'.pdf';
                }
                else{
                    $fullpath=storage_path().'/orders/order-'.$id.'.pdf';
                }

                $name=basename($fullpath);
                $mime_content_type=mime_content_type ($fullpath);

                $message->attach($fullpath, array(
                        'as' => $name, // If you want you can chnage original name to custom name
                        'mime' => $mime_content_type)
                );

            });

          //  return redirect()->route('invoices.index')
          //  ->with('success','Facture envoyée !');
          //return view('test',['result'=>$e]);

        }catch(\Exception $e){
           // return redirect()->route('invoices.index');
           dd($e->getMessage());
           //return view('test',['result'=>$e->getMessage()]);

        }

    }
*/
}
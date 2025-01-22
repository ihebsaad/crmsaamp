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


    public static function send($to, $sujet, $contenu, $attachments = [],$reply_to=null)
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

        $email = (new Email())
            ->from($from)
            ->bcc(...$recipients) // Spread operator to add all recipients
            ->to($ccAddress)
            ->replyTo($ccAddress)
            ->subject($sujet)
            ->html($contenu);

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
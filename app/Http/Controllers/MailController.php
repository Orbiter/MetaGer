<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    /**
     * Load Startpage accordingly to the given URL-Parameter and Mobile
     *
     * @param  int  $id
     * @return Response
     */
    public function contactMail(Request $request){

        # Nachricht, die wir an den Nutzer weiterleiten:
        $messageType = ""; # [success|error]
        $returnMessage = '';
        if(!$request->has('email') || !$request->has('message')){
            $messageType = "error";
            $returnMessage = "Tut uns leid, aber leider haben wir mit Ihrer Kontaktanfrage keine Daten erhalten. Die Email wurde nicht versand";
        }else{
            # Wir versenden die Mail des Benutzers an uns:
            $replyTo = $request->input('email');
            $message = $request->input('message');

            if( Mail::send(['text' => 'kontakt.mail'], ['messageText'=>$message], function($message) use($replyTo){
                $message->to("office@suma-ev.de", $name = null);
                $message->from($replyTo, $name = null);
                $message->replyTo($replyTo, $name = null);
                $message->subject("MetaGer - Kontaktanfrage");
            }) ){
                # Mail erfolgreich gesendet
                $messageType = "success";
                $returnMessage = 'Ihre Email wurde uns erfolgreich zugestellt. Vielen Dank dafür! Wir werden diese schnellstmöglich bearbeiten und uns dann ggf. wieder bei Ihnen melden.';
            }else{
                # Fehler beim senden der Email
                $messageType = "error";
                $returnMessage = 'Beim Senden Ihrer Email ist ein Fehler aufgetreten. Bitte schicken Sie eine Email an: office@suma-ev.de, damit wir uns darum kümmern können';
            }

            $messageType = "success";
        }

    
        return view('kontakt.kontakt')
                ->with('title', 'Kontakt')
                ->with('css', 'kontakt.css')
                ->with('js', ['openpgp.min.js','kontakt.js'])
                ->with( $messageType, $returnMessage );
    }
}
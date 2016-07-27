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
        $replyTo = $request->input('email', 'noreply@metager.de');
        if($replyTo === ""){
            $replyTo = "noreply@metager.de";
        }else{
            $replyTo = $request->input('email');
        }

        if(!$request->has('message')){
            $messageType = "error";
            $returnMessage = "Tut uns leid, aber leider haben wir mit Ihrer Kontaktanfrage keine Daten erhalten. Die Email wurde nicht versand";
        }else{
            # Wir versenden die Mail des Benutzers an uns:
            $message = $request->input('message');
            $subject = "[Ticket " . date("Y") . date("d") . date("m") . date("H") . date("i") . date("s") . "] MetaGer - Kontaktanfrage";
            if( Mail::send(['text' => 'kontakt.mail'], ['messageText'=>$message], function($message) use($replyTo, $subject){
                $message->to("office@suma-ev.de", $name = null);
                $message->from($replyTo, $name = null);
                $message->replyTo($replyTo, $name = null);
                $message->subject($subject);
            }) ){
                # Mail erfolgreich gesendet
                $messageType = "success";
                $returnMessage = 'Ihre Email wurde uns erfolgreich zugestellt. Vielen Dank dafür! Wir werden diese schnellstmöglich bearbeiten und uns dann ggf. wieder bei Ihnen melden.';
            }else{
                # Fehler beim senden der Email
                $messageType = "error";
                $returnMessage = 'Beim Senden Ihrer Email ist ein Fehler aufgetreten. Bitte schicken Sie eine Email an: office@suma-ev.de, damit wir uns darum kümmern können.';
            }

            $messageType = "success";
        }

    
        return view('kontakt.kontakt')
                ->with('title', 'Kontakt')
                ->with('css', 'kontakt.css')
                ->with('js', ['openpgp.min.js','kontakt.js'])
                ->with( $messageType, $returnMessage );
    }

    public function donation(Request $request)
    {
        # Der enthaltene String wird dem Benutzer nach der Spende ausgegeben
        $messageToUser = "";
        $messageType = ""; # [success|error]

        # Folgende Felder werden vom Spendenformular als Input übergeben:
        # Name
        # Telefon
        # email
        # Kontonummer ( IBAN )
        # Bankleitzahl ( BIC )
        # Nachricht
        if(!$request->has('Kontonummer') || !$request->has('Bankleitzahl') || !$request->has('Nachricht')){
            $messageToUser = "Sie haben eins der folgenden Felder nicht ausgefüllt: IBAN, BIC, Nachricht. Bitte korrigieren Sie Ihre Eingabe und versuchen es erneut.\n";
            $messageType = "error";
        }else{
            $message = "\r\nName: " . $request->input('Name', 'Keine Angabe');
            $message .= "\r\nTelefon: " . $request->input('Telefon', 'Keine Angabe');
            $message .= "\r\nKontonummer: " . $request->input('Kontonummer');
            $message .= "\r\nBankleitzahl: " . $request->input('Bankleitzahl');
            $message .= "\r\nNachricht: " . $request->input('Nachricht');

            $replyTo = $request->input('email', 'anonymous-user@metager.de');
            if (!filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                $messageToUser .= "Die eingegebene Email-Addresse ($replyTo) scheint nicht korrekt zu sein.";
            }

            try{
                if(Mail::send(['text' => 'kontakt.mail'], ['messageText'=>$message], function($message) use($replyTo){
                    $message->to("office@suma-ev.de", $name = null);
                    $message->from($replyTo, $name = null);
                    $message->replyTo($replyTo, $name = null);
                    $message->subject("MetaGer - Spende");
                })) {
                    $messageType = "success";
                    $messageToUser = "Wir haben Ihre Spendenbenachrichtigung dankend erhalten. Eine persönliche Nachricht erhalten Sie in nächster Zeit, falls sie Ihre Kontaktdaten angegeben haben.";
                }else{
                    $messageType = "error";
                    $messageToUser = 'Beim Senden Ihrer Spendenbenachrichtigung ist ein Fehler auf unserer Seite aufgetreten. Bitte schicken Sie eine Email an: office@suma-ev.de, damit wir uns darum kümmern können.';
                }
            } catch( \Swift_TransportException $e ){
                $messageType = "error";
                $messageToUser = 'Beim Senden Ihrer Spendenbenachrichtigung ist ein Fehler auf unserer Seite aufgetreten. Bitte schicken Sie eine Email an: office@suma-ev.de, damit wir uns darum kümmern können.';
            }
        }


        return view('spende.spende')
                ->with('title', 'Kontakt')
                ->with('css', 'donation.css')
                ->with($messageType,$messageToUser);
    }
}
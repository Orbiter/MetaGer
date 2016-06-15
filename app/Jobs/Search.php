<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Redis;
use Log;

class Search extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    protected $hash, $host, $port, $name, $getString, $useragent, $fp, $sumaFile;
    protected $buffer_length = 8192;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($hash, $host, $port, $name, $getString, $useragent, $sumaFile)
    {
        $this->hash = $hash;
        $this->host = $host;
        $this->port = $port;
        $this->name = $name;
        $this->getString = $getString;
        $this->useragent = $useragent;
        $this->sumaFile = $sumaFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $this->fp = $this->getFreeSocket();
        
        if(!$this->fp)
        {
            $this->disable($this->sumaFile, "Die Suchmaschine " . $this->name . " wurde für 1h deaktiviert, weil keine Verbindung aufgebaut werden konnte");
        }else
        {
            if($this->writeRequest())
            {
                $this->readAnswer();
            }
        }
    }

    public function disable($sumaFile, $message)
    {
        $xml = simplexml_load_file($sumaFile);
        $xml->xpath("//sumas/suma[@name='" . $this->name . "']")['0']['disabled'] = date(DATE_RFC822, mktime(date("H")+1,date("i"), date("s"), date("m"), date("d"), date("Y")));
        $xml->saveXML($sumaFile);
    }

    private function readAnswer ()
    {
        $time = microtime(true);
        $headers = '';
        $body = '';
        $length = 0;

        if(!$this->fp)
        {
            return;
        }

        // get headers FIRST
        $c = 0;
        stream_set_blocking($this->fp, 1);
        do
        {
            // use fgets() not fread(), fgets stops reading at first newline
            // or buffer which ever one is reached first
            $data = fgets($this->fp, 8192);
            // a sincle CRLF indicates end of headers
            if ($data === false || $data == "\r\n" || feof($this->fp) ) {
                // break BEFORE OUTPUT
                break;
            }
            if( sizeof(($tmp = explode(": ", $data))) === 2 )
                $headers[trim($tmp[0])] = trim($tmp[1]);
            $c++;
        }
        while (true);

        // end of headers
        if(sizeof($headers) > 1){
            $bodySize = 0;
            if( isset($headers["Transfer-Encoding"]) && $headers["Transfer-Encoding"] === "chunked" )
            {
                $body = $this->readChunked();
                
            }elseif( isset($headers['Content-Length']) )
            {
                $length = trim($headers['Content-Length']);
                if(is_numeric($length) && $length >= 1)
                    $body = $this->readBody($length);
                $bodySize = strlen($body);
            }else
            {
                Log::error("Konnte nicht herausfinden, wie ich die Serverantwort von: " . $this->name . " auslesen soll. Header war: " . print_r($headers));
                exit;
            }
        }else
        {
            return;
        }

        Redis::del($this->host . "." . $this->socketNumber);
        if( isset($headers["Content-Encoding"]) && $headers['Content-Encoding'] === "gzip")
        {
            $body = $this->gunzip($body);
        }
        Redis::hset('search.' . $this->hash, $this->name, $body);
        Redis::expire('search.' . $this->hash, 5);
    }

    private function readBody($length)
    {
        $theData = '';
        $done = false;
        stream_set_blocking($this->fp, 0);
        $startTime = time();
        $lastTime = $startTime;
        while (!feof($this->fp) && !$done && (($startTime + 1) > time()) && $length !== 0)
        {
            usleep(100);
            $theNewData = fgets($this->fp, 8192);
            $theData .= $theNewData;
            $length -= strlen($theNewData);
            $done = (trim($theNewData) === '0');

        }
        return $theData;
    }

    private function readChunked()
    {
        $body = '';
        // read from chunked stream
        // loop though the stream
        do
        {
            // NOTE: for chunked encoding to work properly make sure
            // there is NOTHING (besides newlines) before the first hexlength

            // get the line which has the length of this chunk (use fgets here)
            $line = fgets($this->fp, 8192);

            // if it's only a newline this normally means it's read
            // the total amount of data requested minus the newline
            // continue to next loop to make sure we're done
            if ($line == "\r\n") {
                continue;
            }

            // the length of the block is sent in hex decode it then loop through
            // that much data get the length
            // NOTE: hexdec() ignores all non hexadecimal chars it finds
            $length = hexdec($line);

            if (!is_int($length)) {
                trigger_error('Most likely not chunked encoding', E_USER_ERROR);
            }

            // zero is sent when at the end of the chunks
            // or the end of the stream or error
            if ($line === false || $length < 1 || feof($this->fp)) {
                if($length <= 0)
                        fgets($this->fp, 8192);
                // break out of the streams loop
                break;
            }

            // loop though the chunk
            do
            {
                // read $length amount of data
                // (use fread here)
                $data = fread($this->fp, $length);

                // remove the amount received from the total length on the next loop
                // it'll attempt to read that much less data
                $length -= strlen($data);

                // PRINT out directly
                // you could also save it directly to a file here

                // store in string for later use
                $body .= $data;

                // zero or less or end of connection break
                if ($length <= 0 || feof($this->fp))
                {
                    // break out of the chunk loop
                    if($length <= 0)
                        fgets($this->fp, 8192);
                    break;
                }
            }
            while (true);
            // end of chunk loop
        }
        while (true);
        // end of stream loop
        return $body;
    }

    private function gunzip($zipped) {
      $offset = 0;
      if (substr($zipped,0,2) == "\x1f\x8b")
         $offset = 2;
      if (substr($zipped,$offset,1) == "\x08")  
      {
        try
        {
         return gzinflate(substr($zipped, $offset + 8));
        } catch (\Exception $e)
        {
            abort(500, "Fehler beim unzip des Ergebnisses von folgendem Anbieter: " . $this->name);
        }
      }
      return "Unknown Format";
   }  

    private function writeRequest ()
    {
        
        $out = "GET " . $this->getString . " HTTP/1.1\r\n";
        $out .= "Host: " . $this->host . "\r\n";
        $out .= "User-Agent: " . $this->useragent . "\r\n";
        $out .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
        $out .= "Accept-Language: de,en-US;q=0.7,en;q=0.3\r\n";
        $out .= "Accept-Encoding: gzip, deflate, br\r\n";
        $out .= "Connection: keep-alive\r\n\r\n";
        # Anfrage senden:
        $sent = 0; $string = $out; $time = microtime(true);
        while(true)
        {   
            try{
                $tmp = fwrite($this->fp, $string);
            }catch(\ErrorException $e)
            {
                # Irgendwas ist mit unserem Socket passiert. Wir brauchen einen neuen:
                fclose($this->fp);
                Redis::del($this->name . "." . $this->socketNumber);
                $this->fp = $this->getFreeSocket();
                $sent = 0;
                $string = $out;
                continue;
            }
            if($tmp){
                $sent += $tmp;
                $string = substr($string, $tmp);
            }else
                 Log::error("Fehler beim schreiben.");

            if(((microtime(true) - $time) / 1000000) >= 500)
            {
                Log::error("Konnte die Request Daten nicht an: " . $this->name . " senden");
            }

            if($sent >= strlen($out))
                break;
        }
        if( $sent === strlen($out) )
        {
            return true;
        }
        return false;
    }

    private function getFreeSocket()
    {
        # Je nach Auslastung des Servers ( gleichzeitige Abfragen ), kann es sein, dass wir mehrere Sockets benötigen um die Abfragen ohne Wartezeit beantworten zu können.
        # pfsockopen öffnet dabei einen persistenten Socket, der also auch zwischen den verschiedenen php Prozessen geteilt werden kann. 
        # Wenn der Hostname mit einem bereits erstellten Socket übereinstimmt, wird die Verbindung also aufgegriffen und fortgeführt.
        # Allerdings dürfen wir diesen nur verwenden, wenn er nicht bereits von einem anderen Prozess zur Kommunikation verwendet wird.
        # Wenn dem so ist, probieren wir den nächsten Socket zu verwenden.
        # Dies festzustellen ist komplizierter, als man sich das vorstellt. Folgendes System sollte funktionieren:
        # 1. Stelle fest, ob dieser Socket neu erstellt wurde, oder ob ein existierender geöffnet wurde.
        $counter = 0; $fp = null;
        do
        {
            
            if( intval(Redis::exists($this->host . ".$counter")) === 0 )              
            {
                Redis::set($this->host . ".$counter", 1);
                Redis::expire($this->host . ".$counter", 5);
                $this->socketNumber = $counter;

                try
                {
                    $fp = pfsockopen($this->getHost() . ":" . $this->port . "/$counter", $this->port, $errstr, $errno, 1);
                    if(!$fp)
                        Log::error("lkajng");
                }catch(\ErrorException $e)
                {
                    Log::error('Fehler beim erstellen des Sockets zu: ' . $this->getHost());
                    break;
                }
                # Wir gucken, ob der Lesepuffer leer ist:
                stream_set_blocking($fp, 0);
                $string = fgets($fp, 8192);
                if( $string !== false || feof($fp) )
                {
                    Log::error("Der Lesepuffer von: " . $this->name . " war nach dem Erstellen nicht leer. Musste den Socket neu starten.");
                    fclose($fp);
                    continue;
                }
                Log::info($this->getHost() . ":" . $this->port . "/$counter");
                break;
            }
            $counter++;
        }while(true);
        return $fp;
    }

    private function getHost()
    {
        $return = "";
        if( $this->port === "443" )
        {
            $return .= "tls://";
        }else
        {
            $return .= "tcp://";
        }
        $return .= $this->host;
        return $return;
    }
}

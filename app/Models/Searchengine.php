<?php

namespace App\Models;
use App\MetaGer;
use Log;
use Redis;

abstract class Searchengine
{

	protected $ch; 	# Curl Handle zum erhalten der Ergebnisse
	protected $fp;
	protected $getString = "";
	protected $engine;
    protected $counter = 0;
    protected $enabled;
    protected $socketNumber = null;
	public $results = [];

	function __construct(\SimpleXMLElement $engine, MetaGer $metager)
	{

		foreach($engine->attributes() as $key => $value){
			$this->$key = $value->__toString();
		}

		# User-Agent definieren:
		if( isset($_SERVER['HTTP_USER_AGENT']))
		{
			$this->useragent = $_SERVER['HTTP_USER_AGENT'];
		}else
		{
			$this->useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1";
		}
		$this->ip = $metager->getIp();
		$this->gefVon = "<a href=\"" . $this->homepage . "\" target=\"_blank\">" . $this->displayName . "</a>";
		$this->startTime = microtime();
		$this->getString = $this->generateGetString($metager->getEingabe(), $metager->getUrl(), $metager->getLanguage(), $metager->getCategory());
		$counter = 0;

		# Wir benötigen einen verfügbaren Socket, über den wir kommunizieren können:
		$this->fp = $this->getFreeSocket();

		do
		{
			#try
			#{
				
				
			/*}catch(\Exception $e)
			{
				# Connection Timeout: Wir schalten die Suchmaschine aus:
				$this->disable($metager->getSumaFile(), "Die Suchmaschine " . $this->name . " wurde deaktiviert, weil keine Verbindung aufgebaut werden konnte");
				break;
			}*/
			if(!$this->fp)
			{
				// Mache etwas
				Log::error("Konnte keine Verbindung zur Suchmaschine: " . $this->name . " aufbauen.");
				break;
			}else
			{
				$out = "GET " . $this->getString . " HTTP/1.1\r\n";
				$out .= "Host: " . $this->host . "\r\n";
				$out .= "User-Agent: " . $this->useragent . "\r\n";
				$out .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
				$out .= "Accept-Language: de,en-US;q=0.7,en;q=0.3\r\n";
				$out .= "Accept-Encoding: gzip, deflate, br\r\n";
				$out .= "Connection: keep-alive\r\n\r\n";
				// Daten senden:
				#try 
				#{
					if(fwrite($this->fp, $out))
					{
						break;
					}else
					{
						abort(500, "Fehler beim schreiben.");
					}
					
				#}catch(\Exception $e)
				#{
				#	fclose($this->fp);
				#	if($counter >= 5)
				#	{
#
				#		Log::error("Konnte auch nach 6 Versuchen keine schreibbare Verbindung zum Server \"" . $this->host . "\" aufbauen.");
				#		break;
				#	}
				#}
			}
		}while(true);
	}

	public abstract function loadResults(String $result);

	private function getFreeSocket()
	{
		# Je nach Auslastung des Servers ( gleichzeitige Abfragen ), kann es sein, dass wir mehrere Sockets benötigen um die Abfragen ohne Wartezeit beantworten zu können.
		# pfsockopen öffnet dabei einen persistenten Socket, der also auch zwischen den verschiedenen php Prozessen geteilt werden kann. 
		# Wenn der Hostname mit einem bereits erstellten Socket übereinstimmt, wird die Verbindung also aufgegriffen und fortgeführt.
		# Allerdings dürfen wir diesen nur verwenden, wenn er nicht bereits von einem anderen Prozess zur Kommunikation verwendet wird.
		# Wenn dem so ist, probieren wir den nächsten Socket zu verwenden.
		# Dies festzustellen ist komplizierter, als man sich das vorstellt. Folgendes System sollte funktionieren:
		# 1. Stelle fest, ob dieser Socket neu erstellt wurde, oder ob ein existierender geöffnet wurde.

		$counter = 0;
		do
		{
			if(intval(Redis::getBit($this->name, $counter)) === 0)
			{

				#die($counter . "Socket.");
				Redis::setBit($this->name, $counter, 1);
				$this->socketNumber = $counter;

				try
				{
					$fp = pfsockopen($this->getHost() . ":" . $this->port . "/$counter", $this->port, $errstr, $errno, 1);
					return $fp;
				}catch(\ErrorException $e)
				{
					return null;
				}
				
				
			}
			$counter++;
		}while($counter < 20);

		# Wenn wir hier hin kommen, läuft etwas falsch! Wir kappen alle bestehenden Verbindungen:
		$connectionError = false;
		for($i = 0; $i < 20; $i++)
		{
			if(!$connectionError)
			{
				try
				{
					$fp = pfsockopen($this->getHost() . ":" . $this->port . "/$counter", $this->port, $errstr, $errno, 1);
					fclose($fp);
				}catch(\ErrorException $e)
				{
					$connectionError = true;
				}
			}
			
			Redis::setBit($this->name, $i, 0);
			
		}

		abort(500, "Konnte keinen freien Socket bekommen für: " . $this->name);
	}

	public function disable(string $sumaFile, string $message)
	{
		Log::info($message);
		$xml = simplexml_load_file($sumaFile);
		$xml->xpath("//sumas/suma[@name='" . $this->name . "']")['0']['disabled'] = "1";
		$xml->saveXML($sumaFile);
	}

	public function closeFp()
	{
		fclose($this->fp);
	}

	public function retrieveResults()
	{
		
		$headers = '';
		$body = '';
		$length = 0;
		if(!$this->fp)
		{
			return;
		}
		// We need a waiting Loop to wait till the server is ready
		// get headers FIRST
		stream_set_blocking($this->fp, 1);
		do
		{
    		// use fgets() not fread(), fgets stops reading at first newline
   			// or buffer which ever one is reached first
    		$data = fgets($this->fp, BUFFER_LENGTH);
    		if($data === false)
    		{
    			usleep(10000);
    			continue;
    		}
    		// a sincle CRLF indicates end of headers
    		if ($data === false || $data == CRLF || feof($this->fp)) {
        		// break BEFORE OUTPUT
        		break;
    		}
    		if( sizeof(($tmp = explode(": ", $data))) === 2 )
    			$headers[trim($tmp[0])] = trim($tmp[1]);
		}
		while (true);
		// end of headers

		if( isset($headers["Transfer-Encoding"]) && $headers["Transfer-Encoding"] === "chunked" )
		{
			$body = $this->readChunked();
		}elseif( isset($headers['Content-Length']) )
		{
			$length = trim($headers['Content-Length']);
			if(is_numeric($length) && $length >= 1)
				$body = fread($this->fp, $headers['Content-Length']);
		}
		Redis::setBit($this->name, $this->socketNumber, 0 );

		if( isset($headers["Content-Encoding"]) && $headers['Content-Encoding'] === "gzip")
		{
			$body = $this->gunzip($body);
		}
		#die(print_r($headers));
		// $body and $headers should contain your stream data
		$this->loadResults($body);
		#print(print_r($headers, TRUE) . $body);
		#exit;
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
	    	$line = fgets($this->fp, BUFFER_LENGTH);

	    	// if it's only a newline this normally means it's read
	    	// the total amount of data requested minus the newline
	    	// continue to next loop to make sure we're done
	    	if ($line == CRLF) {
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
		            	fgets($this->fp, BUFFER_LENGTH);
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
		        #print $data;
		        #flush();
		        // you could also save it directly to a file here

		        // store in string for later use
		        $body .= $data;

		        // zero or less or end of connection break
		        if ($length <= 0 || feof($this->fp))
		        {
		            // break out of the chunk loop
		            if($length <= 0)
		            	fgets($this->fp, BUFFER_LENGTH);
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

	protected function getHost()
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

	public function getCurlInfo()
	{
		return curl_getinfo($this->ch);
	}

	public function getCurlErrors()
	{
		return curl_errno($this->ch);
	}

	public function addCurlHandle ($mh)
	{
		curl_multi_add_handle($mh, $this->ch);
	}

	public function removeCurlHandle ($mh)
	{
		curl_multi_remove_handle($mh, $this->ch);
	}

	private function generateGetString($query, $url, $language, $category)
	{
		$getString = "";

		# Skript:
		if(strlen($this->skript) > 0)
			$getString .= $this->skript;
		else
			$getString .= "/";
		# FormData:
		if(strlen($this->formData) > 0)
			$getString .= "?" . $this->formData;

		# Wir müssen noch einige Platzhalter in dem GET-String ersetzen:
		if( strpos($getString, "<<USERAGENT>>") ){
			$getString = str_replace("<<USERAGENT>>", $this->urlEncode($this->useragent), $getString);
		}

		if( strpos($getString, "<<QUERY>>") )
		{
			$getString = str_replace("<<QUERY>>", $this->urlEncode($query), $getString);
		}

		if( strpos($getString, "<<IP>>") )
		{
			$getString = str_replace("<<IP>>", $this->urlEncode($this->ip), $getString);
		}

		if( strpos($getString, "<<LANGUAGE>>") )
		{
			$getString = str_replace("<<LANGUAGE>>", $this->urlEncode($language), $getString);
		}

		if( strpos($getString, "<<CATEGORY>>") )
		{
			$getString = str_replace("<<CATEGORY>>", $this->urlEncode($category), $getString);
		}

		if( strpos($getString, "<<AFFILDATA>>") )
		{
			$getString = str_replace("<<AFFILDATA>>", $this->getOvertureAffilData($url), $getString);
		}
		return $getString;
	}

	protected function urlEncode($string)
	{
		if(isset($this->inputEncoding))
		{
			return urlencode(mb_convert_encoding($string, $this->inputEncoding));
		}else
		{
			return urlencode($string);
		}
	}

	private function getOvertureAffilData($url)
	{
	    $affil_data = 'ip=' . $this->ip;
	    $affil_data .= '&ua=' . $this->useragent;  
	    if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
	       $affil_data .= '&xfip=' . $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	    $affilDataValue = $this->urlEncode($affil_data);
		# Wir benötigen die ServeUrl:
		$serveUrl = $this->urlEncode($url);

		return "&affilData=" . $affilDataValue . "&serveUrl=" . $serveUrl;
	}

	public function isEnabled ()
	{
		return $this->enabled;
	}
}
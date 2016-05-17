<?php

namespace App\Models;

abstract class Searchengine
{

	protected $ch; 	# Curl Handle zum erhalten der Ergebnisse

	function __construct(\SimpleXMLElement $engine, $mh, $query, $time)
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

		$this->ch = curl_init($this->generateGetString($query));
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT , $time); 

		$this->addCurlHandle($mh);
	}

	public abstract function loadResults();

	public function addCurlHandle ($mh)
	{
		curl_multi_add_handle($mh, $this->ch);
	}

	public function removeCurlHandle ($mh)
	{
		curl_multi_remove_handle($mh, $this->ch);
	}

	private function generateGetString($query)
	{
		$getString = "";
		# Protokoll:
		if($this->port === "443"){
			$getString .= "https://";
		}else{
			$getString .= "http://";
		}
		# Host:
		$getString .= $this->host;
		# Port:
		$getString .= ":" . $this->port;
		# Skript:
		$getString .= $this->skript;
		# FormData:
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
			$getString = str_replace("<<IP>>", $this->urlEncode(IP), $getString);
		}

		if( strpos($getString, "<<LANGUAGE>>") )
		{
			$getString = str_replace("<<LANGUAGE>>", $this->urlEncode(LANGUAGE), $getString);
		}

		if( strpos($getString, "<<CATEGORY>>") )
		{
			$getString = str_replace("<<CATEGORY>>", $this->urlEncode(CATEGORY), $getString);
		}

		if( strpos($getString, "<<AFFILDATA>>") )
		{
			$getString = str_replace("<<AFFILDATA>>", $this->getOvertureAffilData(), $getString);
		}
		return $getString;
	}

	private function urlEncode($string)
	{
		if(isset($this->inputEncoding))
		{
			return urlencode(mb_convert_encoding($string, $this->inputEncoding));
		}else
		{
			return urlencode($string);
		}
	}

	private function getOvertureAffilData()
	{
	    $affil_data = 'ip=' . IP;
	    $affil_data .= '&ua=' . $this->useragent;  
	    if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
	       $affil_data .= '&xfip=' . $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	    $affilDataValue = $this->urlEncode($affil_data);
		# Wir benötigen die ServeUrl:
		$serveUrl = $this->urlEncode(Request::url());#

		return "&affilData=" . $affilDataValue . "&serveUrl=" . $serveUrl;
	}
}
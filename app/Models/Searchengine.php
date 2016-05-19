<?php

namespace App\Models;
use App\MetaGer;

abstract class Searchengine
{

	protected $ch; 	# Curl Handle zum erhalten der Ergebnisse
	public $results = [];

	function __construct(\SimpleXMLElement $engine, $mh, MetaGer $metager)
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
		$this->ch = curl_init($this->generateGetString($metager->getEingabe(), $metager->getUrl(), $metager->getLanguage(), $metager->getCategory()) );
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_USERAGENT, $this->useragent); // set browser/user agent
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1); // automatically follow Location: headers (ie redirects)
		curl_setopt($this->ch, CURLOPT_AUTOREFERER, 1); // auto set the referer in the event of a redirect
		curl_setopt($this->ch, CURLOPT_MAXREDIRS, 5); // make sure we dont get stuck in a loop
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT , $metager->getTime()); 
		curl_setopt($this->ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
		curl_setopt($this->ch, CURLOPT_TIMEOUT, 1); // 10s timeout time for cURL connection
		if($this->port ==="443")
		{
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, true); // allow https verification if true
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 2); // check common name and verify with host name
			curl_setopt($this->ch, CURLOPT_SSLVERSION,3); // verify ssl version 2 or 3
		}

		$this->addCurlHandle($mh);
	}

	public abstract function loadResults();

	public function getCurlInfo()
	{
		return curl_getinfo($this->ch);
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
}
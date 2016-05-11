<?php

namespace App\MetaGer;

class Searchengine
{


	function __construct(\SimpleXMLElement $engine)
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

	}

	public function generateGetString()
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
			$getString = str_replace("<<QUERY>>", $this->urlEncode(Q), $getString);
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
		$xfip="$_SERVER['HTTP_X_FORWARDED_FOR']";
		$xfip=~ s/^(\d+\.\d+\.\d+)\.\d+/$1\.0/;
	    my $overt_ip=$ENV{'HTTP_FROM'};
	    my $overt_xfip=$xfip;
	    # anonymisierte IPs an Overture:
	    $overt_ip=~s#(\d+)\.(\d+)\.(\d+)\.\d+#$1\.$2\.$3\.0#;
	    $overt_xfip=~s#(\d+)\.(\d+)\.(\d+)\.\d+#$1\.$2\.$3\.0#;
	    $affil_data .= 'ip=' . $overt_ip;
	    $affil_data .= '&ua=' . $self->{useragent};  
	    if ($xfip) {
	       $affil_data .= '&xfip=' . $overt_xfip;
	    }
	    $affil_data = URI::Escape::uri_escape_utf8($affil_data);
	    $affilDataValue=$affil_data;
		# Wir benötigen die ServeUrl:
		$serveUrl = $cgi->self_url();#URI::Escape::uri_escape_utf8($cgi->self_url());
		$serveUrl = URI::Escape::uri_escape_utf8($serveUrl);
		return "&affilData=" . $affilDataValue . "&serveUrl=" . $serveUrl;
	}
}
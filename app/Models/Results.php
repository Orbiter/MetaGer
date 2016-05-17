<?php

namespace App\MetaGer;
use Illuminate\Http\Request;
use File;
class Results
{
	private $fokiNames = [];
	private $fokus;
	public $results = [];

	function __construct ($engines)
	{
		$this->results = $this->loadResults($engines);
	}



	private function get($getStrings){
		# Nimmt ein array aus getStrings entgegen und liefert ein Array aus Antworten zurück:
		# Zunächst alle Curl Abfragen initialisieren, aber noch nicht ausführen:
		#return $getStrings;
		#$getStrings = array($getStrings[0]);
		$ch = [];
		foreach($getStrings as $getString)
		{
			$tmp = curl_init($getString);
			curl_setopt($tmp, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($tmp, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($tmp, CURLOPT_CONNECTTIMEOUT , TIME); 
			$ch[] = $tmp;
		}

		# Nun initialisieren wir Multicurl:
		$mh = curl_multi_init();
		foreach($ch as $handle)
		{
			curl_multi_add_handle($mh, $handle);
		}

		# Nun führen wir die Get-Requests aus und warten auf alle Ergebnisse:
		$running = null;
		do
		{
			curL_multi_exec($mh, $running);
		}while($running);

		# Wir haben alle Ergebnisse und schließen die Handles
		foreach($ch as $handle)
		{
			curl_multi_remove_handle($mh, $handle);
		}
		# Und auch den Multicurl-Handle:
		curl_multi_close($mh);

		$results = [];
		foreach($ch as $handle)
		{
			$results[] = curl_multi_getcontent($handle);
		}

		return $results;
	}

}
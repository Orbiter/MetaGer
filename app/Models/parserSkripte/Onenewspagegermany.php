<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;
use App\Models\Result;

class Onenewspagegermany extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine, $mh, $query, $time, $ip, $url)
	{
		parent::__construct($engine, $mh, $query, $time, $ip, $url);
	}

	public function loadResults ()
	{
		$result = curl_multi_getcontent($this->ch);
		foreach( explode("\n", $result) as $line )
		{
			$line = trim($line);
			if( strlen($line) > 0 ){
				# Hier bekommen wir jedes einzelne Ergebnis
				$result = explode("|", $line);
				$this->results[] = new Result(
					trim(strip_tags($result[0])),
					$result[2],
					$result[2],
					$result[1],
					"<a href=\"http://www.newsdeutschland.com/videos.htm\">newsdeutschland.com</a>"
					);
			}
			
		}

	}
}
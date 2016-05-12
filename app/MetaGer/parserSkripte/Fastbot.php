<?php

namespace app\MetaGer\parserSkripte;
use App\MetaGer\Searchengine;

class Fastbot extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine)
	{
		parent::__construct($engine);
	}

	public function loadResults ()
	{
		$result = curl_multi_getcontent($this->ch);
		foreach( explode("\n", $result) as $line )
		{
			$line = trim($line);
			if( strlen($line) > 0 ){
				# Hier bekommen wir jedes einzelne Ergebnis
				$result = explode("|:|", $line);
				$link = $result[1];
				$link = substr($link, strpos($link, "href=\"") + 6);
				$link = substr($link, 0, strpos($link, "\""));
				$this->results[] = new \App\MetaGer\Result(
					trim(strip_tags($result[1])),
					$link,
					$result[3],
					$result[2]
					);
			}
			
		}
	}
}
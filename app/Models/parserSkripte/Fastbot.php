<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;

class Fastbot extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine, $mh, $query, $time)
	{
		parent::__construct($engine, $mh, $query, $time);
	}

	public function loadResults ()
	{
		$result = utf8_encode(curl_multi_getcontent($this->ch));
		foreach( explode("\n", $result) as $line )
		{
			$line = trim($line);
			if( strlen($line) > 0 ){
				# Hier bekommen wir jedes einzelne Ergebnis
				$result = explode("|:|", $line);
				$link = $result[1];
				$link = substr($link, strpos($link, "href=\"") + 6);
				$link = substr($link, 0, strpos($link, "\""));
				$this->results[] = new \App\Models\Result(
					trim(strip_tags($result[1])),
					$link,
					$result[3],
					$result[2],
					"<a href=\"http://www.fastbot.de\">fastbot</a>"
					);
			}
			
		}

	}
}
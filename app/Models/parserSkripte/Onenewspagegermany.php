<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;
use App\Models\Result;

class Onenewspagegermany extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine,  \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults (String $result)
	{
		$counter = 0;
		foreach( explode("\n", $result) as $line )
		{
			$line = trim($line);
			if( strlen($line) > 0 ){
				# Hier bekommen wir jedes einzelne Ergebnis
				$result = explode("|", $line);
				$counter++;
				$this->results[] = new Result(
					trim(strip_tags($result[0])),
					$result[2],
					$result[2],
					$result[1],
					$this->gefVon,
					$counter
					);
			}
			
		}

	}
}
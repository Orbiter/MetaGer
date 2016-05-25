<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;

class Onenewspagevideo extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine,  \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults (String $result)
	{
		$results = trim($result);
		
		foreach( explode("\n", $results) as $result )
		{
			$res = explode("|", $result);

			$title = $res[0];
			$link = $res[2];
			$anzeigeLink = $link;
			$descr = $res[1];

			$this->counter++;
			$this->results[] = new \App\Models\Result(
				$title,
				$link,
				$anzeigeLink,
				$descr,
				$this->gefVon,
				$this->counter
			);		
		}

		
	}
}
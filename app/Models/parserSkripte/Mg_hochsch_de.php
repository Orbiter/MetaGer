<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;

class Mg_hochsch_de extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine,  \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		$results = trim($result);
		
		foreach( explode("\n", $results) as $result )
		{
			$res = explode("|", $result);
			if(sizeof($res) < 3)
			{
				continue;
			}
			$title = $res[0];
			$link = $res[2];
			$anzeigeLink = $link;
			$descr = $res[1];

			$this->counter++;
			$this->results[] = new \App\Models\Result(
				$this->engine,
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
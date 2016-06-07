<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;

class Nebel extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine,\App\MetaGer $metager)
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
				continue;
			$title = $res[2];
			$link = $res[0];
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
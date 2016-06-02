<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;

class Suchticker extends Searchengine 
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
			$res = explode("';'", $result);
			if(sizeof($res) < 3)
			{
				continue;
			}
			$title = trim($res[0], "'");
			$link = trim($res[1], "'");
			$anzeigeLink = $link;
			$descr = trim($res[2], "'");

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
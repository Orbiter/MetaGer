<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;

class Zeitde extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine, \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		
		$results = json_decode($result);
		if(!$results)
			return;
		foreach( $results->{"matches"} as $result )
		{
			$title = $result->{"title"};
			$link = $result->{"href"};
			$anzeigeLink = $link;
			$descr = $result->{"snippet"};

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
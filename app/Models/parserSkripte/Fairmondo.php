<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;

class Fairmondo extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine,  \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		$results = json_decode($result, true);
		$results = $results["articles"];
		foreach($results as $result)
		{
			$title = $result["title"];
			$link = $result["html_url"];
			$anzeigeLink = $link;
			$descr = $result["slug"];

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
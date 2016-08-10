<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;

class BASE extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine,\App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		
		$title = "";
		$link = "";
		$anzeigeLink = $link;
		$descr = "";

		/*$this->counter++;
		$this->results[] = new \App\Models\Result(
			$title,
			$link,
			$anzeigeLink,
			$descr,
			$this->gefVon,
			$this->counter
		);*/		
	}
}
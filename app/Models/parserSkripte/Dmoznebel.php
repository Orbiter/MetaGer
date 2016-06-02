<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;

class Dmoznebel extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine, \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		
		$title = "";
		$link = "";
		$anzeigeLink = $link;
		$descr = "";

		die($result);

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
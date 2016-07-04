<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;
use Log;

class Loklak extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine, \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		if(!$result)
		{
			return;
		}
		$results = json_decode($result, true);
		if( !isset($results['statuses']) )
			return;
		foreach($results['statuses'] as $result)
		{
			$title = $result["screen_name"];
			$link = $result['link'];
			$anzeigeLink = $link;
			$descr = $result["text"];
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

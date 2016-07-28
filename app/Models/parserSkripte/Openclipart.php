<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;
use Symfony\Component\DomCrawler\Crawler;

class Openclipart extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine, \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		$result = preg_replace("/\r\n/si", "", $result);
		try {
			$content = json_decode($result);
		} catch (\Exception $e) {
			abort(500, "$result is not a valid json string");
		}
		
		if(!$content)
		{
			return;
		}

		$results = $content->payload;
		foreach($results as $result)
		{
			$title = $result->title;
			$link = $result->detail_link;
			$anzeigeLink = $link;
			$descr = $result->description;
			$image = $result->svg->url;
			$this->counter++;
			$this->results[] = new \App\Models\Result(
				$this->engine,
				$title,
				$link,
				$anzeigeLink,
				$descr,
				$this->gefVon,
				$this->counter,
				false,
				$image
			);
		}
	}
}
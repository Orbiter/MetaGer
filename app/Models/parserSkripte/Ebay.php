<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;

class Ebay extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine,  \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		try {
			$content = simplexml_load_string($result);
		} catch (\Exception $e) {
			abort(500, "$result is not a valid xml string");
		}
		
		if(!$content)
		{
			return;
		}
		$results = $content->xpath('//rss/channel/item');
		$count = 0;
		foreach($results as $result)
		{
			if($count > 10)
				break;
			$title = $result->{"title"}->__toString();
			$link = $result->{"link"}->__toString();
			$anzeigeLink = $link;
			if(preg_match("/.*?href=\"(.+?)\".*src=\"(.+?)\".*<strong><b>EUR<\/b> (.+?)<\/strong>.*?<span>(.+?)<\/span>.*/si", $result->{"description"}->__toString(), $matches) === 1);
			$descr = "Ebay-Auktion: l&auml;uft bis " . $matches[4] . " | " . $matches[3] . " &euro;";
			$image = $matches[2];
			# die($result->{"description"}->__toString());
			# $descr = strip_tags($result->{"description"}->__toString());
			# $descr = $result->{"description"}->__toString();
			# .*?href="(.+?)".*src="(.+?)".*<strong><b>EUR<\/b> (.+?)<\/strong>.*?<div>(.+?)<\/div>.*
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
			$count++;

		}
		
	}
}
<?php

namespace App\Models\parserSkripte;

use App\Models\Searchengine;

class Minisucher extends Searchengine
{

	function __construct (\SimpleXMLElement $engine,\App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($content)
	{
		$content = simplexml_load_string($content);
		if(!$content)
		{
			return;
		}
		$results = $content->xpath('//response/result/doc');

		$string = "";
		
		$counter = 0;
		foreach($results as $result)
		{
			$counter++;
			$result = simplexml_load_string($result->saveXML());
			$title = $result->xpath('//doc/arr[@name="title"]/str')[0]->__toString();
			$link = $result->xpath('//doc/str[@name="url"]')[0]->__toString();
			$anzeigeLink = $link;
			$descr = "";
			$descriptions = $content->xpath("//response/lst[@name='highlighting']/lst[@name='$link']/arr[@name='content']/str");
			foreach($descriptions as $description)
			{
				$descr .= $description->__toString();
			}
			$descr = strip_tags($descr);
			$provider = $result->xpath('//doc/str[@name="subcollection"]')[0]->__toString();
			$this->results[] = new \App\Models\Result(
					$this->engine,
					$title,
					$link,
					$link,
					$descr,
					$this->gefVon,
					$counter
					);
		}
		
		
	}

}
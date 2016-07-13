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
		try {
			$content = simplexml_load_string($content);
		} catch (\Exception $e) {
			return;
		}
		if(!$content)
		{
			return;
		}
		$results = $content->xpath('//response/result/doc');

		$string = "";
		
		$counter = 0;
		$providerCounter = [];
		foreach($results as $result)
		{
			try{
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

				if( isset($providerCounter[$provider]) && $providerCounter[$provider] > 10 )
					continue;
				else
				{
					if( !isset($providerCounter[$provider]) )
						$providerCounter[$provider] = 0;
					$providerCounter[$provider] += 1;
				}

				if( isset($provider) )
				{
					$gefVon = "<a href=\"https://metager.de\" target=\"_blank\">Minisucher: $provider</a>";
				}else
				{
					$gefVon = $this->gefVon;
				}

				$this->results[] = new \App\Models\Result(
						$this->engine,
						$title,
						$link,
						$link,
						$descr,
						$gefVon,
						$counter
						);
			}catch(\ErrorException $e)
			{
				continue;
			}
		}
		
		
	}

}
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
			if( $this->counter >= 10 )
				break;
			$title = $result["title"];
			$link = "https://www.fairmondo.de/articles/" . $result["id"];
			$anzeigeLink = $link;
			$price = 0;
			$descr = "";
			if( isset($result['price_cents']))
			{
				$price = intval($result['price_cents']);
				$descr .= "<p>Preis: " . (intval($result['price_cents']) / 100.0) . " €</p>";
			}
			if( isset($result['title_image_url']) )
			{
				$image = $result['title_image_url'];
			}

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
				$image,
				$price
			);		
		}
	}
}
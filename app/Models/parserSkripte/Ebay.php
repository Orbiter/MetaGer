<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;
use DateTimeZone;
use DateTime;

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
		$results = $content;
		
		$results = $results->{"searchResult"};
		$count = 0;
		foreach($results->{"item"} as $result)
		{
			$title = $result->{"title"}->__toString();
			$link = $result->{"viewItemURL"}->__toString();
			$anzeigeLink = $link;
			$time = $result->{"listingInfo"}->{"endTime"}->__toString();
			$time = date(DATE_RFC2822, strtotime($time));
			$price = intval($result->{"sellingStatus"}->{"convertedCurrentPrice"}->__toString()) * 100;
			$descr = "<p>Preis: " . $result->{"sellingStatus"}->{"convertedCurrentPrice"}->__toString() . " €</p>";
			$descr .= "<p>Versandkosten: " . $result->{"shippingInfo"}->{"shippingServiceCost"}->__toString() . " €</p>";
			if( isset($result->{"listingInfo"}->{"listingType"}))
				$descr .= "<p>Auktionsart: " . $result->{"listingInfo"}->{"listingType"}->__toString() . "</p>";
			$descr .= "<p>Auktionsende: " . $time . "</p>";
			if( isset($result->{"primaryCategory"}->{"categoryName"}))
				$descr .= "<p class=\"text-muted\">Kategorie: " . $result->{"primaryCategory"}->{"categoryName"}->__toString() . "</p>";
			$image = $result->{"galleryURL"}->__toString();
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
			$count++;

		}
		
	}
}
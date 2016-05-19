<?php

namespace App\Models\parserSkripte;

use App\Models\Searchengine;

class Similar_product_ads extends Searchengine
{

	function __construct (\SimpleXMLElement $engine, $mh, \App\MetaGer $metager)
	{
		parent::__construct($engine, $mh, $metager);
		$tmp = $metager->getEingabe();
		$tmp = preg_replace("/\W/si", "", $tmp);
		if(strlen($tmp) < 3)
		{
			$this->removeCurlHandle($mh);
		}
	}

	public function loadResults ()
	{
		$result = utf8_encode(curl_multi_getcontent($this->ch));
	}

}
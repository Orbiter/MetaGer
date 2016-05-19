<?php

namespace App\Models\parserSkripte;

use App\Models\Searchengine;

class Qualigo extends Searchengine
{

	function __construct (\SimpleXMLElement $engine, $mh, \App\MetaGer $metager)
	{
		parent::__construct($engine, $mh, $metager);
	}

	public function loadResults ()
	{
		$result = utf8_encode(curl_multi_getcontent($this->ch));
	}

}
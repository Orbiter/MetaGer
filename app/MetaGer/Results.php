<?php

namespace App\MetaGer;
use Illuminate\Http\Request;
use File;
class Results
{
	private $fokiNames = [];
	private $fokus;
	function __construct ($engines)
	{
		$this->results = $this->loadResults($engines);
	}

	private function loadResults($engines)
	{
		# Das Laden der Ergebnisse besteht aus 2 Elementaren Schritten:
		# 1. GET-Requests abarbeiten
		# 2. Ergebnisse parsen

		# 1. GET-Requests abarbeiten
		# Wir generiern zunächst alle GET-Strings:
		$getStrings = [];
		foreach($engines as $engine)
		{
			$getStrings[] = $engine->generateGetString();
		}

		return print_r($getStrings, TRUE);
	}

}
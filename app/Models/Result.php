<?php

namespace App\Models;

class Result
{
	
	function __construct ( $titel, $link, $anzeigeLink , $descr, $gefVon )
	{
		$this->titel = strip_tags(trim($titel));
		$this->link = trim($link);
		$this->anzeigeLink = trim($anzeigeLink);
		$this->descr = strip_tags(trim($descr));
		$this->descr = preg_replace("/\n+/si", " ", $this->descr);
		$this->gefVon = trim($gefVon);
	}
}
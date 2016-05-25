<?php

namespace App\Models;

class Result
{
	
	function __construct ( $titel, $link, $anzeigeLink , $descr, $gefVon )
	{
		$this->titel = trim($titel);
		$this->link = trim($link);
		$this->anzeigeLink = trim($anzeigeLink);
		$this->descr = trim($descr);
		$this->gefVon = trim($gefVon);
	}
}
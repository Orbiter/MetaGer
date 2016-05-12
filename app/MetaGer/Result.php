<?php

namespace App\MetaGer;

class Result
{
	
	function __construct ( $titel, $link, $anzeigeLink , $descr )
	{
		$this->titel = trim($titel);
		$this->link = trim($link);
		$this->anzeigeLink = trim($anzeigeLink);
		$this->descr = trim($descr);
	}
}
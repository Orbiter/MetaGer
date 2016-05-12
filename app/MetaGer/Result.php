<?php

namespace App\MetaGer;

class Result
{
	
	function __construct ( $titel, $link, $anzeigeLink , $descr )
	{
		$this->titel = $titel;
		$this->link = $link;
		$this->anzeigeLink = $anzeigeLink;
		$this->descr = $descr;
	}
}
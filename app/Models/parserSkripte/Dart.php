<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;
use Symfony\Component\DomCrawler\Crawler;

class Dart extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine, \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		
		$crawler = new Crawler($result);
		$crawler->filter('table#search-results > tr > td')->each(function (Crawler $node, $i)
		{
			$i = $i - ( 6 * $this->counter);
			if($i === 0)
				return;
			if($i === 1)
			{
				$this->description = $node->text();
				$this->link = "http://www.dart-europe.eu/" . $node->filter('a')->attr('href');
			}
			if($i === 2)
				$this->title = $node->text();
			if($i === 3)
				$this->title .= " (" . $node->text() . ")";
			if($i === 4)
				$this->title .= " " . $node->text();
			if($i === 5)
			{
				$this->title .= "|" . $node->text();
				$title = $this->title;
	                        $this->title = "";
        	                $link = $this->link; 
                	        $this->link = "";
                        	$anzeigeLink = $link;
	                        $descr = $this->description;
        	                $this->description = "";

	                        $this->counter++;
        	                $this->results[] = new \App\Models\Result(
                	                $this->engine,
                        	        $title,
                                	$link,
	                                $anzeigeLink,
        	                        $descr,
                	                $this->gefVon,
                        	        $this->counter
	                        );
			}
		} );


		
	}
}

<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;
use Symfony\Component\DomCrawler\Crawler;

class Bing extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine, \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		
		try
		{
			$crawler = new Crawler($result);
			$crawler->filter('ol#b_results > li.b_algo')->each(function (Crawler $node, $i)
			{
				$title = $node->filter('li h2 > a')->text();
				$link = $node->filter('li h2 > a')->attr('href');
				$anzeigeLink = $link;
				$descr = $node->filter('li div > p')->text();

				#die($result);

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
			} );
		} catch ( \ErrorException $e)
		{
			return;
		}


		
	}
}
<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;
use Symfony\Component\DomCrawler\Crawler;

class Mnogosearch extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine, $mh, \App\MetaGer $metager)
	{
		parent::__construct($engine, $mh, $metager);
	}

	public function loadResults ()
	{
		$result = curl_multi_getcontent($this->ch);
		$counter = 0;
		$crawler = new Crawler($result);
		$crawler->filter('table[width=600]')
			->reduce(function (Crawler $node, $i) 
			{
				if(strpos($node->text(), "Result pages:") !== FALSE)
				{
					return false;
				}
			})
			->each(function(Crawler $node, $i) 
			{
				$title = $node->filter('table > tr > td ')->eq(1)->filter('td > div')->text();
				$title = preg_replace("/\s+/si", " ", $title);
				
				$link = $node->filter('table > tr > td ')->eq(1)->filter('td > div > a')->attr('href');
				$anzeigeLink = $link;
				$descr = $node->filter('table > tr > td ')->eq(1)->filter('td > div')->eq(1)->text();
				$this->counter++;

				$this->results[] = new \App\Models\Result(
					$title,
					$link,
					$anzeigeLink,
					$descr,
					$this->gefVon,
					$this->counter
				);
			});

		
	}
}
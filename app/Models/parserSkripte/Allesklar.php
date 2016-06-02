<?php

namespace App\Models\parserSkripte;

use App\Models\Searchengine;
use Symfony\Component\DomCrawler\Crawler;

class Allesklar extends Searchengine
{
	protected $tds = "";
	function __construct (\SimpleXMLElement $engine, \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		$crawler = new Crawler(utf8_decode($result));
		$crawler = $crawler
					->filter('table[width=585]')
					->reduce(function(Crawler $node, $i) {
						if($i < 5)
						{
							return false;
						}
					});
		
		$this->counter = 0;
		$crawler->filter('table')->each(function (Crawler $node, $i) 
		{
			try {
			$this->string = "";
				$titleTag = $node->filter('tr > td > a')->first();
				$title = trim($titleTag->filter('a')->text());
				$link = $titleTag->filter('a')->attr('href');
				if($i === 0)
				{
					$descr = trim($node->filter('tr > td.bodytext')->eq(3)->text());
				}else
				{
					$descr = trim($node->filter('tr > td.bodytext')->eq(2)->text());
				}
				$this->counter++;
				$this->results[] = new \App\Models\Result(
					$this->engine,
					$title,
					$link,
					$link,
					$descr,
					$this->gefVon,
					$this->counter
					);
			} catch (\InvalidArgumentException $e)
			{

			}
				
		});	
	}

}
<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;
use Symfony\Component\DomCrawler\Crawler;

class Bing_bilder extends Searchengine
{
    public $results = [];

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {
        $crawler = new Crawler($result);
        $crawler->filter('div#b_content div.item')->each(function (Crawler $node, $i) {
            $title       = $node->filter('div.meta > a.tit')->text();
            $link        = $node->filter('div.meta > a.tit')->attr("href");
            $anzeigeLink = $link;
            $descr       = $node->filter('div.meta > div.des')->text();
            $image       = $node->filter('a.thumb img')->attr("src");

            $this->counter++;
            $this->results[] = new \App\Models\Result(
                $this->engine,
                $title,
                $link,
                $anzeigeLink,
                $descr,
                $this->gefVon,
                $this->counter,
                false,
                $image
            );
        });

    }
}

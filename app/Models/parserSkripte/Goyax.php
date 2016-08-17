<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;
use Symfony\Component\DomCrawler\Crawler;

class Goyax extends Searchengine
{
    public $results = [];

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {

        $crawler = new Crawler($result);
        $crawler->filter('tr.treffer')->each(function (Crawler $node, $i) {
            $title       = $node->filter('td.name')->text();
            $link        = "http://www.goyax.de" . $node->filter('td.name > a')->attr('href');
            $anzeigeLink = $link;
            $descr       = "Aktie: " . $node->filter('td.waehrung')->text() . " " . $node->filter('td.isin')->text();

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
        });

    }
}

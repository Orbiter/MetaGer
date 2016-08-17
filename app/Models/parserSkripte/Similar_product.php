<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Similar_product extends Searchengine
{
    public $results = [];

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {
        $results = json_decode($result);

        foreach ($results->{"products"} as $result) {
            $title       = $result->{"title"};
            $link        = $result->{"product_url"};
            $anzeigeLink = $link;
            $descr       = $result->{"description"};

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
    }
}

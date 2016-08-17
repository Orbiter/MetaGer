<?php

namespace App\Models\parserSkripte;

use App\Models\Searchengine;

class Similar_product_ads extends Searchengine
{

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
        $tmp = $metager->getEingabe();
        $tmp = preg_replace("/\W/si", "", $tmp);
        if (strlen($tmp) < 3) {
            $this->removeCurlHandle($mh);
        }
    }

    public function loadResults($results)
    {
        $results = json_decode($result);

        foreach ($results->{"products"} as $result) {
            $title       = $result->{"title"};
            $link        = $result->{"product_url"};
            $anzeigeLink = $link;
            $descr       = $result->{"description"};

            $this->counter++;
            $this->ads[] = new \App\Models\Result(
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

<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Qip extends Searchengine
{
    public $results = [];

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {
        $result = preg_replace("/\r\n/si", "", $result);
        try {
            $content = simplexml_load_string($result);
        } catch (\Exception $e) {
            abort(500, "$result is not a valid xml string");
        }

        if (!$content) {
            return;
        }
        $results = $content->xpath('//channel/item');
        foreach ($results as $result) {
            $title       = $result->{"title"}->__toString();
            $link        = $result->{"link"}->__toString();
            $anzeigeLink = $link;
            $descr       = $result->{"description"}->__toString();
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

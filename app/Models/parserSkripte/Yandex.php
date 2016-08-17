<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Yandex extends Searchengine
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
        $results = $content;
        try {
            $results = $results->xpath("//yandexsearch/response/results/grouping/group");
        } catch (\ErrorException $e) {
            return;
        }
        foreach ($results as $result) {
            $title       = strip_tags($result->{"doc"}->{"title"}->asXML());
            $link        = $result->{"doc"}->{"url"}->__toString();
            $anzeigeLink = $link;
            $descr       = strip_tags($result->{"doc"}->{"headline"}->asXML());
            if (!$descr) {
                $descr = strip_tags($result->{"doc"}->{"passages"}->asXML());
            }
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

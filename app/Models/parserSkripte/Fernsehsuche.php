<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Fernsehsuche extends Searchengine
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
            $content = json_decode($result);
        } catch (\Exception $e) {
            abort(500, "$result is not a valid xml string");
        }

        if (!$content) {
            return;
        }

        $results = $content->response->docs;
        foreach ($results as $result) {
            $title       = $result->show . " : " . $result->title;
            $link        = urldecode($result->url);
            $anzeigeLink = $link;
            $descr       = $result->description;
            $image       = "http://api-resources.fernsehsuche.de" . $result->thumbnail;
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
        }
    }
}

<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Pixabay extends Searchengine
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
            abort(500, "$result is not a valid json string");
        }

        if (!$content) {
            return;
        }

        $results = $content->hits;
        foreach ($results as $result) {
            $title       = $result->tags;
            $link        = $result->pageURL;
            $anzeigeLink = $link;
            $descr       = "";
            $image       = $result->previewURL;
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

<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Europeana extends Searchengine
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

        $results = $content->items;
        foreach ($results as $result) {
            if (isset($result->edmPreview)) {
                $title = $result->title[0];

                if (preg_match("/(.+)\?.*/si", $result->guid, $match)) {
                    $link = $match[1];
                } else {
                    $link = "";
                }

                $anzeigeLink = $link;
                $descr       = "";

                /*if (preg_match("/(?:uri=)(.+)/si", urldecode($result->edmPreview[0]), $match)){
                $image = $match[1];
                } else {
                $image = "";
                }
                echo $image . "
                ";*/
                $image = urldecode($result->edmPreview[0]);
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
}

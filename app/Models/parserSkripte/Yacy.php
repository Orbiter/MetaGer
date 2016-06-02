<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;

class Yacy extends Searchengine 
{
    public $results = [];

    function __construct (\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults ($result)
    {
        
       # die($result);
        try {
            $content = simplexml_load_string($result);
        } catch (\Exception $e) {
            abort(500, "$result is not a valid xml string");
        }

        if(!$content)
            return;
        $results = $content->xpath("//rss/channel/item");
        if(!$results)
            return;
        foreach($results as $res)
        {
            $title = $res->{"title"};
            $link = $res->{"link"};
            $anzeigeLink = $link;
            $descr = $res->{"description"};

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
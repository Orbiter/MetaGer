<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Overture extends Searchengine
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
        $results = $content->xpath('//Results/ResultSet[@id="inktomi"]/Listing');
        foreach ($results as $result) {
            $title       = $result["title"];
            $link        = $result->{"ClickUrl"}->__toString();
            $anzeigeLink = $result["siteHost"];
            $descr       = $result["description"];
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

        # Nun noch die Werbeergebnisse:
        $ads = $content->xpath('//Results/ResultSet[@id="searchResults"]/Listing');
        foreach ($ads as $ad) {
            $title       = $ad["title"];
            $link        = $ad->{"ClickUrl"}->__toString();
            $anzeigeLink = $ad["siteHost"];
            $descr       = $ad["description"];
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

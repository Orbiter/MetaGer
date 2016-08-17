<?php

namespace app\Models\parserSkripte;

use App\Models\Result;
use App\Models\Searchengine;

class Onenewspagegermany extends Searchengine
{
    public $results = [];

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {
        $counter = 0;
        foreach (explode("\n", $result) as $line) {
            $line = trim($line);
            if (strlen($line) > 0) {
                # Hier bekommen wir jedes einzelne Ergebnis
                $result = explode("|", $line);
                if (sizeof($result) < 3) {
                    continue;
                }
                $counter++;
                $this->results[] = new Result(
                    $this->engine,
                    trim(strip_tags($result[0])),
                    $result[2],
                    $result[2],
                    $result[1],
                    $this->gefVon,
                    $counter
                );
            }

        }

    }
}

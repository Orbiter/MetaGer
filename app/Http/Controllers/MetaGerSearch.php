<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
#use App\MetaGer\Forwarder;
#use App\MetaGer\Results;
#use App\MetaGer\Search;
use App;
use App\MetaGer;


class MetaGerSearch extends Controller
{

    public function search(Request $request, MetaGer $metager)
    {
        $time = microtime();
        # Mit gelieferte Formulardaten parsen und abspeichern:
        $metager->parseFormData($request);
        if($metager->getFokus() !== "bilder" )
        {
            # Nach Spezialsuchen überprüfen:
            $metager->checkSpecialSearches($request);
        }
        # Alle Suchmaschinen erstellen
        $metager->createSearchEngines($request);

        # Alle Ergebnisse vor der Zusammenführung ranken:
        $metager->rankAll();

        # Ergebnisse der Suchmaschinen kombinieren:
        $metager->combineResults();

        $metager->removeInvalids();
        # Die Ausgabe erstellen:
        return $metager->createView();
    }

    public function quicktips(Request $request)
    {

    }

}
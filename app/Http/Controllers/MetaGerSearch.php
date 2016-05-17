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

    public function test(Request $request, MetaGer $metager)
    {
        # Mit gelieferte Formulardaten parsen und abspeichern:
        $metager->parseFormData($request);
        # Nach Spezialsuchen überprüfen:
        $metager->checkSpecialSearches($request);
        # Alle Suchmaschinen erstellen
        $metager->createSearchEngines($request);
        # Ergebnisse der Suchmaschinen kombinieren:
        $metager->combineResults();
        # Die Ausgabe erstellen:
        return $metager->createView();
    }

    public function search(Request $request)
    {
       
 
        $searchengines = Search::loadSearchEngines($request);
        $results = new Results($searchengines);

        
        

        

        return print_r( $viewResults, TRUE);
    }

}
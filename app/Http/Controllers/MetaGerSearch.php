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
        #die($request->header('User-Agent'));
        $time = microtime();
        # Mit gelieferte Formulardaten parsen und abspeichern:
        $metager->parseFormData($request);
        #if($metager->getFokus() !== "bilder" )
        #{
            # Nach Spezialsuchen überprüfen:
            $metager->checkSpecialSearches($request);
        #}
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
        $q = $request->input('q', '');

        # Zunächst den Spruch
        $spruecheFile = storage_path() . "/app/public/sprueche.txt";
        if( file_exists($spruecheFile) && $request->has('sprueche') )
        {
            $sprueche = file($spruecheFile);
            $spruch = $sprueche[array_rand($sprueche)];
        }else
        {
            $spruch = "";
        }

        # Die manuellen Quicktips:
        $file = storage_path() . "/app/public/qtdata.csv";
        
        $mquicktips = [];
        if( file_exists($file) && $q !== '')
        {
            $file = fopen($file, 'r');
            while (($line = fgetcsv($file)) !== FALSE) {
                $words = array_slice($line,3);
                $isIn = FALSE;
                foreach($words as $word){
                        $word = strtolower($word);
                        if(strpos($q, $word) !== FALSE){
                                $isIn = TRUE;
                                break;
                        }
                }
                if($isIn === TRUE){
                        $quicktip = array('QT_Type' => "MQT");
                        $quicktip["URL"] = $line[0];
                        $quicktip["title"] = $line[1];
                        $quicktip["descr"] = $line[2];
                        $mquicktips[] = $quicktip;
                }
        }
        fclose($file);
        }

        # Wikipedia Quicktip
        $quicktips = [];
        $url = "http://de.wikipedia.org/w/api.php?action=query&titles=".urlencode(implode("_",array_diff(explode(" ",$q),array("wikipedia"))))."&prop=info|extracts|categories&inprop=url|displaytitle&exintro&exsentences=3&format=json";
        $decodedResponse = json_decode($this->get($url), true);
        if( isset($decodedResponse["query"]["pages"]) )
        {
            foreach($decodedResponse["query"]["pages"] as $result)
            {
                if( isset($result['displaytitle']) && isset($result['fullurl']) && isset($result['extract']) )
                {
                    $quicktip = [];
                    $quicktip["title"] = $result['displaytitle'];
                    $quicktip["URL"] = $result['fullurl'];
                    $quicktip["descr"] = strip_tags($result['extract']);
                    $quicktip['gefVon'] = "aus <a href=\"https://de.wikipedia.org\" target=\"_blank\">Wikipedia, der freien Enzyklopädie</a>";

                    $quicktips[] = $quicktip;
                }
            }
        }
        $mquicktips = array_merge($mquicktips, $quicktips);

        # Uns Natürlich das wussten Sie schon:
        $file = storage_path() . "/app/public/tips.txt";
        if( file_exists($file) )
        {
            $tips = file($file);
            $tip = $tips[array_rand($tips)];

            $mquicktips[] = ['title' => 'Wussten Sie schon?', 'descr' => $tip, 'URL' => '/tips'];   
        }   

        # Uns die Werbelinks:
        $file = storage_path() . "/app/public/ads.txt";
        if( file_exists($file) )
        {
            $ads = json_decode(file_get_contents($file), true);
            $ad = $ads[array_rand($ads)];

            $mquicktips[] = ['title' => $ad['title'], 'descr' => $ad['descr'], 'URL' => $ad['URL']];   
        }   
        return view('quicktip')
            ->with('spruch', $spruch)
            ->with('mqs', $mquicktips);

            
    }

    public function tips()
    {
        $file = storage_path() . "/app/public/tips.txt";
        $tips = [];
        if( file_exists($file) )
        {
            $tips = file($file);
        }
        return view('tips')
            ->with('title', 'MetaGer - Tipps & Tricks')
            ->with('tips', $tips);
    }

    function get($url) {
        return file_get_contents($url);
    } 

}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MetaGer\Forwarder;
use App\MetaGer\Results;
use App;
use App\MetaGer\Search;

class MetaGerSearch extends Controller
{
    /**
     * Select a free Server to forward the Request to:
     *
     * @param  int  $id
     * @return Response
     */
    public function forwardToServer()
    {
        
        return Forwarder::getFreeServer();
        return var_dump($serversArray);
        return $cfg['redis']['password'];


        #return view('index', [ 'title' => 'MetaGer: Sicher suchen & finden, Privatsphäre schützen']);
    }

    public function search(Request $request)
    {
        # Zunächst überprüfen wir die eingegebenen Einstellungen:
        # FOKUS
        $fokus = $request->input('focus', 'web');
        $fokus = trans('fokiNames.'.$fokus);
        if(strpos($fokus,".")){
            $fokus = trans('fokiNames.web');
        }
        define("FOKUS", $fokus);
        # SUMA-FILE
        if(App::isLocale("en")){
            define("SUMA_FILE", config_path() . "/sumasEn.xml");
        }else{
            define("SUMA_FILE", config_path() . "/sumas.xml");
        }
        if(!file_exists(SUMA_FILE)){
            die("Suma-File konnte nicht gefunden werden");
        }
        # Sucheingabe:
        $eingabe = trim($request->input('eingabe', ''));
        if(strlen($eingabe) === 0){
            return 'Achtung: Sie haben keinen Suchbegriff eingegeben. Sie können ihre Suchbegriffe oben eingeben und es erneut versuchen.';
        }else{
            define("Q", $eingabe);
        }
        # IP:
        if( isset($_SERVER['HTTP_FROM']) )
        {
            define("IP", $_SERVER['HTTP_FROM']);
        }else
        {
            define("IP", "127.0.0.1");
        }
        # Language:
        if( isset($_SERVER['HTTP_LANGUAGE']) )
        {
            define("LANGUAGE", $_SERVER['HTTP_LANGUAGE']);
        }else
        {
            define("LANGUAGE", "");
        }
        # Category
        define("CATEGORY", $request->input('category', ''));
 
        $searchengines = Search::loadSearchEngines($request);
        $results = new Results($searchengines);
        return $results->results;
    }

}
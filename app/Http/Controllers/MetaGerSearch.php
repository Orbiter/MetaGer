<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MetaGer\Forwarder;
use App\MetaGer\Results;

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
        $results = new Results($request);
        return $results->loadSearchEngines();
    }

}
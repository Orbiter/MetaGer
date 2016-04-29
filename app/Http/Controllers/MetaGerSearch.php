<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\MetaGer\Forwarder;

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

}
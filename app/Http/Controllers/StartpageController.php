<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class StartpageController extends Controller
{
    /**
     * Load Startpage accordingly to the given URL-Parameter and Mobile
     *
     * @param  int  $id
     * @return Response
     */
    public function loadStartPage($locale = "de")
    {
        \App::setLocale($locale);
        return view('index', [ 'title' => 'MetaGer: Sicher suchen & finden, Privatsphäre schützen']);
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * Load Startpage accordingly to the given URL-Parameter and Mobile
     *
     * @param  int  $id
     * @return Response
     */
    public function loadStartPage($locale = "de") {
        \App::setLocale($locale);
        return view('index', [ 'title' => 'MetaGer: Sicher suchen & finden, Privatsphäre schützen']);
    }

    public function loadSubPage($locale = "de", $page = "datenschutz") {
        \App::setLocale($locale);
        return view('$page', [ 'title' => 'MetaGer: Sicher suchen & finden, Privatsphäre schützen']);
    }
}
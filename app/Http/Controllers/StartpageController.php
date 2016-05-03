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
        return view('index', [ 
            'title' => 'MetaGer: Sicher suchen & finden, Privatsphäre schützen', 
            'homeIcon']);
    }

    public function loadPage($subpage)
    {
        /* TODO CSS und Titel laden
        $css = array(
            'datenschutz' => 'privacy.css',
        );

        if (in_array($subpage, $css)) {
            return view($subpage, [ 'title' => 'Datenschutz Richtlinien', 'css' => $css[$subpage]]);
        } else {
            return view($subpage, [ 'title' => 'Datenschutz Richtlinien']);
        }*/
        return view($subpage, [ 'title' => 'Datenschutz Richtlinien']);
    }

    public function loadLocalPage($locale = "de", $subpage = "datenschutz")
    {
        \App::setLocale($locale);
        return loadPage($subpage);
    }
}
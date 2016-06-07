<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StartpageController extends Controller
{
    /**
     * Load Startpage accordingly to the given URL-Parameter and Mobile
     *
     * @param  int  $id
     * @return Response
     */
    /* public function loadStartPage($locale = "de")
    {
        \App::setLocale($locale);
        return view('index', [ 
            'title' => 'MetaGer: Sicher suchen & finden, Privatsphäre schützen', 
            'homeIcon']);
    } */

    public function loadStartPage(Request $request) 
    {
        $focusPages = [];
        foreach($request->all() as $key => $value)
        {
            if($value === 'on' && $key != 'param_sprueche' && $key != 'param_tab') 
            {
               $focusPages[] = str_replace('param_', '', $key);
            }
        }

        return view('index')
            ->with('title', trans('titles.index'))
            ->with('homeIcon')
            ->with('focus', $request->input('focus', 'web'))
            ->with('lang', $request->input('param_lang', 'all'))
            ->with('resultCount', $request->input('param_resultCount', '20'))
            ->with('time', $request->input('param_time', '1000'))
            ->with('sprueche', $request->input('param_sprueche', 'off'))
            ->with('tab', $request->input('param_sprueche', 'off'))
            ->with('focusPages', $focusPages);
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
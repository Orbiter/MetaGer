<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;


class SitesearchController extends Controller
{
    public function loadPage(Request $request)
    {
        return view('widget.sitesearch')
            ->with('title', trans('titles.sitesearch'))
            ->with('css', 'sitesearch.css')
            ->with('site', $request->input('site', ''))
            ->with('navbarFocus', 'dienste');
    }
}
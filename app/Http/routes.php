<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

    Route::group(['prefix' => LaravelLocalization::setLocale()], function()
    {
        /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
        Route::get('/', function()
        {
            return view('index', [ 
            'title' => 'MetaGer: Sicher suchen & finden, Privatsphäre schützen', 
            'homeIcon']);
        });

        Route::get('impressum', function()
        {
            return view('impressum')
                ->with('title', 'Impressum')
                ->with('css', 'impressum.css');
        });

        Route::get('about', function()
        {
            return view('about')
                ->with('title', 'Über Uns');
        });
        Route::get('team', function()
        {
            return view('team.team')
                ->with('title', 'Team')
                ->with('css', 'team.css');
        });
        Route::get('team/pubkey-wsb', function()
        {
            return view('team.pubkey-wsb')
                ->with('title', 'Team');
        });

        Route::get('kontakt', function()
        {
            return view('kontakt.kontakt')
                ->with('title', 'Kontakt')
                ->with('css', 'kontakt.css')
                ->with('js', ['openpgp.min.js','kontakt.js']);
        });

        Route::post('kontakt', 'MailController@contactMail');

        Route::get('spende', function()
        {
            return view('spende')
                ->with('title', 'Kontakt')
                ->with('css', 'donation.css');
        });
        Route::post('spende', 'MailController@donation');

        Route::get('datenschutz', function()
        {
            return view('datenschutz')
                ->with('title', 'Datenschutz und Privatsphäre')
                ->with('css', 'privacy.css');
        });

        Route::get('hilfe', function()
        {
            return view('hilfe')
                ->with('title', 'MetaGer - hilfe')
                ->with('css', 'help.css');
        });

        Route::get('meta/meta.ger3', 'MetaGerSearch@search');
    });
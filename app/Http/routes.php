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
                ->with('title', 'Impressum - MetaGer')
                ->with('css', 'impressum.css');
        });

        Route::get('about', function()
        {
            return view('about')
                ->with('title', 'Über Uns - MetaGer')
                ->with('css', 'about.css');
        });
        Route::get('team', function()
        {
            return view('team.team')
                ->with('title', 'Team - MetaGer')
                ->with('css', 'team.css');
        });
        Route::get('team/pubkey-wsb', function()
        {
            return view('team.pubkey-wsb')
                ->with('title', 'Team - MetaGer');
        });

        Route::get('kontakt', function()
        {
            return view('kontakt.kontakt')
                ->with('title', 'Kontakt - MetaGer')
                ->with('css', 'kontakt.css')
                ->with('js', ['openpgp.min.js','kontakt.js']);
        });

        Route::post('kontakt', 'MailController@contactMail');

        Route::get('meta/meta.ger3', 'MetaGerSearch@search');

        Route::get('spende', function()
        {
            return view('spende')
                ->with('title', 'Spenden - MetaGer')
                ->with('css', 'donation.css');
        });
        Route::post('spende', 'MailController@donation');

        Route::get('datenschutz', function()
        {
            return view('datenschutz')
                ->with('title', 'Datenschutz und Privatsphäre - MetaGer')
                ->with('css', 'privacy.css');
        });

        Route::get('hilfe', function()
        {
            return view('hilfe')
                ->with('title', 'Hilfe - MetaGer')
                ->with('css', 'help.css');
        });

        Route::get('meta/meta.ger3', 'MetaGerSearch@test');

        Route::get('settings', function()
        {
            return view('settings')
                ->with('title', 'Einstellungen') // TODO Titel übersetzen
                ->with('css', 'settings.css')
                ->with('js', ['settings.js']);
        });
    });

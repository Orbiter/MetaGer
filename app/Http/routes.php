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
        /* Route::get('/', function()
        {
            return view('index', [ 
            'title' => trans('titles.index'), 
            'homeIcon']);
        }); */

        Route::get('/', 'StartpageController@loadStartPage');

        Route::get('impressum', function()
        {
            return view('impressum')
                ->with('title', trans('titles.impressum'))
                ->with('css', 'impressum.css');
        });

        Route::get('about', function()
        {
            return view('about')
                ->with('title', trans('titles.about'))
                ->with('css', 'about.css');
        });
        Route::get('team', function()
        {
            return view('team.team')
                ->with('title', trans('titles.team'))
                ->with('css', 'team.css');
        });
        Route::get('team/pubkey-wsb', function()
        {
            return view('team.pubkey-wsb')
                ->with('title', trans('titles.team'));
        });

        Route::get('kontakt', function()
        {
            return view('kontakt.kontakt')
                ->with('title', trans('titles.kontakt'))
                ->with('css', 'kontakt.css')
                ->with('js', ['openpgp.min.js','kontakt.js']);
        });

        Route::post('kontakt', 'MailController@contactMail');

        Route::get('spende', function()
        {
            return view('spende')
                ->with('title', trans('titles.spende'))
                ->with('css', 'donation.css');
        });
        Route::post('spende', 'MailController@donation');

        Route::get('datenschutz', function()
        {
            return view('datenschutz')
                ->with('title', trans('titles.datenschutz'))
                ->with('css', 'privacy.css');
        });

        Route::get('hilfe', function()
        {
            return view('hilfe')
                ->with('title', trans('titles.hilfe'))
                ->with('css', 'help.css');
        });

        Route::get('widget', function()
        {
            return view('widget')
                ->with('title', trans('titles.widget'))
                ->with('css', 'widget.css');
        });
        
        Route::get('settings', function()
        {
            return view('settings')
                ->with('title', 'Einstellungen') // TODO Titel übersetzen
                ->with('css', 'settings.css')
                ->with('js', ['settings.js']);
        });

        
        Route::get('meta/meta.ger3', 'MetaGerSearch@search');
        Route::get('meta/picture', 'Pictureproxy@get');

        Route::get('qt', 'MetaGerSearch@quicktips');
        Route::get('tips', 'MetaGerSearch@tips');
        Route::get('opensearch.xml', 'StartpageController@loadPlugin');
    });

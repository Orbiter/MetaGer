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

# Unsere Startseite. Hier fängt alles an
Route::get('{locale?}', 
	[ 'uses' => 'PageController@loadStartPage']
)
->where('locale', '[a-z]{1,2}');

# Unsere Startseite. Hier fängt alles an
Route::get('/{locale?}/{page}', 
	[ 'uses' => 'PageController@loadSubPage']
)
->where('locale', '[a-z]{1,2}');

# Unsere MetaGer Suche!!
Route::get('/meta/meta.ger3', 
	[ 'uses' => 'MetaGerSearch@forwardToServer' ]
);

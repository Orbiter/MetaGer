<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Response;
use App\Http\Requests;
use PiwikTracker;

class ImageController extends Controller
{
    public function generateImage(Request $request)
    {
    	#Piwik Code
		PiwikTracker::$URL = 'http://piwik.metager3.de';
		$piwikTracker = new PiwikTracker( $idSite = 1 );

		// Cookies ausschalten
		$piwikTracker->disableCookieSupport();
		$piwikTracker->deleteCookies();

		$site = $request->input('site', '/');

		// Sendet Tracker request per http
		$piwikTracker->doTrackPageView($site);


    	$path = public_path() . '/img/1px.png';
		$fileType = File::type($path);
		$response = Response::make( File::get($path), 200);
		$response->header('Content-Type', $fileType);
		return $response;
    }
}

<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

 # Unser erster Schritt wird sein, IP-Adresse und USER-Agent zu anonymisieren, damit 
 # nicht einmal wir selbst noch Zugriff auf die Daten haben:
if( !isset($_SERVER['HTTP_X_FORWARDED_FOR'] ))
{
	$_SERVER['REMOTE_ADDR'] = preg_replace("/(\d+)\.(\d+)\.\d+.\d+/s", "$1.$2.0.0", $_SERVER['REMOTE_ADDR']);
}else
{
	$_SERVER['HTTP_X_FORWARDED_FOR'] = preg_replace("/(\d+)\.(\d+)\.\d+.\d+/s", "$1.$2.0.0", $_SERVER['HTTP_X_FORWARDED_FOR']);
}
$agentPieces = explode(" ", $_SERVER['HTTP_USER_AGENT']);

for($i = 0; $i < count($agentPieces); $i++)
{
	$agentPieces[$i] = preg_replace("/(\d+\.\d+)/s", "0.0", $agentPieces[$i]);
	$agentPieces[$i] = preg_replace("/([^\/]*)\/\w+/s", "$1/0.0", $agentPieces[$i]);
}
$_SERVER['HTTP_USER_AGENT'] = implode(" ", $agentPieces);

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__.'/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);

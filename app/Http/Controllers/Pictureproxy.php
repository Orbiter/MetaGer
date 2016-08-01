<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
#use App\MetaGer\Forwarder;
#use App\MetaGer\Results;
#use App\MetaGer\Search;
use App;
use App\MetaGer;
use Response;


class Pictureproxy extends Controller
{
    function get(Request $request) {
        if( $request->has('url') )
        {
           try{
                $arrContextOptions=array(
                    "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ),
                ); 
                $file = file_get_contents($request->input('url'), false, stream_context_create($arrContextOptions));
                $responseCode = explode(" ", $http_response_header[0])[1];
                $contentType = "";
                foreach($http_response_header as $header)
                {
                    if( strpos($header, "Content-Type:") === 0)
                    {
                        $tmp = explode(": ", $header);
                        $contentType = $tmp[1];
                    }
                }
                $response = Response::make($file, $responseCode);
                $response->header('Content-Type', $contentType);
            }catch(\ErrorException $e)
            {
                $response = Response::make("", 404);
            }
            return $response;
        }
    } 

}
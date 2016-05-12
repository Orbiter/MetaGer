<?php

namespace App\MetaGer;
use Illuminate\Http\Request;
use App\MetaGer\Searchengine;

class Search
{
	public static function loadSearchEngines(Request $request)
	{

        # Überprüfe, welche Sumas eingeschaltet sind
        $xml = simplexml_load_file(SUMA_FILE);
        $enabledSearchengines = [];
        $overtureEnabled = FALSE;
        
        if(FOKUS === "angepasst")
        {
            $sumas = $xml->xpath("suma");
            foreach($sumas as $suma)
            {
                if($request->has($suma["service"]) 
                #	|| ( FOKUS !== "bilder" 
                #		&& ($suma["name"]->__toString() === "qualigo" 
                #			|| $suma["name"]->__toString() === "similar_product_ads" 
                #			|| ( !$overtureEnabled && $suma["name"]->__toString() === "overtureAds" )
               # 			)
               # 		)
                	){
                	if($suma["name"]->__toString() === "overture")
                	{
                		$overtureEnabled = TRUE;
                	}
                    $enabledSearchengines[] = $suma;
                }
            }
        }else{
            $sumas = $xml->xpath("suma");
            foreach($sumas as $suma){
                $types = explode(",",$suma["type"]);
                if(in_array(FOKUS, $types) 
                #	|| ( FOKUS !== "bilder" 
                	#	&& ($suma["name"]->__toString() === "qualigo" 
                	#		|| $suma["name"]->__toString() === "similar_product_ads" 
                #			|| ( !$overtureEnabled && $suma["name"]->__toString() === "overtureAds" )
                #			)
              #  		)
                	){
                	if($suma["name"]->__toString() === "overture")
                	{
                		$overtureEnabled = TRUE;
                	}
                    $enabledSearchengines[] = $suma;
                }
            }
        }
        
		$engines = [];
		foreach($enabledSearchengines as $engine){
            $path = "App\MetaGer\parserSkripte\\" . $engine["name"]->__toString();
			$engines[] = new $path($engine);
		}

        return $engines;
	}
}
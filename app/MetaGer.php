<?php
namespace App;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App;

class MetaGer
{
	# Einstellungen für die Suche
	protected $fokus;
	protected $eingabe;
	protected $q;
	protected $category;
	protected $time;
	protected $page;
	protected $lang;
	protected $cache = "";
	protected $site;
	protected $hostBlacklist = [];
	protected $domainBlacklist = [];
	protected $stopWords = [];
	protected $engines = [];
	protected $results = [];
	protected $warnings = [];
	# Daten über die Abfrage
	protected $ip;
	protected $language;
	protected $agent;
	# Konfigurationseinstellungen:
	protected $sumaFile;
	protected $mobile;
	protected $resultCount;
	protected $sprueche;

	function __construct()
	{
		#$this->eingabe = Input::get('eingabe');
	}

	public function createView()
	{
		$viewResults = [];
        # Wir extrahieren alle notwendigen Variablen und geben Sie an unseren View:
        foreach($this->results as $result)
        {
            $viewResults[] = get_object_vars($result);
        }
        return view('metager3')
            ->with('results', $viewResults)
            ->with('eingabe', $this->eingabe)
            ->with('warnings', $this->warnings);
	}

	public function combineResults ()
	{
		foreach($this->engines as $engine)
		{
			$this->results = array_merge($this->results, $engine->results);
		}
	}

	public function createSearchEngines (Request $request)
	{
		# Curl-Multihandle um die Ergebnisse abzurufen:
		$mh = curl_multi_init();

		# Überprüfe, welche Sumas eingeschaltet sind
        $xml = simplexml_load_file($this->sumaFile);
        $enabledSearchengines = [];
        $overtureEnabled = FALSE;
        
        if($this->fokus === "angepasst")
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
                if(in_array($this->fokus, $types) 
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
            $path = "App\Models\parserSkripte\\" . ucfirst($engine["name"]->__toString());
			$engines[] = new $path($engine, $mh, $this->q, $this->time);
		}

		# Nun führen wir die Get-Requests aus und warten auf alle Ergebnisse:
		$running = null;
		do
		{
			curL_multi_exec($mh, $running);
		}while($running);

		# Und beenden noch alle Handles
		foreach($engines as $engine)
		{
			$engine->removeCurlHandle($mh);
			$engine->loadResults();
		}

		# Und auch den Multicurl-Handle:
		curl_multi_close($mh);

        $this->engines = $engines;
	}

	public function parseFormData (Request $request)
	{
		if($request->input('encoding', '') !== "utf8")
		{
			# In früheren Versionen, als es den Encoding Parameter noch nicht gab, wurden die Daten in ISO-8859-1 übertragen
			$input = $request->all();
			foreach($input as $key => $value)
			{
				$input[$key] = mb_convert_encoding("$value", "UTF-8", "ISO-8859-1");
			}
			$request->replace($input);
		}
		# Zunächst überprüfen wir die eingegebenen Einstellungen:
        # FOKUS
        $this->fokus = trans('fokiNames.'
        	. $request->input('focus', 'web'));
        if(strpos($this->fokus,"."))
        {
            $this->fokus = trans('fokiNames.web');
        }

        # SUMA-FILE
        if(App::isLocale("en")){
            $this->sumaFile = config_path() . "/sumasEn.xml";
        }else{
            $this->sumaFile = config_path() . "/sumas.xml";
        }
        if(!file_exists($this->sumaFile))
        {
            die("Suma-File konnte nicht gefunden werden");
        }

        # Sucheingabe:
        $this->eingabe = trim($request->input('eingabe', ''));
        if(strlen($this->eingabe) === 0)
        {
            $this->warnings[] = 'Achtung: Sie haben keinen Suchbegriff eingegeben. Sie können ihre Suchbegriffe oben eingeben und es erneut versuchen.';
        }
        $this->q = $this->eingabe;

        # IP:
        if( isset($_SERVER['HTTP_FROM']) )
        {
            $this->ip = $_SERVER['HTTP_FROM'];
        }else
        {
            $this->ip = "127.0.0.1";
        }
        # Language:
        if( isset($_SERVER['HTTP_LANGUAGE']) )
        {
            $this->language = $_SERVER['HTTP_LANGUAGE'];
        }else
        {
            $this->language = "";
        }
        # Category
        $this->category = $request->input('category', '');
        # Request Times:
        $this->time = $request->input('time', 1);
        # Page
        $this->page = $request->input('page', 1);
        # Lang
        $this->lang = $request->input('lang', 'all');
        if ( $this->lang !== "de" || $this->lang !== "en" || $this->lang !== "all" )
        {
        	$this->lang = "all";
        }
        $this->agent = new Agent();
        $this->mobile = $this->agent->isMobile();
        #Sprüche
        $this->sprueche = $request->input('sprueche', 'on');
        # Ergebnisse pro Seite:
        $this->resultCount = $request->input('resultCount', '20');

        # Manchmal müssen wir Parameter anpassen um den Sucheinstellungen gerecht zu werden:
        if( $request->has('dart') )
        {
        	$this->time = 10;
        	$this->warnings[] = "Hinweis: Sie haben Dart-Europe aktiviert. Die Suche kann deshalb länger dauern und die maximale Suchzeit wurde auf 10 Sekunden hochgesetzt.";
        }
        if( $this->time < 0 || $this->time > 20 )
        {
        	$this->time = 1;
        }
        if( $request->has('minism') && ( $request->has('fportal') || $request->has('harvest') ) )
        {
        	$input = $request->all();
        	$newInput = [];
        	foreach($input as $key => $value)
        	{
        		if( $key !== "fportal" && $key !== "harvest" )
        		{
        			$newInput[$key] = $value;
        		}
        	}
        	$request->replace($newInput);
        }
        if( $request->has('ebay') )
        {
        	$this->time = 2;
        	$this->warnings[] = "Hinweis: Sie haben Ebay aktiviert. Die Suche kann deshalb länger dauern und die maximale Suchzeit wurde auf 2 Sekunden hochgesetzt.";
        }
        if( App::isLocale("en") )
        {
        	$this->sprueche = "off";
        }
        if($this->resultCount <= 0 || $this->resultCount > 200 )
        {
        	$this->resultCount = 1000;
        }
        if( $request->has('onenewspageAll') || $request->has('onenewspageGermanyAll') )
        {
        	$this->time = 5000;
        	$this->cache = "cache";
        }
	}

	public function checkSpecialSearches (Request $request)
	{
		# Site Search:
		if(preg_match("/(.*)\bsite:(\S+)(.*)/si", $this->q, $match))
		{
			$this->site = $match[2];
			$this->q = $match[1] . $match[3];
			$this->warnings[] = "Sie führen eine Sitesearch durch. Es werden nur Ergebnisse von der Seite: \"" . $this->site . "\" angezeigt.";
		}
		# Wenn die Suchanfrage um das Schlüsselwort "-host:*" ergänzt ist, sollen bestimmte Hosts nicht eingeblendet werden
		# Wir prüfen, ob das hier der Fall ist:
		while(preg_match("/(.*)(^|\s)-host:(\S+)(.*)/si", $this->q, $match))
		{
			$this->hostBlacklist[] = $match[3];
			$this->q = $match[1] . $match[4];
		}
		if( sizeof($this->hostBlacklist) > 0 )
		{
			$hostString = "";
			foreach($this->hostBlacklist as $host)
			{
				$hostString .= $host . ", ";
			}
			$hostString = rtrim($hostString, ", ");
			$this->warnings[] = "Ergebnisse von folgenden Hosts werden nicht angezeigt: \"" . $hostString . "\"";
		}
		# Wenn die Suchanfrage um das Schlüsselwort "-domain:*" ergänzt ist, sollen bestimmte Domains nicht eingeblendet werden
		# Wir prüfen, ob das hier der Fall ist:
		while(preg_match("/(.*)(^|\s)-domain:(\S+)(.*)/si", $this->q, $match))
		{
			$this->domainBlacklist[] = $match[3];
			$this->q = $match[1] . $match[4];
		}
		if( sizeof($this->domainBlacklist) > 0 )
		{
			$domainString = "";
			foreach($this->domainBlacklist as $domain)
			{
				$domainString .= $domain . ", ";
			}
			$domainString = rtrim($domainString, ", ");
			$this->warnings[] = "Ergebnisse von folgenden Domains werden nicht angezeigt: \"" . $domainString . "\"";
		}
		
		# Alle mit "-" gepräfixten Worte sollen aus der Suche ausgeschlossen werden.
		# Wir prüfen, ob das hier der Fall ist:
		while(preg_match("/(.*)(^|\s)-(\S+)(.*)/si", $this->q, $match))
		{
			$this->stopWords[] = $match[3];
			$this->q = $match[1] . $match[4];
		}
		if( sizeof($this->stopWords) > 0 )
		{
			$stopwordsString = "";
			foreach($this->stopWords as $stopword)
			{
				$stopwordsString .= $stopword . ", ";
			}
			$stopwordsString = rtrim($stopwordsString, ", ");
			$this->warnings[] = "Sie machen eine Ausschlusssuche. Ergebnisse mit folgenden Wörtern werden nicht angezeigt: \"" . $stopwordsString . "\"";
		}

		# Meldung über eine Phrasensuche
		if(preg_match("/\"(.+)\"/si", $this->q, $match)){
			$this->warnings[] = "Sie führen eine Phrasensuche durch: \"" . $match[1] . "\"";
		}
	}
}
<?php
namespace App;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App;
use Storage;
use Log;
use Config;
use Redis;
use App\lib\TextLanguageDetect\TextLanguageDetect;
use App\lib\TextLanguageDetect\LanguageDetect\TextLanguageDetectException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
#use \Illuminate\Pagination\Paginator;

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
    protected $ads = [];
	protected $warnings = [];
    protected $errors = [];
    protected $addedHosts = [];
	# Daten über die Abfrage
	protected $ip;
	protected $language;
	protected $agent;
	# Konfigurationseinstellungen:
	protected $sumaFile;
	protected $mobile;
	protected $resultCount;
	protected $sprueche;
    protected $domainsBlacklisted = [];
    protected $urlsBlacklisted = [];
    protected $url;
    protected $languageDetect;

	function __construct()
	{
        $this->starttime = microtime(true);   
        if( file_exists(config_path() . "/blacklistDomains.txt") && file_exists(config_path() . "/blacklistUrl.txt") )
        {
            # Blacklists einlesen:
            $tmp = file_get_contents(config_path() . "/blacklistDomains.txt");
            $this->domainsBlacklisted = explode("\n", $tmp);
            $tmp = file_get_contents(config_path() . "/blacklistUrl.txt");
            $this->urlsBlacklisted = explode("\n", $tmp);
        }else
        {
            Log::warning("Achtung: Eine, oder mehrere Blacklist Dateien, konnten nicht geöffnet werden");
        }

        $this->languageDetect = new TextLanguageDetect();
        $this->languageDetect->setNameMode("2");
	}

    public function getHashCode ()
    {
        $string = url()->full();
        return md5($string);
    }

    public function rankAll ()
    {
        foreach( $this->engines as $engine )
        {
            $engine->rank($this);
        }
    }

	public function createView()
	{
		$viewResults = [];

        # Wir extrahieren alle notwendigen Variablen und geben Sie an unseren View:
        foreach($this->results as $result)
        {
            $viewResults[] = get_object_vars($result);
        }

        # Wir müssen natürlich noch den Log für die durchgeführte Suche schreiben:
        $this->createLogs();

        if( $this->fokus === "bilder" )
        {
            switch ($this->out) 
            {
                case 'results':
                    return view('metager3bilderresults')
                        ->with('results', $viewResults)
                        ->with('eingabe', $this->eingabe)
                        ->with('mobile', $this->mobile)
                        ->with('warnings', $this->warnings)
                        ->with('errors', $this->errors)
                        ->with('metager', $this);
                default:
                    return view('metager3bilder')
                        ->with('results', $viewResults)
                        ->with('eingabe', $this->eingabe)
                        ->with('mobile', $this->mobile)
                        ->with('warnings', $this->warnings)
                        ->with('errors', $this->errors)
                        ->with('metager', $this);
            }
        }

        switch ($this->out) {
            case 'results':
                return view('metager3results')
                    ->with('results', $viewResults)
                    ->with('eingabe', $this->eingabe)
                    ->with('mobile', $this->mobile)
                    ->with('warnings', $this->warnings)
                    ->with('errors', $this->errors)
                    ->with('metager', $this);
                break;
            case 'results-with-style':
                return view('metager3')
                    ->with('results', $viewResults)
                    ->with('eingabe', $this->eingabe)
                    ->with('mobile', $this->mobile)
                    ->with('warnings', $this->warnings)
                    ->with('errors', $this->errors)
                    ->with('metager', $this)
                    ->with('suspendheader', "yes");
                break;
            default:
                return view('metager3')
                    ->with('eingabe', $this->eingabe)
                    ->with('mobile', $this->mobile)
                    ->with('warnings', $this->warnings)
                    ->with('errors', $this->errors)
                    ->with('metager', $this);
                break;
        }
	}

    private function createLogs()
    {
        $redis = Redis::connection('redisLogs');
        try
        {
            $logEntry = "";
            $logEntry .= "[" . date(DATE_RFC822, mktime(date("H"),date("i"), date("s"), date("m"), date("d"), date("Y"))) . "]";
            $logEntry .= " From=" . $this->ip;
            $logEntry .= " pid=" . getmypid();
            $anonId= md5("MySeCrEtSeEdFoRmd5"
            .$this->request->header('Accept')
            .$this->request->header('Accept-Charset')
            .$this->request->header('Accept-Encoding')
            .$this->request->header('HTTP_LANGUAGE')
            .$this->request->header('User-Agent')
            .$this->request->header('Keep-Alive')
            .$this->request->header('X-Forwarded-For')
            .date("H")); # Wichtig!! Den Parameter um die aktuelle Stunde erweitern. Ansonsten wäre die anonId dauerhaft einem Nutzer zuzuordnen.
            $logEntry .= " anonId=$anonId";
            $logEntry .= " ref=" . $this->request->header('Referer');
            $useragent = $this->request->header('User-Agent');
            $useragent = str_replace("(", " ", $useragent);
            $useragent = str_replace(")", " ", $useragent);
            $useragent = str_replace(" ", "", $useragent);
            $logEntry .= " ua=" . $useragent;
            $logEntry .= " iter= mm= time=" . round((microtime(true)-$this->starttime), 2) . " serv=" . $this->fokus . " which= hits= stringSearch= QuickTips= SSS= check=";
            $logEntry .= " search=" . $this->eingabe;
            $redis->rpush('logs.search', $logEntry);
        }catch( \Exception $e)
        {
            return;
        }
    }

    public function removeInvalids ()
    {
        $results = [];
        foreach($this->results as $result)
        {
            if($result->isValid($this))
                $results[] = $result;
        }
        #$this->results = $results;
    }

	public function combineResults ()
	{
		foreach($this->engines as $engine)
		{
            foreach($engine->results as $result)
            {
                if($result->valid)
                    $this->results[] = $result;
            }
            foreach($engine->ads as $ad)
            {
                $this->ads[] = $ad;
            }
		}
        uasort($this->results, function($a, $b){
            if($a->getRank() == $b->getRank())
                return 0;
            return ($a->getRank() < $b->getRank()) ? 1 : -1;
        });
        # Validate Results
        $newResults = [];
        foreach($this->results as $result)
        {
            if($result->isValid($this))
                $newResults[] = $result;
        }
        $this->results = $newResults;

        $counter = 0;
        $firstRank = 0;
        foreach($this->results as $result)
        {
            if($counter === 0)
                $firstRank = $result->rank;
            $counter++;
            $result->number = $counter;
            $confidence = 0;
            if($firstRank > 0)
                $confidence = $result->rank/$firstRank;
            else
                $confidence = 0;
            if($confidence > 0.65)
                $result->color = "#FF4000";
            elseif($confidence > 0.4)
                $result->color = "#FF0080";
            elseif($confidence > 0.2)
                $result->color = "#C000C0";
            else
                $result->color = "#000000";
        }

        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset= $currentPage-1;

        //Create a new Laravel collection from the array data
        $collection = new Collection($this->results);

        //Define how many items we want to be visible in each page
        $perPage = $this->resultCount;

        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice($offset * $perPage, $perPage)->all();

        //Create our paginator and pass it to the view
        $paginatedSearchResults= new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);
        $paginatedSearchResults->setPath('/meta/meta.ger3');
        foreach($this->request->all() as $key => $value)
        {
            if( $key === "out" )
                continue;
            $paginatedSearchResults->addQuery($key, $value);
        }

        $this->results = $paginatedSearchResults;

        $this->validated = false;
        if( isset($this->password) )
        {
            # Wir bieten einen bezahlten API-Zugriff an, bei dem dementsprechend die Werbung ausgeblendet wurde:
            # Aktuell ist es nur die Uni-Mainz. Deshalb überprüfen wir auch nur diese.
            $password = getenv('mainz');
            $eingabe = $this->eingabe;
            $password = md5($eingabe . $password);
            if( $this->password === $password )
            {
                $this->ads = [];
                $this->validated = true;
            }
        }
	}

	public function createSearchEngines (Request $request)
	{

        if( !$request->has("eingabe") )
            return;

		# Überprüfe, welche Sumas eingeschaltet sind
        $xml = simplexml_load_file($this->sumaFile);
        $enabledSearchengines = [];
        $overtureEnabled = FALSE;
        $countSumas = 0;
        if($this->fokus === "angepasst")
        {
            $sumas = $xml->xpath("suma");

            foreach($sumas as $suma)
            {
                if($request->has($suma["name"]) 
                	|| ( $this->fokus !== "bilder" 
                		&& ($suma["name"]->__toString() === "qualigo" 
                			|| $suma["name"]->__toString() === "similar_product_ads" 
                			|| ( !$overtureEnabled && $suma["name"]->__toString() === "overtureAds" )
                			)
                		)
                	){

                	if(!(isset($suma['disabled']) && $suma['disabled']->__toString() === "1"))
                    {
                        if($suma["name"]->__toString() === "overture" || $suma["name"]->__toString() === "overtureAds")
                        {
                            $overtureEnabled = TRUE;
                        }
                        if( $suma["name"]->__toString() !== "qualigo" && $suma["name"]->__toString() !== "similar_product_ads" && $suma["name"]->__toString() !== "overtureAds" )
                            $countSumas += 1;
                        $enabledSearchengines[] = $suma;
                    }
                }
            }
        }else{
            $sumas = $xml->xpath("suma");
            foreach($sumas as $suma){
                $types = explode(",",$suma["type"]);
                if(in_array($this->fokus, $types) 
                	|| ( $this->fokus !== "bilder" 
                		&& ($suma["name"]->__toString() === "qualigo" 
                			|| $suma["name"]->__toString() === "similar_product_ads" 
                			|| ( !$overtureEnabled && $suma["name"]->__toString() === "overtureAds" )
                			)
                		)
                	){
                    if(!(isset($suma['disabled']) && $suma['disabled']->__toString() === "1"))
                    {
                        if($suma["name"]->__toString() === "overture" || $suma["name"]->__toString() === "overtureAds")
                        {
                            $overtureEnabled = TRUE;
                        }
                        if( $suma["name"]->__toString() !== "qualigo" && $suma["name"]->__toString() !== "similar_product_ads" && $suma["name"]->__toString() !== "overtureAds" )
                            $countSumas += 1;
                        $enabledSearchengines[] = $suma;
                    }
                }
            }
        }

        # Sonderregelung für alle Suchmaschinen, die zu den Minisuchern gehören. Diese können alle gemeinsam über einen Link abgefragt werden
        $subcollections = [];
        $tmp = [];
        foreach($enabledSearchengines as $engine )
        {
            if( isset($engine['minismCollection']) )
                $subcollections[] = $engine['minismCollection']->__toString();
            else
                $tmp[] = $engine;
        }
        $enabledSearchengines = $tmp;
        if( sizeof($subcollections) > 0)
        {
            $count = sizeof($subcollections) * 10;
            $minisucherEngine = $xml->xpath('suma[@name="minism"]')[0];  
            $subcollections = urlencode("(" . implode(" OR ", $subcollections) . ")");
            $minisucherEngine["formData"] = str_replace("<<SUBCOLLECTIONS>>", $subcollections, $minisucherEngine["formData"]);
            $minisucherEngine["formData"] = str_replace("<<COUNT>>", $count, $minisucherEngine["formData"]);
            $enabledSearchengines[] = $minisucherEngine;
        }

        #die(var_dump($enabledSearchengines));

        if( $countSumas <= 0 )
        {
            $this->errors[] = "Achtung: Sie haben in ihren Einstellungen keine Suchmaschine ausgewählt.";
        }
		$engines = [];

        $siteSearchFailed = false;
        if( strlen($this->site) > 0 )
        {
            # Wenn eine Sitesearch durchgeführt werden soll, überprüfen wir ob eine der Suchmaschinen überhaupt eine Sitesearch unterstützt:
            $enginesWithSite = 0;
            foreach($enabledSearchengines as $engine)
            {
                if( isset($engine['hasSiteSearch']) && $engine['hasSiteSearch']->__toString() === "1" )
                {
                    $enginesWithSite++;
                }
            }
            if( $enginesWithSite === 0 )
            {
                $this->errors[] = "Sie wollten eine Sitesearch auf " . $this->site . " durchführen. Leider unterstützen die eingestellten Suchmaschinen diese nicht. Sie können <a href=\"" . $this->generateSearchLink("web", false) . "\">hier</a> die Sitesearch im Web-Fokus durchführen. Es werden ihnen Ergebnisse ohne Sitesearch angezeigt.";
                $siteSearchFailed = true;
            }else
            {
                $this->warnings[] = "Sie führen eine Sitesearch durch. Es werden nur Ergebnisse von der Seite: \"" . $this->site . "\" angezeigt.";
            }

        }            

		foreach($enabledSearchengines as $engine){

            if( !$siteSearchFailed && strlen($this->site) > 0 && ( !isset($engine['hasSiteSearch']) || $engine['hasSiteSearch']->__toString() === "0")  )
            {
                
                continue;
            }
            # Wenn diese Suchmaschine gar nicht eingeschaltet sein soll
            $path = "App\Models\parserSkripte\\" . ucfirst($engine["package"]->__toString());

            $time = microtime();
            $tmp = new $path($engine, $this);

            if($tmp->enabled && isset($this->debug))
            {
                $this->warnings[] = $tmp->service . "   Connection_Time: " . $tmp->connection_time . "    Write_Time: " . $tmp->write_time . " Insgesamt:" . ((microtime()-$time)/1000);
            }

            if($tmp->isEnabled())
            {
                $engines[] = $tmp;
                $this->sockets[$tmp->name] = $tmp->fp;
            }
		}

        # Nun passiert ein elementarer Schritt.
        # Wir warten auf die Antwort der Suchmaschinen, da wir vorher nicht weiter machen können.
        # aber natürlich nicht ewig.
        # Die Verbindung steht zu diesem Zeitpunkt und auch unsere Request wurde schon gesendet.
        # Wir geben der Suchmaschine nun bis zu 500ms Zeit zu antworten.
        $enginesToLoad = count($engines);
        $loadedEngines = 0;
        $timeStart = microtime(true);

        while( true )
        {
            $time = (microtime(true) - $timeStart) * 1000;
            $loadedEngines = intval(Redis::hlen('search.' . $this->getHashCode()));
            $canBreak = true;
            if( $overtureEnabled && !Redis::hexists('search.' . $this->getHashCode(), 'overture') && !Redis::hexists('search.' . $this->getHashCode(), 'overtureAds'))
                $canBreak = false;


            # Abbruchbedingung
            if($time < 500)
            {
                if(($enginesToLoad === 0 || $loadedEngines >= $enginesToLoad) && $canBreak)
                    break;
            }elseif( $time >= 500 && $time < $this->time)
            {
                if( ($enginesToLoad === 0 || ($loadedEngines / ($enginesToLoad * 1.0)) >= 0.8) && $canBreak )
                    break;
            }else
            {
                break;
            }
            usleep(50000);
        }

        
        foreach($engines as $engine)
        {
            if(!$engine->loaded)
            {
                try{
                    $engine->retrieveResults();
                } catch(\ErrorException $e)
                {
                    Log::error($e);
                    
                }
            }
        }
        
        # und verwerfen den Rest:
        foreach( $engines as $engine )
        {
            if( !$engine->loaded )
                $engine->shutdown();
        }

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
        $this->url = $request->url();
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
            $this->sumaFile = config_path() . "/sumas.xml";
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
        $this->ip = $request->ip();

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
        $this->time = $request->input('time', 1000);
       
        # Page
        $this->page = $request->input('page', 1);
        # Lang
        $this->lang = $request->input('lang', 'all');
        if ( $this->lang !== "de" && $this->lang !== "en" && $this->lang !== "all" )
        {
        	$this->lang = "all";
        }
        $this->agent = new Agent();
        $this->mobile = $this->agent->isMobile();

        #Sprüche
        $this->sprueche = $request->input('sprueche', 'off');
        if($this->sprueche === "off" )
            $this->sprueche = true;
        else
            $this->sprueche = false;
        # Ergebnisse pro Seite:
        $this->resultCount = $request->input('resultCount', '20');

        # Manchmal müssen wir Parameter anpassen um den Sucheinstellungen gerecht zu werden:
        if( $request->has('dart') )
        {
        	$this->time = 10000;
        	$this->warnings[] = "Hinweis: Sie haben Dart-Europe aktiviert. Die Suche kann deshalb länger dauern und die maximale Suchzeit wurde auf 10 Sekunden hochgesetzt.";
        }
        if( $this->time <= 500 || $this->time > 20000 )
        {
        	$this->time = 1000;
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
        	$this->time = 2000;
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
        if( $request->has('tab'))
        {
            if($request->input('tab') === "off")
            {
                $this->tab = "_blank";
            }else
            {
                $this->tab = "_self";
            }
        }else
        {
            $this->tab = "_blank";
        }
        if( $request->has('password') )
            $this->password = $request->input('password');
        if( $request->has('quicktips') )
            $this->quicktips = false;
        else
            $this->quicktips = true;

        $this->out = $request->input('out', "html");
        if($this->out !== "html" && $this->out !== "json" && $this->out !== "results" && $this->out !== "results-with-style")
            $this->out = "html";
        $this->request = $request;
	}

	public function checkSpecialSearches (Request $request)
	{
		# Site Search:
		if(preg_match("/(.*)\bsite:(\S+)(.*)/si", $this->q, $match))
		{
			$this->site = $match[2];
			$this->q = $match[1] . $match[3];
		}
        if( $request->has('site') )
        {
            $this->site = $request->input('site');
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

    public function getFokus ()
    {
        return $this->fokus;
    }

    public function getIp ()
    {
        return $this->ip;
    }

    public function getEingabe ()
    {
        return $this->eingabe;
    }

    public function getQ ()
    {
            return $this->q;
    }

    public function getUrl ()
    {
        return $this->url;
    }
    public function getTime ()
    {
        return $this->time;
    }

    public function getLanguage ()
    {
        return $this->language;
    }

    public function getLang ()
    {
        return $this->lang;
    }

    public function getSprueche ()
    {
        return $this->sprueche;
    }

    public function getCategory ()
    {
        return $this->category;
    }

    public function getSumaFile ()
    {
        return $this->sumaFile;
    }

    public function getUserHostBlacklist ()
    {
        return $this->hostBlacklist;
    }

    public function getUserDomainBlacklist ()
    {
        return $this->domainBlacklist;
    }

    public function getDomainBlacklist ()
    {
        return $this->domainsBlacklisted;
    }

    public function getUrlBlacklist ()
    {
        return $this->urlsBlacklisted;
    }
    public function getLanguageDetect ()
    {
        return $this->languageDetect;
    }
    public function getStopWords ()
    {
        return $this->stopWords;
    }
    public function getHostCount($host)
    {
        if(isset($this->addedHosts[$host]))
        {
            return $this->addedHosts[$host];
        }else
        {
            return 0;
        }
    }
    public function addHostCount($host)
    {
        $hash = md5($host);
        if(isset($this->addedHosts[$hash]))
        {
            $this->addedHosts[$hash] += 1;
        }else
        {
            $this->addedHosts[$hash] = 1;
        }
    }
    public function getSite()
    {
        return $this->site;
    }
    public function addLink($link)
    {
        $hash = md5($link);
        if(isset($this->addedLinks[$hash]))
        {
            return false;
        }else
        {
            $this->addedLinks[$hash] = 1;

            return true;
        }
    }

    public function generateSearchLink($fokus, $results = true)
    {
        $requestData = $this->request->except('page');
        $requestData['focus'] = $fokus;
        if($results)
            $requestData['out'] = "results";
        else
            $requestData['out'] = "";
        $link = action('MetaGerSearch@search', $requestData);
        return $link;
    }

    public function generateQuicktipLink()
    {
        $link = action('MetaGerSearch@quicktips');

        return $link;
    }

    public function generateSiteSearchLink($host)
    {
        $host = urlencode($host);
        $requestData = $this->request->except(['page','out']);
        $requestData['eingabe'] .= " site:$host";
        $requestData['focus'] = "web";
        $link = action('MetaGerSearch@search', $requestData);
        return $link;
    }

    public function generateRemovedHostLink ($host)
    {
        $host = urlencode($host);
        $requestData = $this->request->except(['page','out']);
        $requestData['eingabe'] .= " -host:$host";
        $link = action('MetaGerSearch@search', $requestData);
        return $link;
    }

    public function generateRemovedDomainLink ($domain)
    {
        $domain = urlencode($domain);
        $requestData = $this->request->except(['page','out']);
        $requestData['eingabe'] .= " -domain:$domain";
        $link = action('MetaGerSearch@search', $requestData);
        return $link;
    }

    public function getTab ()
    {
        return $this->tab;
    }
    public function getResults ()
    {
        return $this->results;
    }
    public function popAd()
    {
        if(count($this->ads) > 0)
            return get_object_vars(array_shift($this->ads));
        else
            return null;
    }
    public function getImageProxyLink($link)
    {
        $requestData = [];
        $requestData["url"] = $link;
        $link = action('Pictureproxy@get', $requestData);
        return $link;
    }
    public function showQuicktips ()
    {
        return $this->quicktips;
    }
}
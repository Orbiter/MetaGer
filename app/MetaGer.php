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

/**
*  Dies ist die Hauptklasse für jede Suche. 
*  Sie sammelt alle Ein- und Ausgaben und verarbeitet diese.
*/
class MetaGer
{
	/* Noch nicht hier oben: (unter anderem)
	*  url 				-	???
	*  language			-	???
	*  category			-	???
	*  tab				-	gewählter Ergebnistab
	*  password			-	???
	*  quicktips		-	Quicktips ein-/ausgeschaltet
	*  out				-	???
	*  request			-	Sicherung der Anfrage
	*/
	# Einstellungen für die Suche
	protected $fokus;						# Der gewählte Suchfokus
	protected $eingabe;						# Die eingegebenen Suchbegriffe
	protected $q;							# Die eingegebenen Suchbegriffe ???
	protected $category;					# 
	protected $time;						# Die maximale Suchzeit
	protected $page;						# Die ausgewählte Ergebnisseite
	protected $lang;						# Die gewählte Sprache der Suchergebnisse
	protected $cache = "";					# 
	protected $site;						# Erkannte Sitesearches
	protected $hostBlacklist = [];			# Die Blacklist der Hosts
	protected $domainBlacklist = [];		# Die Blacklist der Domains
	protected $stopWords = [];				# Erkannte Stopworte
	protected $phrases = [];				# Erkannte Phrasensuchen
	protected $engines = [];				# 
	protected $results = [];				# Die gesammelten Ergebnisse
	protected $ads = [];					# Die gesammelten Werbungen
	protected $warnings = [];				# Die entstandenen Warnmeldungen
	protected $errors = [];					# Die entstandenen Fehlermeldungen
	protected $addedHosts = [];				# 
	# Daten über die Abfrage
	protected $ip;							# Die IP des Nutzers
	protected $language;					# 
	protected $agent;						# Ein Agent - unter anderem zur Browser-, Sprach- und Mobilgeräteerkennung
	# Konfigurationseinstellungen:
	protected $sumaFile;					# Die Suma-Datei je nach Sprache
	protected $mobile;						# Nutzer auf Mobilgerät
	protected $resultCount;					# Gewünschte Anzahl Ergebnisse pro Seite
	protected $sprueche;					# Sprüche ein-/ausgeschaltet
	protected $domainsBlacklisted = [];		#
	protected $urlsBlacklisted = [];		#
	protected $url;							#
	protected $languageDetect;				#

	# Erstellt einen noch leeren MetaGer. Dabei werden erst einmal nur die Blacklists für Domains und URLs geladen und die Spracherkennung festgelegt.
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

	# Ruft für jede Engine eine Ranking-Funktion auf. Diese ruft dann wiederum eine Ranking-Funktion für jedes Ergebnis auf, mit der sich die Ergebnisse selbst ranken.
	public function rankAll ()
	{
		foreach( $this->engines as $engine )
		{
			$engine->rank($this);
		}
	}

	# Ruft abhängig vom Suchfokus und vom Ausgabetyp verschiedene Views auf, mit denen die Suchergebnisse dargestellt werden.
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

		# Falls der Suchfokus Bilder ist.
		if( $this->fokus === "bilder" ) {
			switch ($this->out) {
				case 'results':
					return view('metager3bilderresults')
						->with('results', $viewResults)
						->with('eingabe', $this->eingabe)
						->with('mobile', $this->mobile)
						->with('warnings', $this->warnings)
						->with('errors', $this->errors)
						->with('metager', $this);
                        ->with('browser', (new Agent())->browser());
					break;
				default:
					return view('metager3bilder')
						->with('results', $viewResults)
						->with('eingabe', $this->eingabe)
						->with('mobile', $this->mobile)
						->with('warnings', $this->warnings)
						->with('errors', $this->errors)
						->with('metager', $this);
                        ->with('browser', (new Agent())->browser());
					break;
			}
		# Falls der Suchfokus nicht Bilder ist.
		}else 
		{
			switch ($this->out) {
				case 'results':
					return view('metager3results')
						->with('results', $viewResults)
						->with('eingabe', $this->eingabe)
						->with('mobile', $this->mobile)
						->with('warnings', $this->warnings)
						->with('errors', $this->errors)
						->with('metager', $this);
                    ->with('browser', (new Agent())->browser());
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
                    ->with('browser', (new Agent())->browser());
					break;
				default:
					return view('metager3')
						->with('eingabe', $this->eingabe)
						->with('mobile', $this->mobile)
						->with('warnings', $this->warnings)
						->with('errors', $this->errors)
						->with('metager', $this);
                    ->with('browser', (new Agent())->browser());
					break;
			}
		}
	}

	# Erstellt für die aktuelle Suche einen Log auf dem Redis-Server
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

	# Entfernt alle nicht gültigen Ergebnisse aus der Ergebnisliste
	public function removeInvalids ()
	{
		$results = [];
		foreach($this->results as $result)
		{
			if($result->isValid($this))
				$results[] = $result;
		}
	}

	# 
	public function combineResults ()
	{
		# sammelt die gültigen Ergebnisse und ads aller Suchmaschinen in der results-Liste
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

		# Sortiert die Ergebnisse nach Ranking
		uasort($this->results, function($a, $b){
			if($a->getRank() == $b->getRank())
				return 0;
			return ($a->getRank() < $b->getRank()) ? 1 : -1;
		});

		# Überprüft alle Ergebnisse auf Gültigkeit
		# Kann das weg? Wird oben auch gemacht ???
		$newResults = [];
		foreach($this->results as $result)
		{
			if($result->isValid($this))
				$newResults[] = $result;
		}
		$this->results = $newResults;

		# Teilt den Ergebnisse nach Ranking ihre Position sowie eine Farbe zu. Die Farbe gibt auskunft, wie viel "schlechter" das Ergebnis im Vergleich zum besten Ergebnis ist.
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

		# Liest die derzeitige Seite und den zugehörigen Offset aus der URL: ...&page=6
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		$offset = $currentPage-1;

		# Erstellt eine neue Laravel-Collection aus der Ergebnisliste
		$collection = new Collection($this->results);

		# Legt die angezeigten Ergebnisse pro Seite fest
		$perPage = $this->resultCount;

		# Extrahiert mit Offset und Ergebnisse pro Seite die anzuzeugenden Ergebnisse aus der Ergebnis-Collection
		$currentPageSearchResults = $collection->slice($offset * $perPage, $perPage)->all();

		# Für diese 20 Links folgt nun unsere Boost-Implementation.
		$currentPageSearchResults = $this->parseBoost($currentPageSearchResults);

		# Für diese 20 Links folgt nun unsere Adgoal-Implementation.
		$currentPageSearchResults = $this->parseAdgoal($currentPageSearchResults);

		// Create our paginator and pass it to the view ???
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

		# Wir bieten einen bezahlten API-Zugriff an, bei dem dementsprechend die Werbung ausgeblendet wurde. Aktuell ist es nur die Uni-Mainz. Deshalb überprüfen wir auch nur diese.
		if( isset($this->password) )
		{
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

	# Fügt beim Boost-Partnershop Amazon dem Link unser boost-tag an
	public function parseBoost($results)
	{
	foreach($results as $result)
		{
		if(preg_match('/^(http[s]?\:\/\/)?(www.)?amazon\.de/',$result->anzeigeLink))
		{
			if(preg_match('/\?/',$result->anzeigeLink))
					{
				$result->link .= '&tag=boostmg01-21';
					} else
			{
				$result->link .= '?tag=boostmg01-21';
			}
			$result->partnershop = true;

		}
	}	
	return $results;
	}

	# ???
	public function parseAdgoal($results)
	{
		$publicKey = getenv('adgoal_public');
		$privateKey = getenv('adgoal_private');
		if( $publicKey === FALSE )
		{
			return $results;
		}
		$tldList = "";
		try{
			foreach($results as $result)
			{
				$link = $result->anzeigeLink;
				if(strpos($link, "http") !== 0)
				{
					$link = "http://" . $link;
				}
				$tldList .= parse_url($link, PHP_URL_HOST) . ",";
				$result->tld = parse_url($link, PHP_URL_HOST);
			}
			$tldList = rtrim($tldList, ",");

			# Hashwert
			$hash = md5("meta" . $publicKey . $tldList . "GER");

			# Query 
			$query = urlencode($this->q);

			$link = "https://api.smartredirect.de/api_v2/CheckForAffiliateUniversalsearchMetager.php?p=" . $publicKey . "&k=" . $hash . "&tld=" . $tldList . "&q=" . $query; 
			$answer = json_decode(file_get_contents($link));


			# Nun müssen wir nur noch die Links für die Advertiser ändern:
			foreach($answer as $el)
			{
				$hoster = $el[0];
				$hash = $el[1];

				foreach($results as $result)
				{
					if( $hoster === $result->tld )
					{
						# Hier ist ein Advertiser:
						# Das Logo hinzufügen:
						$result->image = "https://img.smartredirect.de/logos_v2/120x60/" . $hash . ".gif";
						# Den Link hinzufügen:
						$publicKey = $publicKey;
						$targetUrl = $result->anzeigeLink;
						if(strpos($targetUrl, "http") !== 0)
							$targetUrl = "http://" . $targetUrl;
                        $gateHash = md5($targetUrl . $privateKey);
                        $newLink = "https://api.smartredirect.de/api_v2/ClickGate.php?p=" . $publicKey . "&k=" . $gateHash . "&url=" . urlencode($targetUrl) . "&q=" . $query;
						$result->link = $newLink;
						$result->partnershop = true;
					}
				}
			}
		}catch(\ErrorException $e)
		{
			return $results;
		}

		return $results;
	}

	# Erstellt aus den Suchparametern des Nutzers die Suchengines.
	# Diese laufen dann über einen Redis-Server. ???
	public function createSearchEngines (Request $request)
	{
		# Keine Suchwörter eingegeben
		if( !$request->has("eingabe") )
			return;

		$xml = simplexml_load_file($this->sumaFile);
		$enabledSearchengines = [];
		$overtureEnabled = FALSE;
		$countSumas = 0;
		$sumas = $xml->xpath("suma");
		
		# Die beiden if-Teile überschneiden sich stark. ??? zusammenführen ???
		# Überprüfe, welche Sumas eingeschaltet sind
		if($this->fokus === "angepasst")
		{
			foreach($sumas as $suma)
			{
				/* Prüft ob die Suma explizit eingeschaltet oder eine Werbe-Suma ist
				*  (Die Suma steht in der Anfrage) oder 
				*  (	(Der Suchfokus ist nicht Bilder) und 
				* 		(	(Es ist Qualigo) oder 
				* 			(Es ist Similar Product Ads) oder 
				* 			(	(Overture ist nicht eingeschaltet) und 
				* 				(Es ist Overture Ads)
				* 			)
				* 		)
				* 	)
				*/
				if($request->has($suma["name"]) 
					|| ( $this->fokus !== "bilder" 
						&& ($suma["name"]->__toString() === "qualigo" 
							|| $suma["name"]->__toString() === "similar_product_ads" 
							|| ( !$overtureEnabled && $suma["name"]->__toString() === "overtureAds" )
							)
						)
					){

					# In eine if-Abfrage ???
					# Prüft ob die Suma ausgeschaltet ist ???
					if(!(isset($suma['disabled']) && $suma['disabled']->__toString() === "1"))
					{
						# Es ist Overture oder Overture Ads
						if($suma["name"]->__toString() === "overture" || $suma["name"]->__toString() === "overtureAds")
						{
							$overtureEnabled = TRUE;
						}

						# Es ist nicht Qualigo, Similar Product Ads oder Overture Ads
						if( $suma["name"]->__toString() !== "qualigo" && $suma["name"]->__toString() !== "similar_product_ads" && $suma["name"]->__toString() !== "overtureAds" )
							$countSumas += 1;
						$enabledSearchengines[] = $suma;
						# Soll das mit in das if ???
					}
				}
			}
		}else
		{
			foreach($sumas as $suma){
				$types = explode(",",$suma["type"]);
				/* Prüft ob die Suma zum eingeschalteten Fokus passt oder eine Werbe-Suma ist
				*  (Die Suma hat einen zum Fokus passenden Typ) oder 
				*  (	(Der Suchfokus ist nicht Bilder) und 
				* 		(	(Es ist Qualigo) oder 
				* 			(Es ist Similar Product Ads) oder 
				* 			(	(Overture ist nicht eingeschaltet) und 
				* 				(Es ist Overture Ads)
				* 			)
				* 		)
				* 	)
				*/
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
						# Es ist Overture oder Overture Ads
						if($suma["name"]->__toString() === "overture" || $suma["name"]->__toString() === "overtureAds")
						{
							$overtureEnabled = TRUE;
						}

						# Es ist nicht Qualigo, Similar Product Ads oder Overture Ads
						if( $suma["name"]->__toString() !== "qualigo" && $suma["name"]->__toString() !== "similar_product_ads" && $suma["name"]->__toString() !== "overtureAds" )
							$countSumas += 1;
						$enabledSearchengines[] = $suma;
						# Soll das mit in das if ???
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

		# Falls keine passenden Sumas gefunden wurden.
		if( $countSumas <= 0 )
		{
			$this->errors[] = "Achtung: Sie haben in ihren Einstellungen keine Suchmaschine ausgewählt.";
		}

		# Wenn eine Sitesearch durchgeführt werden soll, überprüfen wir ob eine der Suchmaschinen überhaupt eine Sitesearch unterstützt:
		$siteSearchFailed = false;
		if( strlen($this->site) > 0 )
		{
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
				$this->warnings[] = "Sie führen eine Sitesearch durch. Es werden nur Ergebnisse von der Seite: <a href=\"http://" . $this->site . "\" target=\"_blank\">\"" . $this->site . "\"</a> angezeigt.";
			}

		}

		$typeslist = [];
		$counter = 0;

		# Erstellung der Suchengines
		
		$engines = [];
		foreach($enabledSearchengines as $engine){
			# Überspringt die Suchengine, falls eine Sitesearch aktiv ist, aber die Engine keine Sitesearch unterstützt.
			if( !$siteSearchFailed && strlen($this->site) > 0 && ( !isset($engine['hasSiteSearch']) || $engine['hasSiteSearch']->__toString() === "0")  )
			{
				
				continue;
			}

			# Überspringt die Suchengine, falls für sie kein Parserskript existiert.
			$path = "App\Models\parserSkripte\\" . ucfirst($engine["package"]->__toString());
			if( !file_exists(app_path() . "/Models/parserSkripte/" . ucfirst($engine["package"]->__toString()) . ".php"))
			{
				Log::error("Konnte " . $engine["name"] . " nicht abfragen, da kein Parser existiert");
				continue;
			}

			# Startet den Timer für die Erstellzeit ??? und versucht die Suchengine zu erstellen
			$time = microtime();

			try
			{
				$tmp = new $path($engine, $this);
			} catch( \ErrorException $e)
			{
				Log::error("Konnte " . $engine["name"] . " nicht abfragen." . var_dump($e));
				continue;
			}

			# isEnabled() ???
			if($tmp->enabled && isset($this->debug))
			{
				$this->warnings[] = $tmp->service . "   Connection_Time: " . $tmp->connection_time . "	Write_Time: " . $tmp->write_time . " Insgesamt:" . ((microtime()-$time)/1000);
			}

			# Trägt die Engine und den genutzten Socket in entsprechende Listen ein ???
			if($tmp->isEnabled())
			{
				$engines[] = $tmp;
				$this->sockets[$tmp->name] = $tmp->fp;
			}

		}

		# Automatische Einstellung des Tabs auf der Ergebnisseite

		# Jetzt werden noch alle Kategorien der Settings durchgegangen und die jeweils enthaltenen Namen der Suchmaschinen gespeichert.
		$foki = [];
		foreach($sumas as $suma)
		{
			if( (!isset($suma['disabled']) || $suma['disabled'] === "") && ( !isset($suma['userSelectable']) || $suma['userSelectable']->__toString() === "1") )
			{
				if( isset($suma['type']) )
				{
					$f = explode(",", $suma['type']->__toString());
					foreach($f as $tmp)
					{
						$name = $suma['name']->__toString();
						$foki[$tmp][$suma['name']->__toString()] = $name;
					}
				}else
				{
					$name = $suma['name']->__toString();
					$foki["andere"][$suma['name']->__toString()] = $name;
				}
			}
		}

		# Es werden auch die Namen der aktuell aktiven Suchmaschinen abgespeichert.
		$realEngNames = [];
		foreach($enabledSearchengines as $realEng) {
			$nam = $realEng["name"]->__toString();
			if($nam !== "qualigo" && $nam !== "overtureAds") {
				$realEngNames[] = $nam;
			}
		}

		# Anschließend werden diese beiden Listen verglichen (jeweils eine der Fokuslisten für jeden Fokus), um herauszufinden ob sie vielleicht identisch sind. Ist dies der Fall, so hat der Nutzer anscheinend Suchmaschinen eines kompletten Fokus eingestellt. Der Fokus wird dementsprechend angepasst.
		foreach($foki as $fok => $engs) {
			$isFokus = true;
			$fokiEngNames = [];
			foreach($engs as $eng) {
				$fokiEngNames[] = $eng;
			}
			foreach($fokiEngNames as $fen) {
				if(!in_array($fen, $realEngNames)) {
					$isFokus = false;
				}
			}
			foreach($realEngNames as $ren) {
				if(!in_array($ren, $fokiEngNames)) {
					$isFokus = false;
				}
			}
			if($isFokus) {
				$this->fokus = $fok;
			}
		}

		# Suchvorgang

		/* Nun passiert ein elementarer Schritt.
		*  Wir warten auf die Antwort der Suchmaschinen, 
		*  da wir vorher nicht weiter machen können, 
		*  aber natürlich nicht ewig.
		*  Die Verbindung steht zu diesem Zeitpunkt und auch unsere Request wurde schon gesendet.
		*  Wir geben der Suchmaschine nun bis zu 500ms Zeit zu antworten.
		*/
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


			/* Prüft abhängig von der bisher verstrichenen Zeit 
			*  ob schon genug Ergebnisse vorliegen, um mit Suchen aufzuhören:
			*  unter 0,5s 					: 
					(	(keine Suchmaschinen laden mehr) oder 
						(die Anzahl der zu ladenden Ergebnisse ist erreicht)
					) und (es darf abgebrochen werden)
			*  0,5s bis maximale Suchzeit 	: 
					(	(keine Suchmaschinen laden mehr) oder 
						(mindestens 80% der Suchmaschinen sind geladen)
					) und (es darf abgebrochen werden)
			*  über maximaler Suchzeit 		: 
					unbedingter Abbruch
			*/
			# canBreak bei enginesToLoad === 0 ???
			if($time < 500)
			{
				if( ($enginesToLoad === 0 || $loadedEngines >= $enginesToLoad) && $canBreak)
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

		# Versucht alle Ergebnisse auszulesen
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
		
		# und verwirft den Rest.
		foreach( $engines as $engine )
		{
			if( !$engine->loaded )
				$engine->shutdown();
		}

		$this->engines = $engines;
	}

	/** 
	*  Liest aus einer vom Nutzer abgesendete Anfrage die Suchparameter aus. Dazu gehören:
	*  
	*  url 				-	???
	*  fokus			-	Der gewählte Suchfokus
	*  sumaFile			-	Die Suma-Datei je nach Sprache
	*  eingabe			-	Die eingegebenen Suchbegriffe
	*  q				-	Erst einmal gleich wie eingabe
	*  ip				-	Die IP des Nutzers ??? anonymisiert ???
	*  language			-	???
	*  category			-	???
	*  time				-	Die maximale Suchzeit
	*  page				-	Die gewählte Ergebnisseite
	*  lang				-	Die gewählte Sprache der Suchergebnisse
	*  agent			-	Ein Agent - unter anderem zur Browser-, Sprach- und Mobilgeräteerkennung
	*  mobile			-	Nutzer auf Mobilgerät
	*  sprueche			-	Sprüche ein-/ausgeschaltet
	*  resultCount		-	Gewünschte Anzahl Ergebnisse pro Seite
	*  tab				-	gewählter Ergebnistab
	*  password			-	???
	*  quicktips		-	Quicktips ein-/ausgeschaltet
	*  out				-	???
	*  request			-	Sicherung der Anfrage
	*/

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
		# Dart-Europe - mehr Suchzeit
		if( $request->has('dart') )
		{
			$this->time = 10000;
			$this->warnings[] = "Hinweis: Sie haben Dart-Europe aktiviert. Die Suche kann deshalb länger dauern und die maximale Suchzeit wurde auf 10 Sekunden hochgesetzt.";
		}

		# Falscher Zeitrahmen - Auf Standardwert setzen
		if( $this->time <= 500 || $this->time > 20000 )
		{
			$this->time = 1000;
		}

		# Minism - ???
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

		# Ebay - extra Suchzeit
		if( $request->has('ebay') )
		{
			$this->time = 2000;
			$this->warnings[] = "Hinweis: Sie haben Ebay aktiviert. Die Suche kann deshalb länger dauern und die maximale Suchzeit wurde auf 2 Sekunden hochgesetzt.";
		}

		# Englisch - keine Sprüche
		if( App::isLocale("en") )
		{
			$this->sprueche = "off";
		}

		# Falsche Anzahl maximale Ergebnisse - Standartwert 1000
		if($this->resultCount <= 0 || $this->resultCount > 200 )
		{
			$this->resultCount = 1000;
		}

		# Onenewspage - Mehr Zeit und Cache
		if( $request->has('onenewspageAll') || $request->has('onenewspageGermanyAll') )
		{
			$this->time = 5000;
			$this->cache = "cache";
		}

		# Den richtigen Tab einstellen
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

		# Password setzen
		if( $request->has('password') )
			$this->password = $request->input('password');

		# Quicktips an-/ausschalten
		if( $request->has('quicktips') )
			$this->quicktips = false;
		else
			$this->quicktips = true;

		# Output-Format festlegen
		$this->out = $request->input('out', "html");
		if($this->out !== "html" && $this->out !== "json" && $this->out !== "results" && $this->out !== "results-with-style")
			$this->out = "html";

		# Request abspeichern
		$this->request = $request;
	}

	# Liest aus der Sucheingabe bestimmte Spezialsuchen aus
	# Arbeitet auf der Variable q
	public function checkSpecialSearches (Request $request)
	{
		# Site Search:
		# Wenn die Suchanfrage um das Schlüsselwort "site:*" ergänzt ist, sollen Ergebnisse nur von einer bestimmten Seite stammen.
		if(preg_match("/(.*)\bsite:(\S+)(.*)/si", $this->q, $match))
		{
			$this->site = $match[2];
			$this->q = $match[1] . $match[3];
		}
		if( $request->has('site') )
		{
			$this->site = $request->input('site');
		}

		# Host-Blacklisting:
		# Wenn die Suchanfrage um das Schlüsselwort "-host:*" ergänzt ist, sollen bestimmte Hosts nicht eingeblendet werden.
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

		# Domain-Blacklisting:
		# Wenn die Suchanfrage um das Schlüsselwort "-domain:*" ergänzt ist, sollen bestimmte Domains nicht eingeblendet werden.
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
		
		# Stopwords:
		# Alle mit "-" gepräfixten Worte sollen aus der Suche ausgeschlossen werden.
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

		# Phrasensuche:
		# Alle mit " umschlossenen Worte gelten als Phrasensuche und sollen in genau dieser Reihenfolge im Suchergebnis vorkommen.
		$p = "";
		$tmp = $this->q;
		while(preg_match("/(.*)\"(.+)\"(.*)/si", $tmp, $match)){
			$tmp = $match[1] . $match[3];
			$this->phrases[] = strtolower($match[2]);
		}
		foreach($this->phrases as $phrase)
		{
			$p .= "\"$phrase\", ";
		}
		$p = rtrim($p, ", ");
		if(sizeof($this->phrases) > 0)
			$this->warnings[] = "Sie führen eine Phrasensuche durch: $p";
	}

	# Hilfsmethoden

	# Adder

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

	public function addLink($link)
	{
		if(strpos($link, "http://") === 0)
			$link = substr($link, 7);
		if(strpos($link, "https://") === 0)
			$link = substr($link, 8);
		if(strpos($link, "www.") === 0)
			$link = substr($link, 4);
		$link = trim($link, "/");
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

	# Generators

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

	# Popper

	public function popAd()
	{
		if(count($this->ads) > 0)
			return get_object_vars(array_shift($this->ads));
		else
			return null;
	}

	# Getter

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

	public function getPhrases ()
	{
		return $this->phrases;
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

	public function getTab ()
	{
		return $this->tab;
	}

	public function getResults ()
	{
		return $this->results;
	}

	public function getImageProxyLink($link)
	{
		$requestData = [];
		$requestData["url"] = $link;
		$link = action('Pictureproxy@get', $requestData);
		return $link;
	}

	public function getSite()
	{
		return $this->site;
	}

	public function getHashCode ()
	{
		$string = url()->full();
		return md5($string);
	}

	# Shower

	public function showQuicktips ()
	{
		return $this->quicktips;
	}
}

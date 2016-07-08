<?php

namespace App\Models;



class Result
{
	
	function __construct ( \SimpleXMLElement $provider, $titel, $link, $anzeigeLink , $descr, $gefVon, $sourceRank, $partnershop = false, $image = "" )
	{
		$this->titel = strip_tags(trim($titel));
		$this->link = trim($link);
		$this->anzeigeLink = trim($anzeigeLink);
		$this->descr = strip_tags(trim($descr));
		$this->descr = preg_replace("/\n+/si", " ", $this->descr);
		if( strlen($this->descr) > 250 )
		{
			$this->descr = wordwrap($this->descr, 250);
			$this->descr = substr($this->descr, 0, strpos($this->descr, "\n"));

		}
		$this->gefVon = trim($gefVon);
		$this->proxyLink = $this->generateProxyLink($this->link);
		$this->sourceRank = $sourceRank;
		if($this->sourceRank <= 0 || $this->sourceRank > 20)
			$this->sourceRank = 20;
		$this->sourceRank = 20 - $this->sourceRank;
		if(isset($provider["engineBoost"]))
		{
			$this->engineBoost = $provider["engineBoost"];
		}else
		{
			$this->engineBoost = 1;
		}

		$this->valid = true;
		$this->host = @parse_url($link, PHP_URL_HOST);
		$this->strippedHost = $this->getStrippedHost($this->anzeigeLink);
		$this->strippedDomain = $this->getStrippedDomain($this->strippedHost);
		$this->strippedLink = $this->getStrippedLink($this->anzeigeLink);
		$this->rank = 0;
		$this->partnershop = $partnershop;
		$this->image = $image;

		#die($this->anzeigeLink . "\r\n" . $this->strippedHost);
	}

	public function rank (\App\MetaGer $metager)
	{

		$rank = 0;
		$rank += ($this->sourceRank * 0.02);

		#URL-Boost
		$link = $this->anzeigeLink;
		if(strpos($link, "http") !== 0)
		{
			$link = "http://" . $link;
		}
		$link = @parse_url($link, PHP_URL_HOST) . @parse_url($link, PHP_URL_PATH);
		$tmpLi = $link;
		$tmpEingabe = $metager->getQ();
		$count = 0;
		$tmpLink = "";

		$regex = [
			"/\s+/si",
			"/http:/si",
			"/https:/si",
			"/www\./si",
			"/\//si",
			"/\./si",
			"/-/si"
			];
		foreach($regex as $reg)
		{
			$link = preg_replace($regex, "", $link);
			$tmpEingabe = preg_replace($regex, "", $tmpEingabe);
		}
		#die($tmpLi . "<br>" . $link . "<br>" . $tmpEingabe . "<br><br>");
		foreach(str_split($tmpEingabe) as $char)
		{
			if( !$char || !$tmpEingabe || strlen($tmpEingabe) === 0 || strlen($char) === 0 )
				continue;
			if(strpos(strtolower($tmpLink), strtolower($char)) >= 0)
			{
				$count++;
				$tmpLink = str_replace(urlencode($char), "", $tmpLink);
			}
			if(strlen($this->descr) > 80 && strlen($link) > 0)
			{
				$rank += $count /((strlen($link)) * 60);
			}
		}

		# Boost für Vorkommen der Suchwörter:
		$maxRank = 0.1;
		$tmpTitle = $this->titel;
		$tmpDescription = $this->descr;
		$isWithin = false;
		$tmpRank = 0;
		$tmpEingabe = $metager->getQ();
		$tmpEingabe = preg_replace("/\b\w{1,3}\b/si", "", $tmpEingabe);
		$tmpEingabe = preg_replace("/\s+/si", " ", $tmpEingabe);
		#die($tmpEingabe);
		foreach(explode(" ", trim($tmpEingabe)) as $el)
		{
			$el = preg_quote($el, "/");
			if(strlen($tmpTitle) > 0)
			{
				if(preg_match("/\b$el\b/si", $tmpTitle))
				{
					$tmpRank += .7 * .6 * $maxRank;
				}elseif (strpos($tmpTitle, $el) !== false) {
					$tmpRank += .3 * .6 * $maxRank;
				}
			}
			if( strlen($tmpDescription) > 0 )
			{
				if(preg_match("/\b$el\b/si", $tmpDescription))
				{
					$tmpRank += .7 * .4 * $maxRank;
				}elseif (strpos($tmpDescription, $el) !== false) {
					$tmpRank += .3 * .4 * $maxRank;
				}
			}
		}
		$tmpRank /= sizeof(explode(" ", trim($tmpEingabe))) * 10;
		$rank += $tmpRank;

		if($this->engineBoost > 0)
		{
			$rank *= floatval($this->engineBoost);
		}

		$this->rank = $rank;
	}

	public function getRank ()
	{
		return $this->rank;
	}

	public function isValid (\App\MetaGer $metager)
	{
		# Zunächst die persönlich ( über URL-Parameter ) definierten Blacklists:
		if(in_array($this->strippedHost, $metager->getUserHostBlacklist())
			|| in_array($this->strippedDomain, $metager->getUserDomainBlacklist()))
			return false;
		
		# Jetzt unsere URL und Domain Blacklist
		if($this->strippedHost !== "" && (in_array($this->strippedHost, $metager->getDomainBlacklist()) || in_array($this->strippedLink, $metager->getUrlBlacklist())))
		{
			return false;
		}

		$text = $this->titel . " " . $this->descr;

		if($metager->getLang() !== "all")
		{
			$result = $metager->getLanguageDetect()->detect($text, 1);
			$lang = "";
			foreach($result as $key => $value)
			{
				$lang = $key;
			}

			if($lang !== "" && $lang !== $metager->getLang())
				return false;
		}

		# Wir wenden die Stoppwortsuche an und schmeißen entsprechende Ergebnisse raus:
		foreach($metager->getStopWords() as $stopWord)
		{
			if(stripos($text, $stopWord) !== false)
			{
				return false;
			}
		}

		# Abschließend noch 2 Überprüfungen. Einmal den Host filter, der Sicherstellt, dass von jedem Host maximal 3 Links angezeigt werden
		# und dann noch den Dublettefilter, der sicher stellt, dass wir nach Möglichkeit keinen Link doppelt in der Ergebnisliste haben
		# Diese Überprüfung führen wir unter bestimmten Bedingungen nicht durch:
		if($metager->getSite() === "" &&
			strpos($this->strippedHost, "ncbi.nlm.nih.gov") === false &&
			strpos($this->strippedHost, "twitter.com") === false &&
			strpos($this->strippedHost, "www.ladenpreis.net") === false &&
			strpos($this->strippedHost, "ncbi.nlm.nih.gov") === false &&
			strpos($this->strippedHost, "www.onenewspage.com") === false)
		{
			$count = $metager->getHostCount($this->strippedHost);
			if($count >= 3)
			{
				return false;
			}
		}

		# Unabhängig davon unser Dublettenfilter:
		if($metager->addLink($this->strippedLink))
		{
			$metager->addHostCount($this->strippedHost);
			return true;
		}else
		{
			return false;
		}
	}

	private function getStrippedHost ($link)
	{
		if(strpos($link, "http") !== 0)
			$link = "http://" . $link;
		$link = @parse_url($link, PHP_URL_HOST);
		$link = preg_replace("/^www\./si", "", $link);
		return $link;
	}
	private function getStrippedLink ($link)
	{
		if(strpos($link, "http") !== 0)
			$link = "http://" . $link;
		$host = $this->strippedHost;
		$path = @parse_url($link , PHP_URL_PATH);
		return $host . $path;
	}

	private function getStrippedDomain ($link)
	{
		if(preg_match("/([^\.]*\.[^\.]*)$/si", $link, $match))
		{
			return $match[1];
		}else
		{
			return $link;
		}		
	}

	private function generateProxyLink ($link)
	{
		if(!$link)
			return "";
		$tmp = $link;
		$tmp = preg_replace("/\r?\n$/s", "", $tmp);
		$tmp = preg_replace("#^([\w+.-]+)://#s", "$1/", $tmp);
		return "https://proxy.suma-ev.de/cgi-bin/nph-proxy.cgi/en/I0/" . $tmp;
		
	}
}
<?php

namespace App\Models;

use App\Jobs\Search;
use App\MetaGer;
use Cache;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Log;
use Redis;

abstract class Searchengine
{
    use DispatchesJobs;

    protected $ch; # Curl Handle zum erhalten der Ergebnisse
    public $fp;
    protected $getString = "";
    protected $engine;
    protected $counter      = 0;
    protected $socketNumber = null;
    public $enabled         = true;
    public $results         = [];
    public $ads             = [];
    public $write_time      = 0;
    public $connection_time = 0;
    public $loaded          = false;
    public $cached          = false;

    public function __construct(\SimpleXMLElement $engine, MetaGer $metager)
    {
        foreach ($engine->attributes() as $key => $value) {
            $this->$key = $value->__toString();
        }
        if (!isset($this->homepage)) {
            $this->homepage = "https://metager.de";
        }

        $this->engine = $engine;

        if (!isset($this->cacheDuration)) {
            $this->cacheDuration = 60;
        }

        # Wir registrieren die Benutzung dieser Suchmaschine
        $this->uses = intval(Redis::hget($this->name, "uses")) + 1;
        Redis::hset($this->name, "uses", $this->uses);

        # Eine Suchmaschine kann automatisch temporär deaktiviert werden, wenn es Verbindungsprobleme gab:
        if (isset($this->disabled) && strtotime($this->disabled) <= time()) {
            # In diesem Fall ist der Timeout der Suchmaschine abgelaufen.
            $this->enable($metager->getSumaFile(), "Die Suchmaschine " . $this->name . " wurde wieder eingeschaltet.");
        } elseif (isset($this->disabled) && strtotime($this->disabled) > time()) {
            $this->enabled = false;
            return;
        }

        # User-Agent definieren:
        $this->useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1";
        $this->ip        = $metager->getIp();
        $this->gefVon    = "<a href=\"" . $this->homepage . "\" target=\"_blank\">" . $this->displayName . "</a>";
        $this->startTime = microtime();

        $q = "";
        if (isset($this->hasSiteSearch) && $this->hasSiteSearch === "1") {
            if (strlen($metager->getSite()) === 0) {
                $q = $metager->getQ();
            } else {
                $q = $metager->getQ() . " site:" . $metager->getSite();
            }

        } else {
            $q = $metager->getQ();
        }
        $this->getString  = $this->generateGetString($q, $metager->getUrl(), $metager->getLanguage(), $metager->getCategory());
        $this->hash       = md5($this->host . $this->getString . $this->port . $this->name);
        $this->resultHash = $metager->getHashCode();
        if (Cache::has($this->hash)) {
            $this->cached = true;
            $this->retrieveResults();
        } else {
            # Die Anfragen an die Suchmaschinen werden nun von der Laravel-Queue bearbeitet:
            # Hinweis: solange in der .env der QUEUE_DRIVER auf "sync" gestellt ist, werden die Abfragen
            # nacheinander abgeschickt.
            # Sollen diese Parallel verarbeitet werden, muss ein anderer QUEUE_DRIVER verwendet werden.
            # siehe auch: https://laravel.com/docs/5.2/queues
            $this->dispatch(new Search($this->resultHash, $this->host, $this->port, $this->name, $this->getString, $this->useragent, $metager->getSumaFile()));
        }
    }

    abstract public function loadResults($result);

    public function rank(\App\MetaGer $metager)
    {
        foreach ($this->results as $result) {
            $result->rank($metager);
        }
    }

    private function setStatistic($key, $val)
    {

        $oldVal = floatval(Redis::hget($this->name, $key)) * $this->uses;
        $newVal = ($oldVal + max($val, 0)) / $this->uses;
        Redis::hset($this->name, $key, $newVal);
        $this->$key = $newVal;
    }

    public function enable($sumaFile, $message)
    {
        Log::info($message);
        $xml = simplexml_load_file($sumaFile);
        unset($xml->xpath("//sumas/suma[@name='" . $this->name . "']")['0']['disabled']);
        $xml->saveXML($sumaFile);
    }

    public function closeFp()
    {
        fclose($this->fp);
    }

    public function getSocket()
    {
        $number = Redis::hget('search.' . $this->hash, $this->name);
        if ($number === null) {
            die("test");
            return null;
        } else {
            return pfsockopen($this->getHost() . ":" . $this->port . "/$number", $this->port, $errstr, $errno, 1);
        }
    }

    public function retrieveResults()
    {
        if ($this->loaded) {
            return true;
        }

        $body = "";
        if ($this->cacheDuration > 0 && Cache::has($this->hash)) {
            $body = Cache::get($this->hash);
        } elseif (Redis::hexists('search.' . $this->resultHash, $this->name)) {
            $body = Redis::hget('search.' . $this->resultHash, $this->name);
            if ($this->cacheDuration > 0) {
                Cache::put($this->hash, $body, $this->cacheDuration);
            }

        }

        if ($body !== "") {
            $this->loadResults($body);
            $this->loaded = true;
            Redis::hdel('search.' . $this->hash, $this->name);
            return true;
        } else {
            return false;
        }
    }

    public function shutdown()
    {
        Redis::del($this->host . "." . $this->socketNumber);
    }

    protected function getHost()
    {
        $return = "";
        if ($this->port === "443") {
            $return .= "tls://";
        } else {
            $return .= "tcp://";
        }
        $return .= $this->host;
        return $return;
    }

    public function getCurlInfo()
    {
        return curl_getinfo($this->ch);
    }

    public function getCurlErrors()
    {
        return curl_errno($this->ch);
    }

    public function addCurlHandle($mh)
    {
        curl_multi_add_handle($mh, $this->ch);
    }

    public function removeCurlHandle($mh)
    {
        curl_multi_remove_handle($mh, $this->ch);
    }

    private function generateGetString($query, $url, $language, $category)
    {
        $getString = "";

        # Skript:
        if (strlen($this->skript) > 0) {
            $getString .= $this->skript;
        } else {
            $getString .= "/";
        }

        # FormData:
        if (strlen($this->formData) > 0) {
            $getString .= "?" . $this->formData;
        }

        # Wir müssen noch einige Platzhalter in dem GET-String ersetzen:
        if (strpos($getString, "<<USERAGENT>>")) {
            $getString = str_replace("<<USERAGENT>>", $this->urlEncode($this->useragent), $getString);
        }

        if (strpos($getString, "<<QUERY>>")) {
            $getString = str_replace("<<QUERY>>", $this->urlEncode($query), $getString);
        }

        if (strpos($getString, "<<IP>>")) {
            $getString = str_replace("<<IP>>", $this->urlEncode($this->ip), $getString);
        }

        if (strpos($getString, "<<LANGUAGE>>")) {
            $getString = str_replace("<<LANGUAGE>>", $this->urlEncode($language), $getString);
        }

        if (strpos($getString, "<<CATEGORY>>")) {
            $getString = str_replace("<<CATEGORY>>", $this->urlEncode($category), $getString);
        }

        if (strpos($getString, "<<AFFILDATA>>")) {
            $getString = str_replace("<<AFFILDATA>>", $this->getOvertureAffilData($url), $getString);
        }
        return $getString;
    }

    protected function urlEncode($string)
    {
        if (isset($this->inputEncoding)) {
            return urlencode(mb_convert_encoding($string, $this->inputEncoding));
        } else {
            return urlencode($string);
        }
    }

    private function getOvertureAffilData($url)
    {
        $affil_data = 'ip=' . $this->ip;
        $affil_data .= '&ua=' . $this->useragent;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $affil_data .= '&xfip=' . $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        $affilDataValue = $this->urlEncode($affil_data);
        # Wir benötigen die ServeUrl:
        $serveUrl = $this->urlEncode($url);

        return "&affilData=" . $affilDataValue . "&serveUrl=" . $serveUrl;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }
}

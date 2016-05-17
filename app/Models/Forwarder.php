<?php

namespace App\MetaGer;

use App\lib\Redis;

class Forwarder 
{
	public static function getFreeServer()
	{
		# Variablen Deklarationen:
        $host = $_SERVER["SERVER_NAME"]; # Dieser Server ist der Host für den Redis-Server
        $redis;                      # In diesem Objekt erhalten wir Zugriff auf die Redis-Datenbank (Redis)
        $cfg;                        # In diesem Objekt erhalten wir Zugriff auf unsere Konfiguration (Config::Simple)
        $servers;                    # Beinhaltet später eine Liste, aller verbundenen Server, an die wir die Anfrage schicken können
        $server;
        $protocol;        # Beinhaltet später den Server ( und sein Protokoll ), der die Suchanfrage tatsächlich beantworten soll
        $result;

        # Load Config File;
        $cfg = parse_ini_file(config_path() . '/metager.ini', TRUE);

        # Connect to our Redis Server
        try {
            $redis = new Redis($host, $cfg['redis']['port']);
        } catch(\Exception $e){
            $redis = new Redis($cfg['redis']['server'], $cfg['redis']['port']);
        }
        $response = $redis->cmd('auth', $cfg['redis']['password'] )->get();
        if($response !== "OK"){
           die("Couldn't authenticate to Redis Server");
        }

        # Fill $servers Array from Redis:
        $serversArray = self::indexedToAssociative($redis->cmd('hgetall', 'servers')->get());

        foreach($serversArray as $key => $value ){
        	$data = explode("\t", $value);
        	$age = time() - $data[0];	# Erste Stelle ist die Zeit in Sekunden, an der sich der Server das letzte Mal angemeldet hat.
        	if($age > 3){
        		# Der Server hat sich seit 3 Sekunden nicht mehr gemeldet. Er kommt schon einmal nicht in Frage und wird aus der Datenbank geworfen:
				$redis->cmd("hdel", "servers", $key)->set();
        	}elseif()
        }

        return time();
        # Select a matching Server:
	}

	/**
     * Converts an indexed Array to an Associative one
     * Every Element on an even index is the Ḱey to the Element on the next uneven index
     *
     * @param  indexedArray  $array
     * @return associativeArray $result
     */
	private static function indexedToAssociative($array){
		$result = array();
        for($i = 0; $i < sizeof($array); $i++){
            if($i %2 === 0){
                $server = $array[$i];
            }else{
                $result[$server] = $array[$i];
                $server = "";
            }
        }
        return $result;
	}
}
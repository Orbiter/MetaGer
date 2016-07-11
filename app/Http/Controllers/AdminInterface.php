<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Redis;

class AdminInterface extends Controller
{
    public function index ()
    {
    	# Zunächst einmal die Redis-Verbindung:
    	$redis = Redis::connection('redisLogs');

    	# Dann lesen wir alle Server aus:
    	$member = $redis->smembers('logs.worker');

    	# Jetzt besorgen wir uns die Daten für jeden Server:
    	$data = [];
    	foreach( $member as $mem )
    	{
    		$tmp = $redis->hgetall('logs.worker.' . $mem);
    		$data[$mem] = $tmp;
    	}
    	return view('admin')
    		->with('data', $data)
    		->with('title', "Admin-Interface-MetaGer");
    }
}

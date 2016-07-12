<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Redis;

class AdminInterface extends Controller
{
    public function index (Request $request)
    {
        $time = $request->input('time', 60);

    	# Zunächst einmal die Redis-Verbindung:
    	$redis = Redis::connection('redisLogs');

    	# Dann lesen wir alle Server aus:
    	$member = $redis->smembers('logs.worker');
        $today = strtotime(date(DATE_RFC822, mktime(0,0,0, date("m"), date("d"), date("Y"))));
        $beginningTime = strtotime(date(DATE_RFC822, mktime(date("H"),date("i")-$time, date("s"), date("m"), date("d"), date("Y")))) - $today;

    	# Jetzt besorgen wir uns die Daten für jeden Server:
    	$data = [];
    	foreach( $member as $mem )
    	{
    		$tmp = $redis->hgetall('logs.worker.' . $mem);
            ksort($tmp, SORT_NUMERIC);
            $tmp2 = [];
            foreach($tmp as $el => $value)
            {
                if($el >= $beginningTime)
                    $data[$mem][$el] = $value ;
            }
    	}
        #$data = [ 5 => "majm", 2 => "mngsn", 7 => "akljsd"];
        #arsort($data);
    	return view('admin')
    		->with('data', $data)
    		->with('title', "Admin-Interface-MetaGer")
            ->with('time', $time);
    }
}

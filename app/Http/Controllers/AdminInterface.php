<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Redis;
use Response;

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
    	return view('admin.admin')
    		->with('data', $data)
    		->with('title', "Admin-Interface-MetaGer")
            ->with('time', $time);
    }

    public function count()
    {
        $logToday = "mg3.log";
        if(file_exists("/var/log/metager/".$logToday))
        {
            $logToday = file("/var/log/metager/".$logToday);
        }else
        {
            return redirect('');
        }
        $oldLogs = [];
        $yesterday = 0;
        $rekordTag = 0;
        $rekordTagDate = "";
        for($i = 1; $i <= 28; $i ++ )
        {
            $logDate = "/var/log/metager/archive/" . date("Y-m-d", mktime(date("H"),date("i"), date("s"), date("m"), date("d")-$i, date("Y"))) . "_mg3.log";
            if( file_exists($logDate) )
            {
                $sameTime = exec("grep -n '" . date('H') . ":" . date('i') . ":' $logDate | tail -1 | cut -f1 -d':'");
                $insgesamt = exec("wc -l $logDate | cut -f1 -d' '");
                if($insgesamt > $rekordTag)
                {
                    $rekordTag = $insgesamt;
                    $rekordTagSameTime = $sameTime;
                    $rekordTagDate = date("d.m.Y", mktime(date("H"),date("i"), date("s"), date("m"), date("d")-$i, date("Y")));
                }
                $oldLogs[$i]['sameTime'] = $sameTime;
                $oldLogs[$i]['insgesamt'] = $insgesamt;
            }
        }

        $median = [];
        # Median für 7 Tage:
        $size = 0;
        $count = 0;
        for($i = 1; $i <= 7; $i++)
        {
            if( isset($oldLogs[$i]) )
            {
                $count += $oldLogs[$i]['insgesamt'];
                $size++;
            }
        }
        $median[7] = ($count/$size);

        # Median für 14 Tage:
        $size = 0;
        $count = 0;
        for($i = 1; $i <= 14; $i++)
        {
            if( isset($oldLogs[$i]) )
            {
                $count += $oldLogs[$i]['insgesamt'];
                $size++;
            }
        }
        $median[14] = ($count/$size);

        # Median für 21 Tage:
        $size = 0;
        $count = 0;
        for($i = 1; $i <= 21; $i++)
        {
            if( isset($oldLogs[$i]) )
            {
                $count += $oldLogs[$i]['insgesamt'];
                $size++;
            }
        }
        $median[21] = ($count/$size);

        # Median für 28 Tage:
        $size = 0;
        $count = 0;
        for($i = 1; $i <= 28; $i++)
        {
            if( isset($oldLogs[$i]) )
            {
                $count += $oldLogs[$i]['insgesamt'];
                $size++;
            }
        }
        $median[28] = ($count/$size);
        return view('admin.count')
            ->with('title', 'Suchanfragen - MetaGer')
            ->with('today', number_format(floatval(sizeof($logToday)), 0, ",", "."))
            ->with('oldLogs', $oldLogs)
            ->with('rekordCount', number_format(floatval($rekordTag), 0, ",", "."))
            ->with('rekordTagSameTime', number_format(floatval($rekordTagSameTime), 0, ",", "."))
            ->with('rekordDate', $rekordTagDate)
            ->with('median', $median);
    }
    public function check ()
    {
        $q = "";
        $logFile = "/var/log/metager/mg3.log";
        if( file_exists($logFile) )
        {
            $q = exec("tail -n 1 $logFile");
            $q = substr($q, strpos($q, "search=")+7);
        }
        return view('admin.check')
            ->with('title', 'Wer sucht was? - MetaGer')
            ->with('q', $q);
    }
}

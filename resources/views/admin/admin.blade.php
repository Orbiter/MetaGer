@extends('layouts.subPages')

@section('title', $title )

@section('content')

@foreach( $data as $serverName => $dataPoints )
<div>
<h1>{{ $serverName }}</h1>
<svg width="100%" height="500px">
	<!-- Zunächst die Achsen: -->
	<!-- Y-Achse -->
	<line x1="1%" y1="0" x2="1%" y2="95%" style="stroke:rgb(0,0,0);stroke-width:3" />
	<line x1="1%" y1="0" x2="0" y2="3%" style="stroke:rgb(0,0,0);stroke-width:3" />
	<line x1="1%" y1="0" x2="2%" y2="3%" style="stroke:rgb(0,0,0);stroke-width:3" />

	<!-- Beschriftungen der Y-Achse -->
	@for( $y = ((95-0) / 10); $y < ((95-0) / 10) * 10; $y = $y + ((95-0) / 10) )
	<line x1="0.5%" y1="{{ $y }}%" x2="1.5%" y2="{{ $y }}%" style="stroke:rgb(0,0,0);stroke-width:1" />
	<text x="1.8%" y="{{ $y }}%" fill="black" style="font-size:10px;">{{ 100 - (($y / 95)*100) }}</text>
	@endfor
	<text x="3%" y="2%" fill="red">Anzahl Worker: x</text>

	<!-- X-Achse -->
	<line x1="1%" y1="95%" x2="99%" y2="95%" style="stroke:rgb(0,0,0);stroke-width:3" />
	<line x1="99%" y1="95%" x2="98%" y2="92%" style="stroke:rgb(0,0,0);stroke-width:3" />
	<line x1="99%" y1="95%" x2="98%" y2="98%" style="stroke:rgb(0,0,0);stroke-width:3" />

	<!-- Beschriftungen der X-Achse -->
	<?php
	$last = 0;
	for( $x = (((99-1) / $time) + 1); $x < 98.9; $x = ($x + ((99-1) / $time)) )
	{
		echo '<line x1="'.$x.'%" y1="93%" x2="'.$x.'%" y2="97%" style="stroke:rgb(0,0,0);stroke-width:1" />';
		if( ($x - $last) >= 3)
		{
			echo '<text x="'.($x-1).'%" y="99%" fill="black" style="font-size:8px;">'.date("H:i", mktime(date("H"),date("i")-($time -(($x-1) / 98)*$time), date("s"), date("m"), date("d"), date("Y"))).'</text>';
			$last = $x;
		}
	}
	?>
	<text x="95%" y="90%" fill="red">Zeit (h): y</text>

	<!-- Nun die Datenpunkte: -->
	<?php
		$count = 0;
		$maximum = 0;
		$maximumY = 0;
		foreach($dataPoints as $key => $value)
		{
			if($count > 0)
			{
				$start = strtotime(date(DATE_RFC822, mktime(date("H"),date("i")-$time, date("s"), date("m"), date("d"), date("Y")))) - strtotime(date(DATE_RFC822, mktime(0,0,0, date("m"), date("d"), date("Y"))));
				$lastkey = $oldKey - $start;
				$newkey = $key - $start ;

				$x1 = (($lastkey / ($time*60)) * 98) + 1;
				$x2 = (($newkey / ($time*60)) * 98) + 1;
			
				$y1 = 95 - (($oldVal / 100) * 95);
				$y2 = 95 - (($value / 100) * 95);
				if($value > $maximum)
				{
					$maximum = $value;
					$maximumY = $y2;
				}
				echo '<line x1="'.$x1.'%" y1="'.$y1.'%" x2="'.$x2.'%" y2="'.$y2.'%" style="stroke:rgb(0,0,0);stroke-width:1" />';
			}
			$oldKey = $key;
			$oldVal = $value;
			$count++;
		}
	?>
	<!-- Und noch eine Linie für das Maximum: -->
	<line x1="1%" y1="{{ $maximumY }}%" x2="99%" y2="{{ $maximumY }}%" style="stroke:rgb(255,0,0);stroke-width:1" stroke-dasharray="5,5" d="M5 20 l215 0"/>
</svg>
</div>
@endforeach

@endsection
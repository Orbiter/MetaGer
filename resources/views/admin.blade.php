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
	@for( $x = (((99-1) / 24) + 1); $x < 98.9; $x = ($x + ((99-1) / 24)) )
	<line x1="{{ $x }}%" y1="93%" x2="{{ $x }}%" y2="97%" style="stroke:rgb(0,0,0);stroke-width:1" />
	<text x="{{ $x }}%" y="99%" fill="black" style="font-size:10px;">{{ (($x-1) / 98)*24 }}</text>
	@endfor
	<text x="95%" y="90%" fill="red">Zeit (h): y</text>

	<!-- Nun die Datenpunkte: -->
	<?php
		$count = 0;

		foreach($dataPoints as $key => $value)
		{
			if($count > 0)
			{
				$x1 = ($oldKey / 86400) * 98;
				$x2 = ($key / 86400) * 98;
			
				$y1 = 95 - (($oldVal / 100) * 95);
				$y2 = 95 - (($value / 100) * 95);
				echo '<line x1="'.$x1.'%" y1="'.$y1.'%" x2="'.$x2.'%" y2="'.$y2.'%" style="stroke:rgb(0,0,0);stroke-width:1" />';
			}
			$oldKey = $key;
			$oldVal = $value;
			$count++;
		}
	?>
</svg>
</div>
@endforeach

@endsection
@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h2>{{ exec("uptime") }}</h2>
<table class="table table-striped">
	<caption>Daten der letzten Tage</caption>
	<tr>
		<th>Datum</th>
		<th>Suchanfragen zur gleichen Zeit</th>
		<th>Suchanfragen insgesamt</th>
		<th>Mittelwert</th>
	</tr>
	@if( isset($today) )
	<tr>
		<td>{{ date(DATE_RFC822, mktime(date("H"),date("i"), date("s"), date("m"), date("d"), date("Y"))) }}</td>
		<td>{{ $today }}</td>
		<td>???</td>
		<td>???</td>
	</tr>
	@endif
	@foreach($oldLogs as $key => $value)
	<tr>
		<td>{{ date(DATE_RFC822, mktime(date("H"),date("i"), date("s"), date("m"), date("d")-$key, date("Y"))) }}</td>
		<td>{{ $value['sameTime'] }}</td>
		<td>{{ $value['insgesamt'] }}</td>
		<td>{{ $value['median'] }}</td>
	</tr>
	@endforeach
</table>

@if( isset($rekordDate) && isset($rekordTagSameTime) && isset($rekordCount) )
<h3>Rekord am {{ $rekordDate }} zur gleichen Zeit <span class="text-info">{{ $rekordTagSameTime }}</span> - insgesamt <span class="text-danger">{{ $rekordCount }}</span></h3>
@endif
@endsection
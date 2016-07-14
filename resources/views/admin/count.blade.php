@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h2>{{ exec("uptime") }}</h2>
<h3>Heute haben wir bis jetzt => <span class="text-info">{{ $today }}</span> Abfragen</h3>
<h3>Gestern zur gleichen Zeit <span class="text-info">{{ $oldLogs[1]['sameTime'] }}</span> - insgesamt <span class="text-danger">{{ $oldLogs[1]['insgesamt'] }}</span></h3>
<h3>Vorgestern zur gleichen Zeit <span class="text-info">{{ $oldLogs[2]['sameTime'] }}</span> - insgesamt <span class="text-danger">{{ $oldLogs[2]['insgesamt'] }}</span></h3>
<h3>Vorvorgestern zur gleichen Zeit <span class="text-info">{{ $oldLogs[3]['sameTime'] }}</span> - insgesamt <span class="text-danger">{{ $oldLogs[3]['insgesamt'] }}</span></h3>
<ul class="list-unstyled" style="text-align:left;">
	@foreach($median as $time => $value)
	<li><h4>Mittelwert der letzten {{ $time }} Tage: <span class="text-danger">{{ number_format($value, 0, ",", ".") }}</span> Abfragen pro Tag</h4></li>
	@endforeach
</ul>
<h3>Rekord am {{ $rekordDate }} zur gleichen Zeit <span class="text-info">{{ $rekordTagSameTime }}</span> - insgesamt <span class="text-danger">{{ $rekordCount }}</span></h3>
@endsection
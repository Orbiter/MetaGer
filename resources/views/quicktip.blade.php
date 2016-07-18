<html>
	<head>
		<title>MetaGer Quicktips</title>
		<link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="/css/quicktips.css" />
	</head>
	<body>
		<div class="quicktip aufruf bg-danger">
			<h1>MetaGer sagt <i>Danke</i></h1>
			Vielen Dank für mehr als 20 Jahre Unterstützung.
			<br>
			<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spendenaufruf") }}" class="btn btn-sm btn-danger" style="margin-top:5px;" target="_blank">Lesen Sie den Spendenaufruf</a>
		</div>		

		@if( $spruch !== "" )
			<blockquote id="spruch">{!! $spruch !!}</blockquote>
		@endif
		@foreach( $mqs as $mq)
			<div class="quicktip">
				<b class="qtheader"><a href="{{ $mq['URL'] }}" target="_blank">{{ $mq['title'] }}</a></b><br>
				<div>{!! $mq['descr'] !!}</div>
				@if( isset($mq['gefVon']) )
					<div class="pull-right">{!! $mq['gefVon'] !!}</div>
				@endif
			</div>
		@endforeach
	</body>
</html>

@extends('layouts.resultPage')

@section('results')
@if( sizeof($errors) > 0 )
		<div class="alert alert-danger">
			<ul>
				@foreach($errors as $error)
				<li>{!! $error !!}</li>
				@endforeach
			</ul>
		</div>
	@endif
	@if( sizeof($warnings) > 0)
		<div class="alert alert-warning">
			<ul>
				@foreach($warnings as $warning)
					<li>{!! $warning !!}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<div class="mg-panel container result ad row" style="text-align:center;">
		<div class="col-xs-12 col-sm-8" style="margin:0 auto;">
			<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spendenaufruf") }}" target="_blank">
				<img src="/img/aufruf.png" style="width:100%">
			</a>
		</div>
	</div>
	<div id="container">
	@foreach($metager->getResults()->items() as $result)
		<div class="item">
			<div class="img">
				<a href="{{ $result->link }}" target="{{ $metager->getTab() }}"><img src="{{ $metager->getImageProxyLink($result->image) }}" width="150px" alt="Bild nicht gefunden"/></a>
			</div>
		</div>
	@endforeach
	</div>
	<nav class="pager">
		{!! $metager->getResults()->links() !!}
	</nav>
@endsection


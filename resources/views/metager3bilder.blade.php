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
	@if( !$metager->validated)
	<div class="mg-panel container" id="spendenaufruf" style="margin-bottom:20px;max-height:126px;max-width:100%;text-align:center;padding:0px;margin-top:0px">
					<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spendenaufruf") }}">
					<img src="/img/aufruf.png" style="max-width:100%;max-height:126px;">
					</a>
			</div>
	@endif
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


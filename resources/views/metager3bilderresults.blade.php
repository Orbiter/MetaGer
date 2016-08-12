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
@if( !$metager->validated && App::isLocale('de') )
	<div class="mg-panel container" id="spendenaufruf" style="margin-bottom:20px;max-height:126px;max-width:100%;text-align:center;padding:0px;margin-top:0px">
					<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spendenaufruf") }}">
					<img src="/img/aufruf.png" style="max-width:100%;max-height:126px;" alt="Spendenaufruf für die unabhängige, nicht-kommerzielle Internet-Suche" >
					</a>
			</div>
	@endif
<div id="container">
	@foreach($metager->getResults()->items() as $result)
		<div class="item">
			<div class="img">
				<a href="{{ $result->link }}" target="{{ $metager->getTab() }}"><img src="{{ $metager->getImageProxyLink($result->image) }}" width="150px" alt=""/></a>
				<span class="label label-default">{{ strip_tags($result->gefVon) }}</span>
			</div>
		</div>
	@endforeach
</div>
<nav class="pager">
	{!! $metager->getResults()->links() !!}
</nav>

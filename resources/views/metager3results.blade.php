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
<div class="col-xs-12 col-md-8">
	@if( !$metager->validated && App::isLocale('de') )
		<div class="mg-panel container" style="margin-bottom:20px;max-height:90px;text-align:left; max-width:100%; padding:0px;margin-top:0px">
			<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spendenaufruf") }}" target="_blank">
				<img src="/img/aufruf.png" style="max-width:100%;max-height:90px;" alt="Spendenaufruf für die unabhängige, nicht-kommerzielle Internet-Suche" >
			</a>
		</div>
	@endif
	{{-- 3-Mal Werbung --}}
	@for($i = 0; $i <= 2; $i++)
		@include('layouts.ad', ['ad' => $metager->popAd()])
	@endfor
	@foreach($metager->getResults()->items() as $result)
		@if($result->number % 7 === 0)
			@include('layouts.ad', ['ad' => $metager->popAd()])
		@endif
		@include('layouts.result', ['result' => $result])
	@endforeach
	<nav class="pager">
		{!! $metager->getResults()->links() !!}
	</nav>
</div>
<div class="hidden-xs col-md-4" id="quicktips">
	<iframe class="col-mod-4 hidden-xs hidden-sm" src="/qt?q={{ $metager->getQ() }}&sprueche={{ $metager->getSprueche() }}"></iframe>
</div>


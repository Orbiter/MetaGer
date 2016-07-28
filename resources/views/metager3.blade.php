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
	@if( $metager->showQuicktips() )
		<div class="col-xs-12 col-md-8 resultContainer">
	@else
		<div class="col-xs-12 col-md-12 resultContainer">
	@endif
	@if( !$metager->validated && App::isLocale('de') )
	<!--/*
	  *
	  * Revive Adserver Asynchronous JS Tag
	  * - Generated with Revive Adserver v3.2.4
	  *
	  */-->

	<ins data-revive-zoneid="1" data-revive-id="13c7408a52f1300b059ed2d36a6e0cdd"></ins>
	<script async src="//ads.metager3.de/www/delivery/asyncjs.php"></script>
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
	@if( $metager->showQuicktips() )
		<div class="hidden-xs col-md-4" id="quicktips">
			<iframe class="col-mod-4 hidden-xs hidden-sm" src="/qt?q={{ $metager->getQ() }}&sprueche={{ $metager->getSprueche() }}"></iframe>
			<!--/*
			  *
			  * Revive Adserver Asynchronous JS Tag
			  * - Generated with Revive Adserver v3.2.4
			  *
			  */-->

			<ins data-revive-zoneid="2" data-revive-id="13c7408a52f1300b059ed2d36a6e0cdd"></ins>
			<script async src="//ads.metager3.de/www/delivery/asyncjs.php"></script>
		</div>
	@endif
@endsection


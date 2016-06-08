@extends('layouts.resultPage')

@section('results')

<div class="col-md-8">
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
<div class="col-md-4" id="quicktips">
	<iframe class="col-mod-4 hidden-xs hidden-sm" src="/qt?q={{ $metager->getQ() }}&sprueche={{ $metager->getSprueche() }}"></iframe>
</div>

@endsection


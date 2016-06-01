@extends('layouts.resultPage')

@section('results')

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

@endsection


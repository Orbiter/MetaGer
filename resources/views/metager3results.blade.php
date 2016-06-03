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
</div>
<div class="col-md-4" id="quicktips">
</div>
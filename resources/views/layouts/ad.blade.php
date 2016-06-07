@if(isset($ad))
<div class="result ad row">
	<div class="number col-sm-1"></div>
	<div class="resultInformation col-sm-10">
		<p class="title">
			<a class="title" href="{{ $ad['link'] }}" target="{{ $metager->getTab() }}" data-hoster="{{ $ad['gefVon'] }}" data-count="0">
			{{ $ad['titel'] }}
			</a>
		</p>
		<p class="link">
			<a href="{{ $ad['link'] }}" target="{{ $metager->getTab() }}" data-hoster="{{ $ad['gefVon'] }}" data-count="0">
			{{ $ad['anzeigeLink'] }}
			</a>
			<span class="hoster">
				Werbung von {!! $ad['gefVon'] !!}
			</span>
		</p>
		<p class="description">
		{{ $ad['descr'] }}
		</p>
	</div>
</div>
@endif
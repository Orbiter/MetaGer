@if(isset($ad))
<div class="result ad">
	<div class="number"></div>
	<div class="resultInformation">
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
<div id="container">
@foreach($metager->getResults()->items() as $result)
	<div class="item">
		<div class="img">
			<a href="{{ $result->link }}" target="{{ $metager->getTab() }}">
			<img src="{{ $metager->getImageProxyLink($result->image) }}" width="150px" alt="Bild nicht gefunden"/>
			</a>
		</div>
	</div>
@endforeach
</div>
<nav class="pager">
        {!! $metager->getResults()->links() !!}
</nav>
<div class="result row">
		<div class="number col-sm-1 hidden-xs" style="color:{{ $result->color }}">
			{{ $result->number }})
		</div>
		<div class="resultInformation col-xs-12 col-sm-11">
			<div class="col-xs-10 col-sm-11" style="padding:0; ">
			<p class="title">
				<a class="title" href="{{ $result->link }}" target="{{ $metager->getTab() }}" data-hoster="{{ strip_tags($result->gefVon) }}" data-count="{{ $result->number }}">
				{{ $result->titel }}
				</a>
			</p>
			<div class="link">
				<div>
					<div class="link-link">
						<a href="{{ $result->link }}" target="{{ $metager->getTab() }}" data-hoster="{{ strip_tags($result->gefVon) }}" data-count="{{ $result->number }}">
						{{ $result->anzeigeLink }}
						</a>
					</div>
					<div class="options">
						<a tabindex="0" data-toggle="popover" data-trigger="focus" data-placement="auto bottom" data-container="body" data-html="true" data-title="<span class='glyphicon glyphicon-cog'></span> Optionen">
							@if(strlen($metager->getSite()) === 0)
							<span class="glyphicon glyphicon-triangle-bottom"></span>
							@endif
						</a>
						<div class="content hidden">
							<ul class="options-list list-unstyled">
								<li>
									<a href="{{ $metager->generateSiteSearchLink($result->strippedHost) }}">
										Suche auf dieser Domain neu starten
									</a>
								</li>
								<li>
									<a href="{{ $metager->generateRemovedHostLink($result->strippedHost) }}">
										{{ $result->strippedHost }} ausblenden
									</a>
								</li>
								@if( $result->strippedHost !== $result->strippedDomain )
								<li>
								<a href="{{ $metager->generateRemovedDomainLink($result->strippedDomain) }}">
								*.{{ $result->strippedDomain }} ausblenden
								</a>
								</li>
								@endif
							</ul>
						</div>
					</div>
				</div>
				<span class="hoster">
				von {!! $result->gefVon !!}
				</span>
				@if( isset($result->partnershop) && $result->partnershop === TRUE )
				<span class="partnershop-info">
				<img src="/img/boosticon.png" height="13" alt="">
				<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/partnershops") }}" target="_blank">Partnershop</a>
				</span>
				@endif
				<a class="proxy" onmouseover="$(this).popover('show');" onmouseout="$(this).popover('hide');" data-toggle="popover" data-placement="auto right" data-container="body" data-content="Der Link wird anonymisiert geöffnet. Ihre Daten werden nicht zum Zielserver übetragen. Möglicherweise funktionieren manche Webseiten nicht wie gewohnt." href="{{ $result->proxyLink }}" target="{{ $metager->getTab() }}">
					<img src="/img/proxyicon.png" alt="" />
					anonym öffnen
				</a>
			</div>
			</div>
			@if( isset($result->logo) )
			<div class="col-xs-2 col-sm-1" style="padding: 0;">
				<a href="{{ $result->link }}" target="{{ $metager->getTab() }}" data-hoster="{{ strip_tags($result->gefVon) }}" data-count="{{ $result->number }}">
				    <img src="{{ $metager->getImageProxyLink($result->logo) }}" alt="" />
				</a>
			</div>
			@endif
			@if( $result->image !== "" )
			<div class="description">
			    <a href="{{ $result->link }}" target="{{ $metager->getTab() }}" data-hoster="{{ strip_tags($result->gefVon) }}" data-count="{{ $result->number }}">
				    <img src="{{ $metager->getImageProxyLink($result->image) }}" align="left" width="120px" height="60px" alt="" />
				</a>
				{!! $result->descr !!}
			</div>
			@else
			<div class="description">{{ $result->descr }}</div>
			@endif
		</div>
</div>

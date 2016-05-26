@extends('layouts.resultPage')

@section('results')
	@foreach($results as $result)
		<div class="result">
            <div class="number" style="color:#FF4000;">1 )</div>
            <div class="resultInformation">
                <p class="title">
                     <a class="title" href="{{ $result['link'] }}" target="_blank">
                        {{ $result['titel'] }}
                    </a>
                </p>
                <div class="link">
                    <div>
                        <div class="link-link">
                                <a data-count="1" data-host="test.de" data-hoster="fastbot" href="{{ $result['link'] }}" target="_blank">
                                    {{ $result['anzeigeLink'] }}
                                </a>
                            </div>
                            <div class="options">
                                <a data-container="body" data-html="true" data-placement="auto bottom" data-title="&lt;span class='glyphicon glyphicon-cog'&gt;&lt;/span&gt; Optionen" data-toggle="popover" data-trigger="focus" tabindex="0">
                                    <span class="glyphicon glyphicon-triangle-bottom"></span>
                                </a>
                                <div class="content hidden">
                                    <ul class="options-list list-unstyled">
                                        <li>
                                            <a href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test%20site%3Awww.test.de&amp;encoding=utf8&amp;lang=all">
                                                Suche auf dieser Domain neu starten
                                            </a>
                                        </li>
                                        <li>
                                            <form method="get" action="/meta/meta.ger3" enctype="multipart/form-data" accept-charset="UTF-8" class="form">
                                                <input type="hidden" name="focus" value="web" />
                                                <input type="hidden" name="eingabe" value="test -host:test.de" />
                                                <input type="hidden" name="encoding" value="utf8" />
                                                <input type="hidden" name="lang" value="all" />
                                                <input type="hidden" name="mobile" value="0" />
                                                <input type="submit" name="" value="test.de ausblenden" />
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> 
                        <span class="hoster ">
                            von  {!! $result['gefVon'] !!}
                        </span>
                        <a class="proxy" data-container="body" data-content="Der Link wird anonymisiert geöffnet. Ihre Daten werden nicht zum Zielserver übetragen. Möglicherweise funktionieren manche Webseiten nicht wie gewohnt." data-placement="auto right" data-toggle="popover" href="https://proxy.suma-ev.de/cgi-bin/nph-proxy.cgi/en/I0/https/www.fastbot.de/red.php?red=3365000124457707395+http://www.test.de" onmouseout="$(this).popover('hide');" onmouseover="$(this).popover('show');" target="_blank">
                            <img alt="Proxy-Icon" src="/img/proxyicon.png" />anonym öffnen
                        </a>
                    </div>
                    <div class="description">{{ $result['descr'] }}</div>
                </div>
            </div>
	@endforeach
@endsection

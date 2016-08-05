@extends('layouts.staticPages')

@section('title', $title )

@section('navbarFocus.search', 'class="active"')

@section('navbarFocus.donate', 'class="dropdown"')

@section('content')
	<div class="modal fade" id="plugin-modal" tab-index="-1" role="dialog">
      <div class="modal-dialog ">
        <div class="content modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;
              </span>
            </button>
            <h4>
              @if ($browser === 'Firefox' || $browser === 'Mozilla')
                {{ trans('index.plugin.head.1') }}
              @elseif ($browser === 'Chrome')
                {{ trans('index.plugin.head.2') }}
              @elseif ($browser === 'Opera')
                {{ trans('index.plugin.head.3') }}
              @elseif ($browser === 'IE')
                {{ trans('index.plugin.head.4') }}
              @elseif ($browser === 'Edge')
                {{ trans('index.plugin.head.5') }}
              @else
                $(".seperator").addClass("hidden");
              @endif
            </h4>
            <p class="text-muted">{{ trans('index.plugin.head.info') }}</p>
          </div>
          <div class="modal-body">
            @if ($browser === 'Firefox' || $browser === 'Mozilla')
              <ol>
                <li>{{ trans('index.plugin.firefox.1') }}<a href="javascript:window.external.AddSearchProvider($('link[rel=search]').attr('href'))">{{ trans('index.plugin.firefox.2') }}</a>{{ trans('index.plugin.firefox.3') }}</li>
                <li>{{ trans('index.plugin.firefox.4') }}</li>
                <li>{{ trans('index.plugin.firefox.5') }}</li>
              </ol>
              <h4>MetaGer als Startseite im {{ $browser }} einrichten</h4>
              <hr />
              <ol>
                <li>Klicken Sie oben rechts im Browser auf <span class="glyphicon glyphicon-menu-hamburger"></span> und öffnen Sie die "Einstellungen"</li>
                <li>Tragen Sie im Feld "Startseite" "https://metager.de" ein.</li>
              </ol>
            @elseif ($browser === 'Chrome')
              <ol>
                <li>{!! trans('index.plugin.chrome.1') !!}</li>
                <li>{!! trans('index.plugin.chrome.2') !!}</li>
                <li>{{ trans('index.plugin.chrome.3') }}</li>
              </ol>
              <h4>MetaGer als Startseite im {{ $browser }} einrichten</h4>
              <hr />
              <ol>
                <li>Klicken Sie oben rechts im Browser auf <span class="glyphicon glyphicon-option-vertical"></span> und öffnen Sie die "Einstellungen"</li>
                <li>Im Bereich "Beim Start" wählen Sie "Bestimmte Seite oder Seiten öffnen und klicken anschließend auf "Seiten festlegen"</li>
                <li>Tragen Sie "https://metager.de" als URL bei "Neue Seite hinzufügen ein"</li>
                <li>Hinweis: Alle hier sichtbaren Webseiten werden nun beim Start des Browsers geöffnet. Sie können Einträge entfernen, indem Sie mit der Maus drüber fahren und rechts auf das "x" klicken.</li>
              </ol>
            @elseif ($browser === 'Opera')
              <ol>
                <li>{{ trans('index.plugin.opera.1') }}<a href="/" target="_blank">{{ trans('index.plugin.opera.2') }}</a>{{ trans('index.plugin.opera.3') }}</li>
                <li>{{ trans('index.plugin.opera.4') }}</li>
                <li>{{ trans('index.plugin.opera.5') }}</li>
                <li>{{ trans('index.plugin.opera.6') }}</li>
                <li><small>{{ trans('index.plugin.opera.7') }}<a href="https://www.mozilla.org/de/firefox/new/" target="_blank">{{ trans('index.plugin.opera.8') }}</a>{{ trans('index.plugin.opera.9') }}</small>
              </ol>
              <h4>MetaGer als Startseite im {{ $browser }} einrichten</h4>
              <hr />
              <ol>
                <li>Klicken Sie im Browser-Menü auf "Bearbeiten"->"Einstellungen"</li>
                <li>Im Bereich "Beim Starten" wählen Sie "Eine bestimmte Seite, oder bestimmte Seiten öffnen
" und anschließend "Seiten festlegen"</li>
                <li>Tragen Sie bei "Adresse eingeben" "https://metager.de" ein und bestätigen mit Enter</li>
                <li>Klicken Sie auf "OK"</li>
              </ol>
            @elseif ($browser === 'IE')
              <ol>
                <li>{{ trans('index.plugin.IE.1') }}<a href="javascript:window.external.addSearchProvider($('link[rel=search]').attr('href'));">{{ trans('index.plugin.IE.2') }}</a>{{ trans('index.plugin.IE.3') }}
                <li>{{ trans('index.plugin.IE.4') }}<span class="glyphicon glyphicon-cog"></span>)</li>
                <li>{{ trans('index.plugin.IE.5') }}</li>
                <li>{{ trans('index.plugin.IE.6') }}</li>
                <li>{{ trans('index.plugin.IE.7') }}</li>
              </ol>
              <h4>MetaGer als Startseite im {{ $browser }} einrichten</h4>
              <hr />
              <ol>
                <li>Klicken Sie oben rechts im Browser auf <span class="glyphicon glyphicon-cog"></span> und öffnen Sie die "Internetoptionen"</li>
                <li>Falls MetaGer die einzige Startseite sein soll, markieren Sie im Textfeld den kompletten Text und ersetzen Ihn durch "https://metager.de"</li>
                <li>Klicken Sie auf "OK"</li>
              </ol>
            @elseif ($browser === 'Edge')
              <ol>
                <li>{{ trans('index.plugin.edge.1') }}<span class="glyphicon glyphicon-option-horizontal"></span>{{ trans('index.plugin.edge.2') }}</li>
                <li>{{ trans('index.plugin.edge.3') }}</li>
                <li>{{ trans('index.plugin.edge.4') }}</li>
                <li>{{ trans('index.plugin.edge.5') }}</li>
              </ol>
              <h4>MetaGer als Startseite im {{ $browser }} einrichten</h4>
              <hr />
              <ol>
                <li>Klicken Sie oben rechts im Browser auf <span class="glyphicon glyphicon-option-horizontal"></span> und öffnen Sie die "Einstellungen"</li>
                <li>Wählen Sie im Bereich "Öffnen mit" den Punkt "Bestimmte Seite(n)" aus</li>
                <li>Im Dropdown-Menü darunter wählen Sie "Benutzerdefiniert" aus</li>
                <li>Im Bereich darunter tragen Sie "https://metager.de" in das Feld "Webadresse eingeben" ein und klicken auf das "+" Symbol</li>
                <li>Entfernen Sie alle Einträge, die beim Start nicht geöffnet werden sollen (z.B. about:start), indem Sie rechts auf das kleine "x" klicken.</li>
              </ol>
            @endif
          </div>
        </div>
      </div>
    </div>
	 <h1 id="mglogo">
            <a class="hidden-xs" href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/") }}">MetaGer
            </a>
          </h1>
          <figure>
            <fieldset id="foki">
              <input id="web" type="radio" name="focus" value="web" form="searchForm" @if ($focus === 'web') checked @endif required="">
              <label id="web-label" for="web">
                <span class="glyphicon glyphicon-globe">
                </span>
                <span class="content">Web
                </span>
              </label>
              <input id="bilder" type="radio" name="focus" value="bilder" form="searchForm" @if ($focus === 'bilder') checked @endif required="">
              <label id="bilder-label" for="bilder">
                <span class="glyphicon glyphicon-picture">
                </span>
                <span class="content">{{ trans('index.foki.bilder') }}
                </span>
              </label>
              <input id="nachrichten" type="radio" name="focus" value="nachrichten" form="searchForm" @if ($focus === 'nachrichten') checked @endif required="">
              <label id="nachrichten-label" for="nachrichten">
                <span class="glyphicon glyphicon-bullhorn">
                </span>
                <span class="content">{{ trans('index.foki.nachrichten') }}
                </span>
              </label>
              <input id="wissenschaft" type="radio" name="focus" value="wissenschaft" form="searchForm" @if ($focus === 'wissenschaft') checked @endif required="">
              <label id="wissenschaft-label" for="wissenschaft">
                <span class="glyphicon glyphicon-file">
                </span>
                <span class="content">{{ trans('index.foki.wissenschaft') }}
                </span>
              </label>
              <input id="produkte" type="radio" name="focus" value="produktsuche" form="searchForm" @if ($focus === 'produkte') checked @endif required="">
              <label id="produkte-label" for="produkte">
                <span class="glyphicon glyphicon-shopping-cart">
                </span>
                <span class="content">{{ trans('index.foki.produkte') }}
                </span>
              </label>
              <input id="angepasst" type="radio" name="focus" value="angepasst" form="searchForm" @if ($focus === 'angepasst') checked @endif required="">
              <label id="anpassen-label" for="angepasst">
                <span class="glyphicon glyphicon-cog">
                </span>
                <span class="content">
                  <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/settings") }}">
                  @if ($focus === 'angepasst')
                  {{ trans('index.foki.angepasst') }}
                  @else
                  {{ trans('index.foki.anpassen') }}
                  @endif
                  </a>
                </span>
              </label>
            </fieldset>
            <fieldset>
              <form id="searchForm" method="GET" action="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/meta/meta.ger3") }}" accept-charset="UTF-8">
                <div class="input-group">
                  <div class="input-group-addon">
                    <button type="button" data-toggle="popover" data-html="true" data-container="body" title="Pers&ouml;nliches Design ausw&auml;hlen" data-content='&lt;ul id="color-chooser" class="list-inline list-unstyled"&gt;
&lt;li &gt;&lt;a id="standard" data-rgba="255,194,107,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;li &gt;&lt;a id="standardHard" data-rgba="255,128,0,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;li &gt;&lt;a id="blue" data-rgba="164,192,230,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;li &gt;&lt;a id="blueHard" data-rgba="2,93,140,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;li &gt;&lt;a id="green" data-rgba="177,226,163,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;li &gt;&lt;a id="greenHard" data-rgba="127,175,27,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;li &gt;&lt;a id="red" data-rgba="255,92,92,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;li &gt;&lt;a id="redHard" data-rgba="255,0,0,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;li &gt;&lt;a id="pink" data-rgba="255,196,246,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;li &gt;&lt;a id="pinkHard" data-rgba="254,67,101,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;li &gt;&lt;a id="black" data-rgba="238,238,238,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;li &gt;&lt;a id="blackHard" data-rgba="50,50,50,1" href="javascript:void(0)"&gt;&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;'>
                      <span class="glyphicon glyphicon-tint">
                      </span>
                    </button>
                  </div>
                  <input type="text" name="eingabe" required="" autofocus="" class="form-control" placeholder="{{ trans('index.placeholder') }}">
                  <input type="hidden" name="encoding" value="utf8">
                  @if ($focus === 'angepasst') <input type="hidden" name="lang" value={{ $lang }} >
                  <input type="hidden" name="resultCount" value={{ $resultCount }} >
                  <input type="hidden" name="time" value={{ $time }} >
                  <input type="hidden" name="sprueche" value={{ $sprueche }} >
                  <input type="hidden" name="tab" value={{ $tab }} >
                    @foreach ($focusPages as $fp)
                      <input type="hidden" name={{ $fp }} value="on">
                    @endforeach
                  @elseif( !App::isLocale('de') )
                  <input type="hidden" name="lang" value="{{ App::getLocale() }}">
                  @else
                  <input type="hidden" name="lang" value="all">
                  @endif
                  <div class="input-group-addon">
                    <button type="submit">
                      <span class="glyphicon glyphicon-search">
                      </span>
                    </button>
                  </div>
                </div>
              </form>
            </fieldset>
            <ul class="list-inline">
              <li>
                <a href="https://www.boost-project.com/de/shops?charity_id=1129&amp;tag=bl" target="_blank" id="foerdershops" class="btn btn-default" title="{{ trans('index.partnertitle') }}">{{ trans('index.conveyor') }}
                </a>
              </li>
              <li class="hidden-xs seperator">|
              </li>
              <li id="plug" @unless ($browser === 'Firefox' || $browser === 'Mozilla' || $browser === 'Chrome' || $browser === 'Opera' || $browser === 'IE' || $browser === 'Edge')
                class="hidden" @endunless >
                <a href="#" id="plugin" data-toggle="modal" data-target="#plugin-modal" class="btn btn-default" title="{{ trans('index.plugintitle') }}">{{ trans('index.plugin') }}</a>
              </li>
            </ul>
          </figure>
        
@endsection

@section('optionalContent')
<section id="moreInformation" class="hidden-xs">
          <h1 class="hidden">{{ trans('index.sponsors.head.1') }}</h1>
          <div class="row">
            <div id="sponsors" class="col-md-6 col-sm12">
              <h2>{{ trans('index.sponsors.head.2') }}</h2>
              <ul>
                <li>
                  {!! trans('index.sponsors.woxikon') !!}</a>
                </li>
                <li>
                  {!! trans('index.sponsors.gutscheine') !!}
                </li>
                <li>
                  {!! trans('index.sponsors.kredite') !!}
                </li>
              </ul>
            </div>
            <div class="col-md-6 col-sm-12">
              <h2>
                <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "about") }}">{{ trans('index.about.title') }}</a>
              </h2>
              <ul>
                <li>
                  <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "datenschutz") }}">{{ trans('index.about.1.1') }}</a>{{ trans('index.about.1.2') }}
                </li>
                <li>{{ trans('index.about.2.1') }}<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "spende") }}">{{ trans('index.about.2.2') }}</a>
                </li>
              </ul>
            </div>
          </div>
        </section>
@endsection

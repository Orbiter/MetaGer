<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>@yield('title')</title>
        <meta charset="utf-8" />
        <meta name="description" content="Sicher suchen und finden unter Wahrung der Privatsph&auml;re. Das digitale Wissen der Welt muss ohne Bevormundung durch Staaten oder Konzerne frei zug&auml;nglich sein und bleiben." />
        <meta name="keywords" content="Internetsuche, privatsph&auml;re, privacy, Suchmaschine, Datenschutz, Anonproxy, anonym suchen, Bildersuche, Suchmaschine, anonym, MetaGer, metager, metager.de" />
        <meta name="page-topic" content="Dienstleistung" />
        <meta name="robots" content="index,follow" />
        <meta name="revisit-after" content="7 days" />
        <meta name="audience" content="all" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <meta rel="icon" type="image/x-icon" href="/favicon.ico" />
        <meta rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
        <link rel="search" type="application/opensearchdescription+xml" title="MetaGer: Sicher suchen &amp; finden, Privatsph&auml;re sch&uuml;tzen" href="{{ action('StartpageController@loadPlugin', Request::all()) }}">
        <link href="/css/bootstrap.css" rel="stylesheet" />
        <link href="/css/style.css" rel="stylesheet" />
        @if (isset($css))
        <link href="/css/{{ $css }}" rel="stylesheet" />
        @endif
        <link id="theme" href="/css/theme.css.php" rel="stylesheet" />
    </head>
    <body>
        <header>
          <nav class="navbar navbar-default">
            <div class="container">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                  <span class="sr-only">{{ trans('staticPages.navigationToggle') }}
                  </span>
                  <span class="icon-bar">
                  </span>
                  <span class="icon-bar">
                  </span>
                  <span class="icon-bar">
                  </span>
                </button>
              </div>
              <div>
                @yield('homeIcon')
              </div>
              <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                  <li>
                    <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/") }}" id="navigationSuche">{{ trans('staticPages.nav1') }}
                    </a>
                  </li>
                  <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ trans('staticPages.nav16') }}
                      <span class="caret">
                      </span>
                    </a>
                    <ul class="dropdown-menu">
                      <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spende/") }}">{{ trans('staticPages.nav2') }}
                        </a>
                      </li>
                      <li>
                        <a href="https://www.boost-project.com/de/shops?charity_id=1129&amp;tag=bl">{{ trans('staticPages.nav17') }}
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/datenschutz/") }}" id="navigationPrivacy">{{ trans('staticPages.nav3') }}
                    </a>
                  </li>
                  <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="navigationKontakt">{{ trans('staticPages.nav18') }}
                      <span class="caret">
                      </span>
                    </a>
                    <ul class="dropdown-menu">
                      <li>
                        <a href="http://forum.suma-ev.de/">{{ trans('staticPages.nav4') }}
                        </a>
                      </li>
                      <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/kontakt/") }}">{{ trans('staticPages.nav5') }}
                        </a>
                      </li>
                      <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/team/") }}">{{ trans('staticPages.nav6') }}
                        </a>
                      </li>
                      <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/about/") }}">{{ trans('staticPages.nav7') }}
                        </a>
                      </li>
                      <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/impressum/") }}">{{ trans('staticPages.nav8') }}
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ trans('staticPages.nav15') }}
                      <span class="caret">
                      </span>
                    </a>
                    <ul class="dropdown-menu">
                      <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/hilfe/") }}">{{ trans('staticPages.nav9') }}
                        </a>
                      </li>
                      <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/widget/") }}">{{ trans('staticPages.nav10') }}
                        </a>
                      </li>
                      <li>
                        <a href="https://metager.de/klassik/asso/" target="_blank">{{ trans('staticPages.nav11') }}
                        </a>
                      </li>
                      <li>
                        <a href="http://code.metager.de/" target="_blank">{{ trans('staticPages.nav12') }}
                        </a>
                      </li>
                      <li>
                        <a href="https://metager.to/" target="_blank">{{ trans('staticPages.nav13') }}
                        </a>
                      </li>
                      <li>
                        <a href="http://forum.suma-ev.de/viewtopic.php?f=3&amp;t=43" target="_blank">{{ trans('staticPages.nav14') }}
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="navigationSprache">{{ trans('staticPages.nav19') }}
                      <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                      <li><a rel="alternate" hreflang="{{$localeCode}}" href="{{LaravelLocalization::getLocalizedURL($localeCode) }}">{{{ $properties['native'] }}}</a></li>
                      @endforeach
                    </ul>
                  </li>
                </ul>
              </div>
            </div>
          </nav>
        </header>

        <div class="wrapper">
            <main class="mg-panel container">
                @if (isset($success))
                  <div class="alert alert-success" role="alert">{{ $success }}</div>
                @endif
                @if (isset($error))
                  <div class="alert alert-danger" role="alert">{{ $error }}</div>
                @endif
                @yield('content')
            </main>
            @yield('optionalContent')
            <footer>
              <ul class="list-inline hidden-xs">
                <li>
                  <a href="https://www.suma-ev.de/" target="_blank">
                    <img src="/img/suma_ev_logo-m1-greyscale.png" alt="SUMA-EV Logo">
                  </a>
                </li>
                <li id="info">
                  <a href="/kontakt/">{{ trans('staticPages.nav5') }}</a> - <a href="/impressum/">{{ trans('staticPages.nav8') }}</a>
                  {{ trans('staticPages.sumaev.1') }}<a href="https://www.suma-ev.de/" target="_blank" >{{ trans('staticPages.sumaev.2') }}</a>
                </li>
                <li>
                  <a href="https://www.uni-hannover.de/" target="_blank">
                    <img src="/img/luh_metager.png" alt="LUH Logo">
                  </a>
                </li>
              </ul>
            </footer>
            <script type="text/javascript" src="/js/jquery.js"></script>
            <script type="text/javascript" src="/js/bootstrap.js"></script>
            <script type="text/javascript" src="/js/scriptStartPage.js"></script>
	    @if (isset($js))
	    	@foreach ($js as $script)
            		<script type="text/javascript" src="/js/{{ $script }}"></script>
            	@endforeach
	    @endif
            <!--[if lte IE 8]><script type="text/javascript" src="/js/html5shiv.min.js"></script><![endif]-->
    </body>
</html>

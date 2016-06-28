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
            </h4>
          </div>
          <div class="modal-body">
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
                  <a href="/settings/">{{ trans('index.foki.anpassen') }}
                  </a>
                </span>
              </label>
            </fieldset>
            <fieldset>
              <form id="searchForm" method="GET" action="/meta/meta.ger3" accept-charset="UTF-8">
                <div class="input-group">
                  <div class="input-group-addon">
                    <button type="button" data-toggle="popover" data-html="true" data-container="body" title="Pers&ouml;nliches Design ausw&auml;hlen" data-content='	&lt;ul id="color-chooser" class="list-inline list-unstyled"&gt;
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
                  @else <input type="hidden" name="lang" value="all">
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
                <a href="https://www.boost-project.com/de/shops?charity_id=1129&amp;tag=bl" target="_blank" id="foerdershops">{{ trans('index.conveyor') }}
                </a>
              </li>
              <li class="hidden-xs seperator">|
              </li>
              <li id="plug">
                <a href="#" id="plugin" data-toggle="modal" data-target="#plugin-modal">{{ trans('index.plugin') }}
                </a>
              </li>
            </ul>
          </figure>
        
@endsection

@section('optionalContent')
<section id="moreInformation" class="hidden-xs">
          <h1 class="hidden">{{ trans('index.furtherInfo') }}
          </h1>
          <div class="row">
            <div id="sponsors" class="col-md-6 col-sm12">
              <h2>{{ trans('index.sponsors') }}
              </h2>
              <ul>
                <li>
                  <a href="http://www.woxikon.de/" class="mutelink" target="_blank">{{ trans('index.sponsors.woxikon') }}
                  </a>
                </li>
                <li>
                  <a href="http://www.gutschein-magazin.de/" class="mutelink" target="_blank">{{ trans('index.sponsors.gutscheine') }}
                  </a>
                </li>
                <li>
                  <a href="https://www.finanzcheck.de/" class="mutelink" target="_blank">{{ trans('index.sponsors.kredite') }}
                  </a>
                </li>
              </ul>
            </div>
            <div class="col-md-6 col-sm-12">
              <h2>
                <a href="/about/">{{ trans('index.about.title') }}
                </a>
              </h2>
              <ul>
                <li>
                  <a href="/datenschutz/">{{ trans('index.about.1.1') }}</a>{{ trans('index.about.1.2') }}
                </li>
                <li>{{ trans('index.about.2.1') }}<a href="/spende/">{{ trans('index.about.2.2') }}</a>
                </li>
              </ul>
            </div>
          </div>
        </section>
@endsection
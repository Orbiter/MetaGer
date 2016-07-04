@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>{{ trans('about.head.1') }}</h1>
<h3>{{ trans('about.head.2') }}</h3>
<ul id="about-list">
	<li>{{ trans('about.list.1') }} <a href="/datenschutz/">{{ trans('about.list.2') }}...</a></li>
	<li>{{ trans('about.list.3') }} <a href="/spende/">{{ trans('about.list.4') }}</a> {{ trans('about.list.5') }}</li>
	<li><a href="https://de.wikipedia.org/wiki/MetaGer" target="_blank">MetaGer</a> {{ trans('about.list.6') }}  <a href="https://de.wikipedia.org/wiki/Metasuchmaschine" target="_blank">{{ trans('about.list.7') }} </a>{{ trans('about.list.8') }}</li>
	<li>{{ trans('about.list.9') }} <a href="https://de.wikipedia.org/wiki/Filterblase" target="_blank">{{ trans('about.list.10') }}</a> {{ trans('about.list.11') }}</li>
	<li><a href="http://blog.suma-ev.de/node/207" target="_blank">{{ trans('about.list.12') }}</a>{{ trans('about.list.13') }}</li>
	<li> {{ trans('about.list.14') }} <a href="https://metager.de/kontakt/" target="_blank">{{ trans('about.list.15') }}</a> {{ trans('about.list.16') }} </li>
</ul>
@endsection
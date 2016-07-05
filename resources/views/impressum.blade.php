@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>{{ trans('impressum.title') }}</h1>
<h2><a href="http://suma-ev.de/" target="_blank">{{ trans('impressum.headline.1') }}</a></h2>
<h2>{{ trans('impressum.headline.2') }} <a href="http://www.uni-hannover.de/" target="_blank">Leibniz Universität Hannover</a></h2>
<p>{{ trans('impressum.info.1') }} <a href="http://de.wikipedia.org/wiki/Suma_e.V." target="_blank">SUMA-EV</a></p>
<address>SUMA-EV
Röselerstr. 3
D-30159 Hannover
Deutschland/Germany
</address>
<address>{{ trans('impressum.info.2') }}:
Tel.: ++49-(0)511-34000070
EMail: <a href="mailto:office@suma-ev.de">office@suma-ev.de</a><a href="/kontakt/"> - Public-PGP-Key</a>
<a href="/kontakt/">{{ trans('impressum.info.3') }}</a>
</address>
<p>{{ trans('impressum.info.4') }}:
<a href="http://www.intares.de/service_provider_info/management.html">Dr. Bernhard Biedermann</a>, <a href="http://www.nebel.de/unternehmen/vita.shtml">Michael Nebel</a>, <a href="http://de.wikipedia.org/wiki/Wolfgang_Sander-Beuermann">Dr. Wolfgang Sander-Beuermann</a></p>
<p>{{ trans('impressum.info.5') }}:
<a href="http://de.wikipedia.org/wiki/Wolfgang_Sander-Beuermann">Dr. Wolfgang Sander-Beuermann</a>
Public-PGP-Key: <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/team/pubkey-wsb") }}">{{ url('/team/pubkey-wsb') }}</a>
Facebook: <a href="https://www.facebook.com/wolfgang.sanderbeuermann%0A">https://www.facebook.com/wolfgang.sanderbeuermann</a>
Twitter: <a href="http://twitter.com/wosabeu">http://twitter.com/wosabeu</a>
</p>
<p>{{ trans('impressum.info.6') }}: Georg Becker
<a href="mailto:jugendschutz@metager.de">jugendschutz@metager.de</a>
</p>
<p>{{ trans('impressum.info.7') }}:
<a href="http://de.wikipedia.org/wiki/Wolfgang_Sander-Beuermann">Dr. Wolfgang Sander-Beuermann</a></p>
<p>"SUMA-EV - Verein für freien Wissenszugang" {{ trans('impressum.info.8') }} VR200033.

{{ trans('impressum.info.9') }}: DE 300 464 091

{{ trans('impressum.info.10') }}</p>
<h2>{{ trans('impressum.info.11') }}:</h2>
<p>{{ trans('impressum.info.12') }}</p>
@endsection
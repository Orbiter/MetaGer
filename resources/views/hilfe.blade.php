@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>{{ trans('hilfe.head.1') }}</h1>
<p>{{ trans('hilfe.head.2') }}</p>
<h2>{{ trans('hilfe.help.1') }}</h2>
<h3>{{ trans('hilfe.help.2') }}</h3>
<ul>
	<li>{{ trans('hilfe.help.3') }}</li>
	<li>{{ trans('hilfe.help.4') }}</li>
	<li>{{ trans('hilfe.help.5') }}</li>
</ul>
<h2>{{ trans('hilfe.help.6') }}</h2>
<h3>{{ trans('hilfe.help.7') }}</h3>
<ul>
	<li>{{ trans('hilfe.help.8') }}</li>
</ul>
<h4>{{ trans('hilfe.help.9') }}</h4>
<ul>
	<li>{{ trans('hilfe.help.10') }}</li>
	<li>{{ trans('hilfe.help.11') }}</li>
	<li>{{ trans('hilfe.help.12') }}</li>
	<li>{{ trans('hilfe.help.13') }}</li>
</ul>
<h3>{{ trans('hilfe.help.14') }}</h3>
<p>{{ trans('hilfe.help.15') }}</p>
<h2>{{ trans('hilfe.help.16') }}</h2>
<h3>{{ trans('hilfe.help.17') }}</h3>
<ul>
	<li>{{ trans('hilfe.help.18') }}</li>
	<li>{{ trans('hilfe.help.19') }}</li>
</ul>
<h3>{{ trans('hilfe.help.20') }}</h3>
<p>{{ trans('hilfe.help.21') }}</p>
<ul>
	<li>{{ trans('hilfe.help.22') }}</li>
</ul>
<h3>{{ trans('hilfe.help.23') }}</h3>
<p>{{ trans('hilfe.help.24') }}</p>
<h3>{{ trans('hilfe.help.25') }}</h3>
<p>{{ trans('hilfe.help.26') }} <a href="https://metager.de/klassik/asso/" target="_blank">{{ trans('hilfe.help.27') }}</a>{{ trans('hilfe.help.28') }}</p>
<p>{{ trans('hilfe.help.29') }}</p>
<p>{{ trans('hilfe.help.30') }} <a href="https://metager.de/kontakt/" target="_blank">{{ trans('hilfe.help.31') }}</a> {{ trans('hilfe.help.32') }}</p>
<h2>{{ trans('hilfe.help.33') }}</h2>
<h3>{{ trans('hilfe.help.34') }}</h3>
<p>{{ trans('hilfe.help.35') }}</p>
<p>{{ trans('hilfe.help.36') }} <a class="collapsed" href="#faq">MetaGer FAQ</a> {{ trans('hilfe.help.37') }} <a href="https://metager.de/kontakt/" target="_blank">Mail.</a></p>
<h1 id="faq">{{ trans('hilfe.faq.1') }}</h1>
<ol>
	<li><p>{{ trans('hilfe.faq.2') }}</p>
		<p>{{ trans('hilfe.faq.3') }}</p></li>
		<li><p>{{ trans('hilfe.faq.4') }}</p>
		<p>{{ trans('hilfe.faq.5') }}</p></li>
	<li><p>{{ trans('hilfe.faq.6') }}</p>
		<p>{{ trans('hilfe.faq.7') }} <a href="https://de.wikipedia.org/wiki/Metasuchmaschine" target="_blank">Wikipedia</a>, {{ trans('hilfe.faq.8') }}</p></li>
	<li>{{ trans('hilfe.faq.9') }}</li>
	<ul>
		<li>{{ trans('hilfe.faq.10') }}</li>
		<li>{{ trans('hilfe.faq.11') }} <a href="http://www.klug-suchen.de/" target="_blank">klug-suchen.de.</a></li>
	</ul>
	<li><p>{{ trans('hilfe.faq.12') }}</p>
		<p>{{ trans('hilfe.faq.13') }}</p></li>
	<li>{{ trans('hilfe.faq.14') }}</li>
	<p>{{ trans('hilfe.faq.15') }}</p>
	<ul>
		<li>{{ trans('hilfe.faq.16') }}</li>
		<li>{{ trans('hilfe.faq.17') }}</li>
	</ul>
	<li><p>{{ trans('hilfe.faq.18') }}</p>
		<p>{{ trans('hilfe.faq.19') }}</p></li>
	<li><p>{{ trans('hilfe.faq.20') }}</p>
		<p>{{ trans('hilfe.faq.21') }}</p></li>
	<li><p>{{ trans('hilfe.faq.22') }}</p>
	 <p>{{ trans('hilfe.faq.23') }}</p></li>
	<li>
		<p>{{ trans('hilfe.faq.24') }}</p>
		<p>{{ trans('hilfe.faq.25') }}</p></li>
	<li><p>{{ trans('hilfe.faq.26') }}</p></li>
	<ul>
		<li>{{ trans('hilfe.faq.27') }}</li>
		<li>{{ trans('hilfe.faq.28') }}</li>
		<li>{{ trans('hilfe.faq.29') }}</li>
	</ul>
	<p>{{ trans('hilfe.faq.30') }} <a href="mailto:jugendschutz@metager.de" class="link">{{ trans('hilfe.faq.31') }}</a></p>
	<li><p>{{ trans('hilfe.faq.32') }}</p>
			<p>{{ trans('hilfe.faq.33') }}</p></li>
	<li><p>{{ trans('hilfe.faq.34') }}</p>
		<p>{{ trans('hilfe.faq.35') }}</p></li>
	<li><p>{{ trans('hilfe.faq.36') }}</p>
		<p>{{ trans('hilfe.faq.37') }}</p></li>
	<li><p>{{ trans('hilfe.faq.38') }}</p>
		<p>{{ trans('hilfe.faq.39') }}</p></li>
	<li><p>{{ trans('hilfe.faq.40') }}</p>
		<p>{{ trans('hilfe.faq.41') }}</p></li>
	<li><p>{{ trans('hilfe.faq.42') }}</p>
		<p>{{ trans('hilfe.faq.43') }}</p></li>
	<li><p>{{ trans('hilfe.faq.44') }}</p>
		<p>{{ trans('hilfe.faq.45') }}</p>
		<p>{{ trans('hilfe.faq.46') }}</p>
		<p>{{ trans('hilfe.faq.47') }}</p></li>
	<li><p>{{ trans('hilfe.faq.48') }}</p>
		<p>{{ trans('hilfe.faq.49') }}</p>
		<p>{{ trans('hilfe.faq.50') }}</p>
		<ul>
			<li><p>{{ trans('hilfe.faq.51') }}</p></li>
			<li><p>{{ trans('hilfe.faq.52') }} <a href="https://metager.de/hilfe/#TORanleitung" target="_blank">{{ trans('hilfe.faq.53') }}</a> {{ trans('hilfe.faq.54') }}</p></li>
			<li><p>{{ trans('hilfe.faq.55') }}</p></li>
			<li><p>{{ trans('hilfe.faq.56') }}</p></li>
		</ul>
	</li>
	<li><p>{{ trans('hilfe.faq.57') }}</p>
		<p>{{ trans('hilfe.faq.58') }}</p></li>
	<li><p>{{ trans('hilfe.faq.59') }}</p>
		<p>{{ trans('hilfe.faq.60') }}</p></li>
	<li><p>{{ trans('hilfe.faq.61') }}</p>
		<p>{{ trans('hilfe.faq.62') }}</p></li>
	<li><p>{{ trans('hilfe.faq.63') }}</p>
		<p>{{ trans('hilfe.faq.64') }} <a href="https://metager.de/tor/" target="_blank">https://metager.de/tor/</a></p>
		<p>{{ trans('hilfe.faq.65') }}</p>
	</li>
</ol>
@endsection
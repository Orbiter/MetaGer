@extends('layouts.subPages')

@section('title', $title )

@section('content')
	<h1>Team</h1>
	<ul id="teamList">
		<li>
			<a href="https://de.wikipedia.org/wiki/Wolfgang_Sander-Beuermann" target="_blank">Sander-Beuermann, Wolfgang</a>, Dr.-Ing. - {{ trans('team.role.1') }} - 
			<a href="mailto:wsb@suma-ev.de">wsb@suma-ev.de</a> - 
			<a href="/team/pubkey-wsb">Public Key</a>
		</li>
		<li>Becker, Georg - {{ trans('team.role.2') }} - 
			<a href="mailto:georg.becker@suma-ev.de">georg.becker@suma-ev.de</a>
		</li>
		<li>Branz, Manuela - {{ trans('team.role.3') }} - 
			<a href="mailto:manuela.branz@suma-ev.de">manuela.branz@suma-ev.de</a>
		</li>
		<li>Pfennig, Dominik - {{ trans('team.role.4') }} - 
			<a href="mailto:dominik@suma-ev.de">dominik@suma-ev.de</a>
		</li>
		<li>Höfer, Phil - {{ trans('team.role.5') }} - 
			<a href="mailto:phil@suma-ev.de">phil@suma-ev.de</a>
		</li>
		<li>Hasselbring, Karl - {{ trans('team.role.6') }} - 
			<a href="mailto:karl@suma-ev.de">karl@suma-ev.de</a>
		</li>
		<li>Riel, Carsten - {{ trans('team.role.7') }} - 
			<a href="carsten@suma-ev.de">carsten@suma-ev.de</a>
		</li>
	</ul>
	<p>{{ trans('team.contact.1') }} 
		<a href="mailto:office@suma-ev.de">office@suma-ev.de</a> {{ trans('team.contact.2') }} <a href="/kontakt/">{{ trans('team.contact.3') }}</a>{{ trans('team.contact.4') }} <a href="http://forum.suma-ev.de/" target="_blank">{{ trans('team.contact.5') }}</a> {{ trans('team.contact.6') }} <a href="http://forum.suma-ev.de/" target="_blank">{{ trans('team.contact.7') }}</a> {{ trans('team.contact.8') }} </p>
	<p>{{ trans('team.contact.9') }}</p>
@endsection
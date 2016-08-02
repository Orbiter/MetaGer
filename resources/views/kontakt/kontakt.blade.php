@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>{{ trans('kontakt.headline.1') }}</h1>
<h2>{{ trans('kontakt.headline.2') }}</h2>
<p><span class="bold">{!! trans('kontakt.headline.3') !!}</span>
<span>{{ trans('kontakt.headline.4') }}</span></p>
<h2>{{ trans('kontakt.form.1') }}</h2>
<p>{{ trans('kontakt.form.2') }} <a href="mailto:office@suma-ev.de">email</a> {{ trans('kontakt.form.3') }}</p>
<p>{{ trans('kontakt.form.4') }}</p>
<p class="bold">{{ trans('kontakt.form.5') }}</p>
<form class="contact" name="contact" method="post" action="{{ LaravelLocalization::getLocalizedURL() }}">
  {{ csrf_field() }}
  <div class="form-group">
    <input class="form-control" name="email" placeholder="Ihre e-mail-Adresse (optional)" type="text"></div>
  <div class="form-group">
    <textarea class="form-control" id="message" name="message" placeholder="Ihre Nachricht"></textarea></div>
  <div class="form-group">
    <p><span class="bold">{{ trans('kontakt.form.6') }} <a href="http://openpgpjs.org/.">OpenPGP.js</a> {{ trans('kontakt.form.7') }}</span>
<span>{{ trans('kontakt.form.8') }}</span></p>
    <button title="" data-original-title="" class="btn btn-default" type="submit">{{ trans('kontakt.form.9') }}</button></div></form>
<h2>{{ trans('kontakt.mail.1') }}</h2>
<p>{{ trans('kontakt.mail.2') }} <a href="mailto:office@suma-ev.de">office@suma-ev.de</a>
{{ trans('kontakt.mail.3') }}</p>
<textarea id="pubkey" style="width:50%" rows="8" readonly>-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG v1

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
=LZAA
-----END PGP PUBLIC KEY BLOCK-----</textarea>
<h2>{{ trans('kontakt.letter.1') }}</h2>
<p>{{ trans('kontakt.letter.2') }}</p>
<adress>SUMA-EV
Röselerstr. 3
30159 Hannover
Germany</adress>
@endsection

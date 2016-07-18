@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
<h1>Mit Ihnen zusammen: Für die unabhängige, nicht-kommerzielle Internet-Suche</a></h1>
<p><a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spende") }}">{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spende") }}</a></p>
<p>Mit Ihnen zusammen haben wir in den vergangenen Monaten eine neue
Erfolgsstory geschrieben: die Zahl der MetaGer-Nutzer steigt weiter und
stetig.  Es ist kein Strohfeuer kurzfristigen Erfolges, sondern es ist die
Stetigkeit dieser Steigerung, die unseren Erfolg begründet.  In drei Jahren
haben wir unsere Abfragezahlen verdreifacht.  Wir sind nach Expertenmeinung
die sicherste Suchmaschine der Welt.  Unsere Suchergebnisse sind zielgenau
und treffsicher.  Beides verdanken wir der Tatsache, dass wir Programmierer
fest anstellen konnten.  Aber auch Programmierer können nicht allein von der
Liebe zur Sache leben: Nur dank Ihrer Spendenbereitschaft konnten wir das
finanziell stemmen.</p>
@endsection
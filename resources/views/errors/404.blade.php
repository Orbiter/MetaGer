@extends('layouts.subPages')

@section('title', 'Fehler 404 - Seite nicht gefunden')

@section('content')
<h1>{{ trans('404.1') }}</h1>
<p>{{ trans('404.2') }}</p>
@endsection
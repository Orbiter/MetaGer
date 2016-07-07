@extends('layouts.subPages')

@section('title', 'Fehler 500 - Interner Serverfehler')

@section('content')
<h1>{{ trans('500.1') }}</h1>
<p>{{ trans('500.2') }}</p>
@if( config('app.debug') )
<pre>{{ $exception }}</pre>
@endif
@endsection
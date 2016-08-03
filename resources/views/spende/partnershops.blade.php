@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.datenschutz', 'class="active"')

@section('content')
<h1>{{ trans('partnershops.heading1') }}</h1>
<p>{{ trans('partnershops.absatz1') }}</p>
<p>{!! trans('partnershops.absatz2') !!}</p>
@endsection
@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>MetaGer hidden service</h1>
<a class="btn btn-primary" href="http://b7cxf4dkdsko6ah2.onion/" role="button">{{trans('tor.torbutton')}}</a>
@endsection

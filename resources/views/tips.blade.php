@extends('layouts.staticPages')

@section('title', $title )

@section('content')
<h1>MetaGer Tipps, unsortiert - dies & das - wussten Sie schon?</h1>
<ol>
	@foreach( $tips as $tip )
	<li>{!! $tip !!}</li>
	@endforeach
</ol>
@endsection
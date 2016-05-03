@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>MetaGer Widget
</h1>
<p>MetaGer zum Einbau in Ihre Webseite.
  Wählen Sie dafür aus, wo gesucht werden soll:
</p>
<p id="widgetLinks" class="btn-group">
  <a class="btn btn-default" href="websearch/">Suche im Web
  </a>
  <a class="btn btn-default" href="sitesearch/">Suche nur auf einer Domain
  </a>
</p>
<p>Hinweis: Sie dürfen das Widget nicht verwenden, wenn Sie auf Ihrer Seite damit den Eindruck zu erwecken versuchen, MetaGer sei Ihre Dienstleistung oder wenn der Eindruck erweckt werden sollte, Ihre Seiten seien die wahren MetaGer-Seiten (das ist alles schon vorgekommen). Insbesondere ist es aus diesem Grund nicht erlaubt, unser Logo zu entfernen.
</p>
@endsection
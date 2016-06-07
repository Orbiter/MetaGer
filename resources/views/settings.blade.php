@extends('layouts.subPages')

@section('title', $title )

@section('content')
<form action="/" method="get">
  <h1>Einstellungen</h1>
  <p id="lead">Hier k&ouml;nnen Sie Ihr MetaGer anpassen: Nach Anklicken Ihrer gew&uuml;nschten Einstellungen m&uuml;ssen Sie <a href="#unten">unten auf dieser Seite</a> w&auml;hlen, ob Sie die Einstellungen dauerhaft speichern, oder nur einmalig setzen wollen.</p>
  <h2>Allgemein</h2>
  <input type="hidden" name="focus" value="angepasst">
  <div class="checkbox">
    <label>
      <input type="checkbox" name="param_sprueche">Spr&uuml;che ausblenden</label></div>
  <div class="checkbox">
    <label>
      <input type="checkbox" name="param_tab">Links im gleichen Tab &ouml;ffnen</label></div>
  <label class="select-label">Sprache ausw&auml;hlen:</label>
  <select class="form-control" name="param_lang">
    <option value="all">Alle Sprachen</option>
    <option value="de">Deutsch</option></select>
  <label class="select-label">Anzahl der Ergebnisse pro Seite:</label>
  <select class="form-control" name="param_resultCount">
    <option value="10">10</option>
    <option value="20" selected>20</option>
    <option value="50">50</option>
    <option value="100">100</option>
    <option value="0">Alle</option></select>
  <label class="select-label">Maximale Suchzeit:</label>
  <select class="form-control" name="param_time">
    <option value="1000" selected>1 Sekunde (Standard)</option>
    <option value="2000">2 Sekunden</option>
    <option value="5000">5 Sekunden</option>
    <option value="10000">10 Sekunden</option>
    <option value="20000">20 Sekunden</option></select>
  <h2>Suchmaschinen</h2>
  <div class="headingGroup web">
    <h3>Web-Suche:
      <small><a data-type="web" class="checker"> (alle an-/abw&auml;hlen)</a></small></h3>
    <div class="row">
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_fastbot">Fastbot</label><a class="glyphicon glyphicon-link" target="_blank" href="https://www.fastbot.de/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_wikipedia">Wikipedia</label><a class="glyphicon glyphicon-link" target="_blank" href="https://de.wikipedia.org/wiki/Wikipedia:Hauptseite"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_witch">Witch</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.witch.de/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_dmoz">Dmoz</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.dmoz.de/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_overture">Yahoo</label><a class="glyphicon glyphicon-link" target="_blank" href="https://de.yahoo.com/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_yacy">YaCy</label><a class="glyphicon glyphicon-link" target="_blank" href="http://yacy.de/de/index.html"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_nebel">Netluchs</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.netluchs.de/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_exalead">Exalead</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.exalead.com/search/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_BASE">BASE</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.base-search.net/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_yandex">Yandex</label><a class="glyphicon glyphicon-link" target="_blank" href="https://www.yandex.com/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_zeitde">Die ZEIT</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.zeit.de/index"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_onenewspage">OneNewspage</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.onenewspage.com/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_onenewspagevideo">OneNewspage (Video)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.onenewspage.com/videos.htm"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_onenewspagegermany">OneNewspage (Deutschland)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.newsdeutschland.com/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_onenewspagegermanyvideo">OneNewspage Video (Deutschland)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.newsdeutschland.com/videos.htm"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_minism">Minisucher SUMA-Lab</label></div></div></div></div>
  <div class="headingGroup nachrichten">
    <h3>Nachrichten:
      <small><a data-type="nachrichten" class="checker"> (alle an-/abw&auml;hlen)</a></small></h3>
    <div class="row">
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_nebelfeed">Netluchs Twitter</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.netluchs.de/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_onenewspage">OneNewspage</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.onenewspage.com/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_onenewspagevideo">OneNewspage (Video)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.onenewspage.com/videos.htm"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_onenewspagegermany">OneNewspage (Deutschland)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.newsdeutschland.com/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_onenewspagegermanyvideo">OneNewspage Video (Deutschland)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.newsdeutschland.com/videos.htm"></a></div></div></div></div>
  <div class="headingGroup wissenschaft">
    <h3>Wissenschaft:
      <small><a data-type="wissenschaft" class="checker"> (alle an-/abw&auml;hlen)</a></small></h3>
    <div class="row">
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_tuhh">TUBdok</label><a class="glyphicon glyphicon-link" target="_blank" href="https://tubdok.tub.tuhh.de/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_BASE">BASE</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.base-search.net/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_MetaGer-QIP">MetaGer-QIP</label></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_minism">Minisucher SUMA-Lab</label></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_pubmed">PubMed</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/"></a></div></div></div></div>
  <div class="headingGroup produktsuche">
    <h3>Produktsuchen:
      <small><a data-type="produktsuche" class="checker"> (alle an-/abw&auml;hlen)</a></small></h3>
    <div class="row">
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_ebay">Ebay</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.ebay.de/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_mg_produkt">MetaGer Produktsuche</label></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_mg_produkt2">MetaGer Produktsuche</label></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_ecoshopper">Ecoshopper</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.ecoshopper.de/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_similar_product">Ladenpreis</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.ladenpreis.net/"></a></div></div></div></div>
  <div class="headingGroup others">
    <h3>Andere Suchmaschinen:
      <small><a data-type="others" class="checker"> (alle an-/abw&auml;hlen)</a></small></h3>
    <div class="row">
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_allesklar">AllesKlar</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.allesklar.de/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_dmozint">dmoz international</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.dmoz.org/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_harvest">CampusSearch</label></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_wikis">Wikis</label></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_bing">Bing</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.bing.com/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_fportal">Forschung</label></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_suchticker">Suchticker</label></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_tauchen">OpenCrawl (Tauchen)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.opencrawl.de/opencrawl/search.jsp?subcollection=tauchen&amp;query=&amp;hitsPerPage=10&amp;hitsPerSite=1"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_regengergie">OpenCrawl (Regenerative Energien)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.opencrawl.de/opencrawl/search.jsp?subcollection=ernenerg"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_astronomie">OpenCrawl (Astronomie)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.opencrawl.de/opencrawl/search.jsp?subcollection=astronom"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_goyax">B&ouml;rse &amp; Finanzen (goyax)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.goyax.de/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_stackexchange">Stackoverflow (Q&amp;A)</label><a class="glyphicon glyphicon-link" target="_blank" href="https://stackoverflow.com/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_dart">DartEurope</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.dart-europe.eu/basic-search.php"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_beammachine">Beam Machine</label></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_onenewspageAll">OneNewspage (mit Archiv)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.onenewspage.com/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_onenewspagegermanyAll">OneNewspage (Deutschland, mit Archiv)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.newsdeutschland.com/"></a></div></div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="param_loklak">loklak</label><a class="glyphicon glyphicon-link" target="_blank" href="http://loklak.org/"></a></div></div></div></div>
  <input id="unten" type="submit" class="btn btn-primary" value="Startseite f&uuml;r einmalige Nutzung generieren">
  <input type="button" class="btn btn-primary hidden" id="save" value="Einstellungen dauerhaft speichern">
  <input type="button" class="btn btn-danger hidden" id="reset" value="Einstellungen Zur&uuml;cksetzen"></form>
@endsection
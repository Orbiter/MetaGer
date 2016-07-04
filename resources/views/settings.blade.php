@extends('layouts.subPages')

@section('title', $title )

@section('content')
	<form action="/" method="get">
		<h1>{{ trans('settings.head.1') }}</h1>
		<p id="lead">{{ trans('settings.head.2') }} <a href="#unten">{{ trans('settings.head.3') }}</a> {{ trans('settings.head.4') }}</p>
		<h2>{{ trans('settings.allgemein.1') }}</h2>
		<input type="hidden" name="focus" value="angepasst">
		<div class="checkbox">
			<label><input type="checkbox" name="param_sprueche">{{ trans('settings.allgemein.2') }}</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="param_tab">{{ trans('settings.allgemein.3') }}</label>
		</div>
		<label class="select-label">{{ trans('settings.allgemein.4') }}:</label>
		<select class="form-control" name="param_lang">
			<option value="all">{{ trans('settings.allgemein.5') }}</option>
			<option value="de">{{ trans('settings.allgemein.6') }}</option>
		</select>
		<label class="select-label">{{ trans('settings.allgemein.7') }}:</label>
		<select class="form-control" name="param_resultCount">
			<option value="10">10</option>
			<option value="20" selected>20</option>
			<option value="50">50</option>
			<option value="100">100</option>
			<option value="0">{{ trans('settings.allgemein.8') }}</option>
		</select>
		<label class="select-label">{{ trans('settings.allgemein.9') }}:</label>
		<select class="form-control" name="param_time">
			<option value="1000" selected>1 {{ trans('settings.allgemein.10') }}</option>
			<option value="2000">2 {{ trans('settings.allgemein.11') }}</option>
			<option value="5000">5 {{ trans('settings.allgemein.11') }}</option>
			<option value="10000">10 {{ trans('settings.allgemein.11') }}</option>
			<option value="20000">20 {{ trans('settings.allgemein.11') }}</option>
		</select>
		<h2>{{ trans('settings.suchmaschinen.1') }}</h2>
		<div class="headingGroup web">
			<h3>{{ trans('settings.suchmaschinen.2') }}:
				<small><a data-type="web" class="checker"> ({{ trans('settings.suchmaschinen.3') }})</a></small></h3>
			<div class="row">
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_fastbot">Fastbot</label><a class="glyphicon glyphicon-link" target="_blank" href="https://www.fastbot.de/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_wikipedia">Wikipedia</label><a class="glyphicon glyphicon-link" target="_blank" href="https://de.wikipedia.org/wiki/Wikipedia:Hauptseite"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_witch">Witch</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.witch.de/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_dmoz">Dmoz</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.dmoz.de/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_overture">Yahoo</label><a class="glyphicon glyphicon-link" target="_blank" href="https://de.yahoo.com/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_yacy">YaCy</label><a class="glyphicon glyphicon-link" target="_blank" href="http://yacy.de/de/index.html"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_nebel">Netluchs</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.netluchs.de/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_exalead">Exalead</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.exalead.com/search/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_BASE">BASE</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.base-search.net/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_yandex">Yandex</label><a class="glyphicon glyphicon-link" target="_blank" href="https://www.yandex.com/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_zeitde">Die ZEIT</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.zeit.de/index"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_onenewspage">OneNewspage</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.onenewspage.com/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_onenewspagevideo">OneNewspage ({{ trans('settings.suchmaschinen.4') }})</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.onenewspage.com/videos.htm"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_onenewspagegermany">OneNewspage ({{ trans('settings.suchmaschinen.5') }})</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.newsdeutschland.com/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_onenewspagegermanyvideo">OneNewspage Video ({{ trans('settings.suchmaschinen.5') }})</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.newsdeutschland.com/videos.htm"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_minism">Minisucher SUMA-Lab</label>
					</div>
				</div>
			</div>
		</div>
		<div class="headingGroup nachrichten">
			<h3>{{ trans('settings.suchmaschinen.6') }}:
				<small><a data-type="nachrichten" class="checker"> ({{ trans('settings.suchmaschinen.3') }})</a></small></h3>
			<div class="row">
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_nebelfeed">Netluchs Twitter</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.netluchs.de/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_onenewspage">OneNewspage</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.onenewspage.com/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_onenewspagevideo">OneNewspage ({{ trans('settings.suchmaschinen.4') }})</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.onenewspage.com/videos.htm"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_onenewspagegermany">OneNewspage ({{ trans('settings.suchmaschinen.5') }})</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.newsdeutschland.com/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_onenewspagegermanyvideo">OneNewspage Video ({{ trans('settings.suchmaschinen.5') }})</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.newsdeutschland.com/videos.htm"></a>
					</div>
				</div>
			</div>
		</div>
		<div class="headingGroup wissenschaft">
			<h3>{{ trans('settings.suchmaschinen.7') }}:
				<small><a data-type="wissenschaft" class="checker"> ({{ trans('settings.suchmaschinen.3') }})</a></small></h3>
			<div class="row">
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_tuhh">TUBdok</label><a class="glyphicon glyphicon-link" target="_blank" href="https://tubdok.tub.tuhh.de/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_BASE">BASE</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.base-search.net/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_MetaGer-QIP">MetaGer-QIP</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_minism">Minisucher SUMA-Lab</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_pubmed">PubMed</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/"></a>
					</div>
				</div>
			</div>
		</div>
		<div class="headingGroup produktsuche">
			<h3>{{ trans('settings.suchmaschinen.8') }}:
				<small><a data-type="produktsuche" class="checker"> ({{ trans('settings.suchmaschinen.3') }})</a></small></h3>
			<div class="row">
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_ebay">Ebay</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.ebay.de/"></a>
					</div>
					</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_mg_produkt">MetaGer {{ trans('settings.suchmaschinen.9') }}</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_mg_produkt2">MetaGer {{ trans('settings.suchmaschinen.9') }}</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_ecoshopper">Ecoshopper</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.ecoshopper.de/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_similar_product">Ladenpreis</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.ladenpreis.net/"></a>
					</div>
				</div>
			</div>
		</div>
		<div class="headingGroup others">
			<h3>{{ trans('settings.suchmaschinen.10') }}:
				<small><a data-type="others" class="checker"> ({{ trans('settings.suchmaschinen.3') }})</a></small></h3>
			<div class="row">
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_allesklar">AllesKlar</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.allesklar.de/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_dmozint">dmoz international</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.dmoz.org/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_harvest">CampusSearch</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_wikis">Wikis</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_bing">Bing</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.bing.com/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_fportal">Forschung</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_suchticker">Suchticker</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_tauchen">OpenCrawl ({{ trans('settings.suchmaschinen.11') }})</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.opencrawl.de/opencrawl/search.jsp?subcollection=tauchen&amp;query=&amp;hitsPerPage=10&amp;hitsPerSite=1"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_regengergie">OpenCrawl ({{ trans('settings.suchmaschinen.12') }})</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.opencrawl.de/opencrawl/search.jsp?subcollection=ernenerg"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_astronomie">OpenCrawl ({{ trans('settings.suchmaschinen.13') }})</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.opencrawl.de/opencrawl/search.jsp?subcollection=astronom"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_goyax">{{ trans('settings.suchmaschinen.14') }} (goyax)</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.goyax.de/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_stackexchange">Stackoverflow (Q&amp;A)</label><a class="glyphicon glyphicon-link" target="_blank" href="https://stackoverflow.com/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_dart">DartEurope</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.dart-europe.eu/basic-search.php"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_beammachine">Beam Machine</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_onenewspageAll">OneNewspage ({{ trans('settings.suchmaschinen.15') }})</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.onenewspage.com/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_onenewspagegermanyAll">OneNewspage ({{ trans('settings.suchmaschinen.16') }})</label><a class="glyphicon glyphicon-link" target="_blank" href="http://www.newsdeutschland.com/"></a>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<div class="checkbox">
						<label><input type="checkbox" name="param_loklak">loklak</label><a class="glyphicon glyphicon-link" target="_blank" href="http://loklak.org/"></a>
					</div>
				</div>
			</div>
		</div>
		<input id="unten" type="submit" class="btn btn-primary" value="Startseite f&uuml;r einmalige Nutzung generieren">
		<input type="button" class="btn btn-primary hidden" id="save" value="Einstellungen dauerhaft speichern">
		<input id="plugin" type="submit" class="btn btn-primary" value="Plugin mit diesen Einstellungen generieren.">
		<input type="button" class="btn btn-danger hidden" id="reset" value="Einstellungen Zur&uuml;cksetzen">
	</form>
@endsection
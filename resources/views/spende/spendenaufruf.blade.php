@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
<h1>Mit Ihnen zusammen: Für die unabhängige, nicht-kommerzielle Internet-Suche</a></h1>
<p>
Den Quellcode von MetaGer erreichen Sie ab sofort unter <a href="https://gitlab.metager3.de/open-source/MetaGer" target="_blank">https://gitlab.metager3.de/open-source/MetaGer</a>
</p>Mit Ihnen zusammen haben wir in den vergangenen Monaten eine neue
Erfolgsstory geschrieben: die Zahl der MetaGer-Nutzer steigt weiter und
stetig.  Es ist kein Strohfeuer kurzfristigen Erfolges, sondern es ist die
Stetigkeit dieser Steigerung, die unseren Erfolg begründet.  In drei Jahren
haben wir unsere Abfragezahlen verdreifacht.  Wir sind nach Expertenmeinung
die sicherste Suchmaschine der Welt.  Unsere Suchergebnisse sind zielgenau
und treffsicher.  Beides verdanken wir der Tatsache, dass wir Programmierer
fest anstellen konnten.  Aber auch Programmierer können nicht allein von der
Liebe zur Sache leben: Nur dank Ihrer Spendenbereitschaft konnten wir das
finanziell stemmen.</p>
<p>
Wir mussten auch einen Prozess gegen die Ausuferung des "Rechts auf
Vergessen" (nach dem EuGH Urteil vom 13.5.2014) durchstehen, um weiterhin
einen unzensierten Betrieb von Suchmaschinen zu ermöglichen
(<a href="http://suma-ev.de/presse/Suchmaschine-MetaGer-totgeklagt.html" target="_blank">http://suma-ev.de/presse/Suchmaschine-MetaGer-totgeklagt.html</a>).  Wir haben
in allen Instanzen gewonnen.  Anschließend jedoch hat die Klägerin sich für
zahlungsunfähig erklärt.  Wir werden wohl auf den Kosten sitzenbleiben, was
unsere Finanzen weiter belastet.
</p>
<h3>
Um unsere gemeinsamen Erfolge fortzuschreiben, benötigen wir jetzt wieder
Ihre Hilfe.
</h3>
<p><a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spende") }}">{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spende") }}</a></p>
<p>
Eine Internet-Suchmaschine muss stetig weiterentwickelt werden,
wenn sie nicht binnen kurzem veraltet sein soll.  Unsere Werbeeinnahmen sind
stark gesunken.  Eigentlich wollen wir in MetaGer auch gar keine Werbung. 
Aber solange das Spendenaufkommen nicht reicht, können wir darauf noch nicht
verzichten.
</p>
<p>
Wir bitten um Ihre Hilfe, damit wir MetaGer als unabhängige Alternative in
einem gemeinnützigen Verein weiter entwickeln und betreiben können.
Große Teile des Internet versinken im Kommerz und Werbemüll.
</p>
<p>
Lassen Sie uns gemeinsam ein Zeichen gegen die zunehmende Kommerzialisierung
des Internet setzen!  Der Betreiberverein von MetaGer, der SUMA-EV, ist als
gemeinnütziger Verein seit 2004 in das Register beim Amtsgericht Hannover
unter VR200033 eingetragen.
</p>
<p>
Bitte unterstützen Sie uns, damit die unabhängige, nicht-kommerzielle Suche
im Internet weiter entwickelt und betrieben werden kann:
<br />
<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spende") }}">{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spende") }}</a>
</p>
<h3>Helfen Sie mit, dass freie Suchmaschinen im Internet frei bleiben. Das digitale Wissen der Welt muss ohne Bevormundung durch Staaten oder Konzerne frei zugänglich sein und bleiben.</h3>
<div class="">
	<div class="col-sm-6">
		<h2>{{ trans('spenden.bankinfo.1') }}</h2>
		<p style="white-space:pre;">{{ trans('spenden.bankinfo.2') }}</p>
		<p class="text-muted">{{ trans('spenden.bankinfo.3') }}</p>
	</div>
	<div class="col-sm-6">
		<div class="">
		<div class="col-md-6">
			<h2>{!! trans('spenden.logos.1') !!}</h2>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input name="cmd" value="_xclick" type="hidden">
				<input name="business" value="wsb@suma-ev.de" type="hidden">
				<input name="item_name" value="SuMa-eV Spende" type="hidden">
				<input name="buyer_credit_promo_code" value="" type="hidden">
				<input name="buyer_credit_product_category" value="" type="hidden">
				<input name="buyer_credit_shipping_method" value="" type="hidden">
				<input name="buyer_credit_user_address_change" value="" type="hidden">
				<input name="no_shipping" value="0" type="hidden">
				<input name="no_note" value="1" type="hidden">
				<input name="currency_code" value="EUR" type="hidden">
				<input name="tax" value="0" type="hidden">
				<input name="lc" value="DE" type="hidden">
				<input name="bn" value="PP-DonationsBF" type="hidden">
				<input src="/img/paypalspenden.gif" name="submit" width="120px" alt="Spenden Sie mit PayPal - schnell, kostenlos und sicher!" type="image">
			</form>
		</div>
		<div class="col-md-6">
			<h2>{!! trans('spenden.logos.2') !!}</h2>
			<a href="bitcoin:174SDRNZqM2WNobHhCDqD1VXbnZYFXNf8V"><img src="/img/WeAcceptBitcoin.png" style="width:120px" alt="Bitcoin"></a>
		</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<hr />
	<div class="col-md-6">
		<h2 id="lastschrift">{{ trans('spenden.lastschrift.1') }}</h2>
		<p>{{ trans('spenden.lastschrift.2') }}</p>
		<form role="form" method="POST" action="{{ action('MailController@donation') }}">
			{{ csrf_field() }}
			<div class="form-group" style="text-align:left;">
				<label for="Name">{{ trans('spenden.lastschrift.3') }}</label>
				<input type="text" class="form-control" id="Name" required="" name="Name" placeholder="{{ trans('spenden.lastschrift.3.placeholder') }}">
			</div>
			<div class="form-group" style="text-align:left;">
				<label for="email">{{ trans('spenden.lastschrift.4') }}</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Email">
			</div>
			<div class="form-group" style="text-align:left;">
				<label for="tel">{{ trans('spenden.lastschrift.5') }}</label>
				<input type="tel" class="form-control" id="tel" name="Telefon" placeholder="xxxx-xxxxx">
			</div>
			<div class="form-group" style="text-align:left;">
				<label for="iban">{{ trans('spenden.lastschrift.6') }}</label>
				<input type="text" class="form-control" id="iban" required="" name="Kontonummer" placeholder="IBAN">
			</div>
			<div class="form-group" style="text-align:left;">
				<label for="bic">{{ trans('spenden.lastschrift.7') }}</label>
				<input type="text" class="form-control" id="bic" required="" name="Bankleitzahl" placeholder="BIC">
			</div>
			<div class="form-group" style="text-align:left;">
				<label for="msg">{{ trans('spenden.lastschrift.8') }}</label>
				<textarea class="form-control" id="msg" required="" name="Nachricht" placeholder="{{ trans('spenden.lastschrift.8.placeholder') }}"></textarea>
			</div>
			<button type="submit" class="btn btn-default">{{ trans('spenden.lastschrift.9') }}</button>
		</form>
		<p>{{ trans('spenden.lastschrift.10') }}</p>
	</div>
	<div class="col-md-6">
		<h2 id="mails">Aus den EMails vorheriger Spender:</h2>
		<ul style="text-align:left; list-style-type: initial;">
			<li>"Danke, dass es metager gibt."</li>
			<li>"Ich (85J.) möchte für Ihre aufwändige Arbeit 200 Euro spenden. Bleibt stark gegen die Kraken."</li>
  			<li>"Ihre Arbeit halte ich für sehr wertvoll"</li>
  			<li>"Danke für Ihre gute Arbeit!"</li>
  			<li>"Super das neue MetaGer!"</li>
  			<li>"Suchmaschine wie von Ihnen entwickelt und betrieben ist sehr begrüßenswert.  Meine Spende dazu"</li>
  			<li>"Als kleinen Beitrag für Ihre große und großartige Arbeit spende ich"</li>
  			<li>"Bitte buchen Sie 100,-EUR für Ihre gute Arbeit ab."</li>
  			<li>"Gerade in der heutigen Zeit braucht es eine Suchmaschine aus sicherer Hand und guten Absichten."</li>
  			<li>"Ihre Arbeit ist Spitze. Deshalb möchte Ihr Projekt fördern."</li>
  			<li>"Ich verwende schon seit Jahren Metager und danke mit einer Spende"</li>
  			<li>"MetaGer ist Spitze! Ich spende"</li>
  			<li>"Armer Rentner spendet gerne 5,00 Euro"</li>
  			<li>"Ich verwende fast nur noch die MetaGer-Suche und bin damit sehr zufrieden"</li>
  			<li>"Danke für euer Werk!"</li>
		</ul>
	</div>
</div>
<div id="left" class="col-lg-6 col-md-12 col-sm-12 others">
		
		
	</div>
@endsection

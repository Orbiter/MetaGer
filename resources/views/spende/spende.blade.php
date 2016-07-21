@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
<h1>{{ trans('spenden.headline.1') }}</h1>
<h2>{{ trans('spenden.headline.2') }}</h2>
<div class="col">
	<div id="left" class="col-lg-6 col-md-12 col-sm-12 others">
		<h2>{{ trans('spenden.bankinfo.1') }}</h2>
		<p>{{ trans('spenden.bankinfo.2') }}</p>
		<p class="text-muted">{{ trans('spenden.bankinfo.3') }}</p>
		<hr>
		<div class="col-lg-6 col-md-12 col-sm-12 others ppbc">
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
		<div class="col-lg-6 col-md-12 col-sm-12 others ppbc">
			<h2>{!! trans('spenden.logos.2') !!}</h2>
			<a href="bitcoin:174SDRNZqM2WNobHhCDqD1VXbnZYFXNf8V"><img src="/img/WeAcceptBitcoin.png" style="width:120px" alt="Bitcoin"></a>
		</div>
		<div class="clearfix"></div>
		<hr>
		<h2 id="lastschrift">{{ trans('spenden.lastschrift.1') }}</h2>
		<p>{{ trans('spenden.lastschrift.2') }}</p>
		<form role="form" method="POST">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="Name">{{ trans('spenden.lastschrift.3') }}</label>
				<input type="text" class="form-control" id="Name" required="" name="Name" placeholder="{{ trans('spenden.lastschrift.3.placeholder') }}">
			</div>
			<div class="form-group">
				<label for="email">{{ trans('spenden.lastschrift.4') }}</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Email">
			</div>
			<div class="form-group">
				<label for="tel">{{ trans('spenden.lastschrift.5') }}</label>
				<input type="tel" class="form-control" id="tel" name="Telefon" placeholder="xxxx-xxxxx">
			</div>
			<div class="form-group">
				<label for="iban">{{ trans('spenden.lastschrift.6') }}</label>
				<input type="text" class="form-control" id="iban" required="" name="Kontonummer" placeholder="IBAN">
			</div>
			<div class="form-group">
				<label for="bic">{{ trans('spenden.lastschrift.7') }}</label>
				<input type="text" class="form-control" id="bic" required="" name="Bankleitzahl" placeholder="BIC">
			</div>
			<div class="form-group">
				<label for="msg">{{ trans('spenden.lastschrift.8') }}</label>
				<textarea class="form-control" id="msg" required="" name="Nachricht" placeholder="{{ trans('spenden.lastschrift.8.placeholder') }}"></textarea>
			</div>
			<button type="submit" class="btn btn-default">{{ trans('spenden.lastschrift.9') }}</button>
		</form>
		<p>{{ trans('spenden.lastschrift.10') }}</p>
	</div>
	<div class="col-lg-6 col-md-12 col-sm-12 others" id="right">
		<h2>{{ trans('spenden.about.0') }}</h2>
		<p>{{ trans('spenden.about.1.1') }}
			<a href="https://metager.de/klassik/bform1.htm" target="_blank">{{ trans('spenden.about.1.2') }}</a></p>
		<p>{{ trans('spenden.about.2.1') }} <a href="http://suma-ev.de/" target="_blank">SUMA-EV</a> {{ trans('spenden.about.2.2') }} <a href="http://suma-ev.de/suma-links/index.html#sponsors" target="_blank">{{ trans('spenden.about.2.3') }}</a> {{ trans('spenden.about.2.4') }} <a href="https://metager.de/klassik/spenden1.html" target="_blank">{{ trans('spenden.about.2.5') }}</a></p>
		<p><a href="http://suma-ev.de/unterstuetzung/index.html" target="_blank">{{ trans('spenden.about.3.1') }}</a> {{ trans('spenden.about.3.2') }} <a href="https://metager.de/" target="_blank">MetaGer.de!</a></p>
		<p>{{ trans('spenden.about.4.1') }} <a href="https://www.boost-project.com/de" target="_blank">www.boost-project.com</a> {{ trans('spenden.about.4.2') }} <a href="https://metager.de/" target="_blank">MetaGer.de!</a> {{ trans('spenden.about.4.3') }} <a href="https://www.boost-project.com/de/shops?charity_id=1129&amp;tag=bl" target="_blank">{{ trans('spenden.about.4.4') }}</a> {{ trans('spenden.about.4.5') }}</p>
	</div>
	<div class="clearfix"></div>
</div>
@endsection

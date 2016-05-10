@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>Ihre Spende f&uuml;r SUMA-EV und MetaGer</h1>
<h2>Helfen Sie mit, dass freie Suchmaschinen im Internet frei bleiben. Das digitale Wissen der Welt muss ohne Bevormundung durch Staaten oder Konzerne frei zug&auml;nglich sein und bleiben.</h2>
<div class="col">
<div id="left" class="col-lg-6 col-md-12 col-sm-12 others">
<h2>Durch eine &Uuml;berweisung</h2>
<p>SUMA-EV
IBAN: DE64 4306 0967 4075 0332 01
BIC: GENODEM1GLS
(Konto-Nr.: 4075 0332 01, BLZ: 43060967)
GLS Gemeinschaftsbank, Bochum</p>
            <p class="text-muted">Falls Sie eine Spendenbescheinigung w&uuml;nschen,
geben Sie auf dem &Uuml;berweisungsformular bitte Ihre
vollst&auml;ndige Adresse, und (sofern vorhanden) auch Ihre EMail-Adresse an.</p>
            <hr>
            <div class="col-lg-6 col-md-12 col-sm-12 others ppbc">
              <h2>Bequem mit Paypal
              </h2>
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
              <h2>oder Bitcoins
              </h2>
              <a href="bitcoin:174SDRNZqM2WNobHhCDqD1VXbnZYFXNf8V">
                <img src="/img/WeAcceptBitcoin.png" style="width:120px" alt="Bitcoin">
              </a>
            </div>
            <div class="clearfix">
            </div>
            <hr>
            <h2 id="lastschrift">Spenden mittels elektronischem Lastschriftverfahren:
            </h2>
            <p>Tragen Sie hier Ihre Kontodaten sowie den gew&uuml;nschten Betrag ein. Wir buchen dann entsprechend von Ihrem Konto ab.</p>
            <form role="form" method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="Name">Bitte geben Sie ihren Namen ein:
                </label>
                <input type="text" class="form-control" id="Name" required="" name="Name" placeholder="Name">
              </div>
              <div class="form-group">
                <label for="email">Ihre E-Mail Adresse:
                </label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
              </div>
              <div class="form-group">
                <label for="tel">Ihre Telefonnummer, um Ihre Spende ggf. durch einen R&uuml;ckruf zu verifizieren:
                </label>
                <input type="tel" class="form-control" id="tel" name="Telefon" placeholder="xxxx-xxxxx">
              </div>
              <div class="form-group">
                <label for="iban">Ihre IBAN oder Kontonummer:
                </label>
                <input type="text" class="form-control" id="iban" required="" name="Kontonummer" placeholder="IBAN">
              </div>
              <div class="form-group">
                <label for="bic">Ihre BIC oder Bankleitzahl:
                </label>
                <input type="text" class="form-control" id="bic" required="" name="Bankleitzahl" placeholder="BIC">
              </div>
              <div class="form-group">
                <label for="msg">Hier k&ouml;nnen Sie uns formlos mitteilen, welchen Betrag Sie spenden m&ouml;chten und ggf. noch eine Mitteilung dazu senden:
                </label>
                <textarea class="form-control" id="msg" required="" name="Nachricht" placeholder="Nachricht"></textarea>
              </div>
              <button type="submit" class="btn btn-default">Spenden
              </button>
            </form>
            <p>Ihre Daten werden &uuml;ber eine verschl&uuml;sselte Verbindung zu uns &uuml;bertragen und k&ouml;nnen von Dritten nicht mitgelesen werden. Der Betrag, den Sie angegeben haben, wird dann durch uns von Ihrem Konto abgebucht. SUMA-EV verwendet Ihre Daten ausschlie&szlig;lich f&uuml;r die Spendenabrechnung; Ihre Daten werden nicht weitergegeben. Spenden an den SUMA-EV sind steuerlich absetzbar, da der Verein vom Finanzamt Hannover Nord als gemeinn&uuml;tzig anerkannt ist, eingetragen in das Vereinsregister beim Amtsgericht Hannover unter VR200033. Eine Bescheinigung f&uuml;r Einzelspenden oberhalb 200,-EUR wird automatisch zugesandt. Bei Spenden bis 200,-EUR gen&uuml;gt der Kontoauszug f&uuml;r die Absetzbarkeit beim Finanzamt.
            </p>
          </div>
          <div class="col-lg-6 col-md-12 col-sm-12 others" id="right">
            <h2>&Uuml;ber uns
            </h2>
            <p>Die deutsche Suchmaschine MetaGer wird betrieben und stetig weiterentwickelt vom SUMA-EV - Verein f&uuml;r freien Wissenszugang. Wir sind ein gemeinn&uuml;tzig anerkannter Verein, eines unserer Ziele ist die Entwicklung und der Betrieb von Suchmaschinen. Wir erhalten keine &ouml;ffentlichen F&ouml;rdergelder und sind daher auf Ihre Spenden angewiesen. Wir haben unsere Werbung stark reduziert, denn wir vertrauen auf IHRE Unterst&uuml;tzung.
              Helfen Sie mit, dass freie Suchmaschinen im Internet erhalten bleiben und weiterentwickelt werden!
              Das k&ouml;nnen Sie mit einer Spende auf dieser Seite tun. Oder, wenn Sie freie Suchmaschinen auch langfristig sichern wollen:
              <a href="https://metager.de/klassik/bform1.htm" target="_blank">Werden Sie Mitglied im SUMA-EV
              </a>
            </p>
            <p>Wenn Sie 50,-EUR oder mehr spenden, oder Mitglied im 
              <a href="http://suma-ev.de/" target="_blank">SUMA-EV
              </a> werden, dann k&ouml;nnen Sie, wenn Sie wollen, auf unserer 
              <a href="http://suma-ev.de/suma-links/index.html#sponsors" target="_blank">Mitglieder- und Sponsorenseite 
              </a>namentlich mit Link auf Ihre Homepage (sofern vorhanden) genannt werden (wenn Sie dies w&uuml;nschen, vermerken Sie es bitte in Ihrer Mitteilung) 
              <a href="https://metager.de/klassik/spenden1.html" target="_blank">Oder werden Sie SUMA-EV F&ouml;rderer!
              </a>
            </p>
            <p>
              <a href="http://suma-ev.de/unterstuetzung/index.html" target="_blank">JEDE Form Ihrer Unterst&uuml;tzung 
              </a>hilft mit, dass freie Suchmaschinen und freier Wissenszugang im Internet eine Chance haben. Zum freien Wissenszugang geh&ouml;rt es auch, dass Ihre Daten weder &uuml;berwacht, noch Ihre Internet-Adressen und Verbindungsdaten gespeichert werden. Bei uns wird Ihre Internet-Adresse bereits w&auml;hrend der Suche anonymisiert, sie wird nicht gespeichert und nicht an Dritte weitergegeben.Freie Internet-Suche ohne &Uuml;berwachung: 
              <a href="https://metager.de/" target="_blank">MetaGer.de!
              </a>
            </p>
            <p>Eine weitere M&ouml;glichkeit, MetaGer zu f&ouml;rdern, besteht darin, dass Sie Ihren n&auml;chsten Online-Einkauf bei MetaGer-F&ouml;rdershops machen. Damit wir auf diesem Weg unterst&uuml;tzt werden k&ouml;nnen, haben wir uns in das Netzwerk zur F&ouml;rderung gemeinn&uuml;tzig anerkannter Organisationen eingebracht, das Projekt 
              <a href="https://www.boost-project.com/de" target="_blank">www.boost-project.com
              </a>. Unter dem Dach dieses Projektes sind ca. 400 Online-Shops (von Amazon bis Zooplus) vereint, die sich bereit erkl&auml;rt haben, von allen Verk&auml;ufen etwa 6% an das Projekt zu spenden. Statt wie bisher direkt zum Online-Shop zu surfen, gehen Sie zun&auml;chst auf 
              <a href="https://metager.de/" target="_blank">MetaGer.de!
              </a> und klicken dort unterhalb der Suchwort-Eingabebox auf 
              <a href="https://www.boost-project.com/de/shops?charity_id=1129&amp;tag=bl" target="_blank">Machen Sie Ihre Eink&auml;ufe bei MetaGer-F&ouml;rdershops - klicken Sie hier!
              </a> Dieser Klick f&uuml;hrt Sie in die Shop-Auswahl des Boost-Projektes. Dort suchen Sie sich Ihren Shop aus und machen wie gewohnt Ihren Einkauf. Das ist alles. Wenn genug Menschen dies tun, dann brauchen wir keine Werbung mehr. Nur zwei Mausklicks f&uuml;r Sie - f&uuml;r alle eine Chance f&uuml;r den freien Wissenszugang in der digitalen Welt. 
            </p>
          </div>
          <div class="clearfix">
          </div>
        </div>
@endsection
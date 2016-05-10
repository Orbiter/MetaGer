@extends('layouts.subPages')

@section('title', $title )

@section('content')
        <h1>Datenschutz und Privatsph&auml;re</h1>
        <p>Datenschutz und Privatsph&auml;re geh&ouml;ren f&uuml;r uns zu den wichtigsten G&uuml;tern im Internet. Sie sind absolut sch&uuml;tzenswert und d&uuml;rfen keinesfalls kommerziell genutzt werden. Im Folgenden eine kurze Auflistung unserer Vorgehensweise. Eine ausf&uuml;hrliche Darstellung mit Hintergrund-Informationen, warum wir die einzige wirklich sichere Suchmaschine betreiben, finden Sie <a href="https://suma-ev.de/presse/Sicher-suchen-UND-finden-mit-MetaGer.html">hier</a></p>
        <h2>Unsere Vorgehensweise/Policy:</h2>
        <ul id="privacyList"><li>Wir speichern weder Ihre IP-Adresse, noch den <a href="http://www.heise.de/security/meldung/Fingerprinting-Viele-Browser-sind-ohne-Cookies-identifizierbar-1982976.html" target="_blank">"Fingerabdruck" Ihres Browsers</a> (der Sie mit hoher Wahrscheinlichkeit ebenfalls eindeutig identifizieren k&ouml;nnte).</li>
          <li>Wir setzen keine Cookies oder benutzen Tracking-Pixel oder &auml;hnliche Technologien, um unsere Nutzer zu "tracken" (tracken = Verfolgen der Bewegungen im Internet).</li>
          <li>Die Daten&uuml;bertragung von MetaGer erfolgt ausschlie&szlig;lich automatisch verschl&uuml;sselt &uuml;ber das https-Protokoll.</li>
          <li>Wir bieten einen Zugang &uuml;ber das anonyme TOR-Netzwerk, den 
            <a href="http://forum.suma-ev.de/viewtopic.php?f=3&amp;t=43&amp;sid=c994b628153235dfef098ba6fea3d60e" target="_blank">MetaGer-TOR-hidden Service.</a></li>
          <li>Da der Zugang &uuml;ber das TOR-Netzwerk vielen Nutzern kompliziert erscheint, manchmal auch langsam ist, haben wir einen weiteren Weg implementiert, auch die Ergebnis-Webseiten ebenfalls anonym erreichen zu k&ouml;nnen: durch Anklicken des Links "anonym &ouml;ffnen". Dadurch sind Ihre pers&ouml;nlichen Daten beim Klick auf MetaGer-Ergebnisse und sogar bei allen Folge-Klicks danach gesch&uuml;tzt.</li>
          <li>Wir machen m&ouml;glichst wenig Werbung, kennzeichnen diese klar und eindeutig, und vertrauen f&uuml;r unsere Finanzierung auf unsere Nutzer, Ihre <a href="/spende/">Spenden</a> und Mitgliedsbetr&auml;ge zum <a href="http://suma-ev.de/" target="_blank">SUMA-EV</a></li>
          <li>MetaGer wird von der deutschen gemeinn&uuml;tzigen Organisation <a href="http://suma-ev.de/" target="_blank">SUMA-EV</a> in Zusammenarbeit mit der <a href="http://www.uni-hannover.de/" target="_blank">Leibniz Universit&auml;t Hannover</a> betrieben und weiterentwickelt.</li>
          <li>Unsere Server stehen ausschlie&szlig;lich in Deutschland. Sie unterliegen damit vollst&auml;ndig deutschem Datenschutzrecht, welches als das h&auml;rteste der Welt gilt.</li>
          <li>Nach den <a href="http://www.heise.de/newsticker/meldung/Bericht-US-Regierung-zapft-Kundendaten-von-Internet-Firmen-an-1884264.html" target="_blank">Enth&uuml;llungen von Edward Snowden im Juni 2013</a> positionierten sich etliche Suchmaschinen mit der Selbstbeschreibung, dass Suchen bei ihnen sicher sei, weil die IP-Adressen der Nutzer nicht gespeichert w&uuml;rden. So ehrenwert und auch ehrlich gemeint diese Selbstbeschreibungen sein m&ouml;gen - Fakt ist, dass viele dieser Suchmaschinen zumindest einen Teil ihrer Server in den USA hosten. Das gilt auch f&uuml;r diejenigen, die von manchen Datensch&uuml;tzern immer noch als "besonders empfehlenswert" gelobt und als Empfehlung verbreitet werden. Denn diese Suchmaschinen <a href="http://de.wikipedia.org/wiki/USA_PATRIOT_Act" target="_blank">unterliegen nach dem Patriot Act und US-Recht dem vollen Zugriff der dortigen Beh&ouml;rden.</a> Sie k&ouml;nnen also gar keine gesch&uuml;tzte Privatsph&auml;re bieten (selbst dann nicht, wenn sie selber sich noch so sehr darum bem&uuml;hen).</li></ul>
        <h2>Was andere &uuml;ber unser Privacy-Konzept auf Twitter sagen:</h2>
        <pre><p>&gt; 7.4.2014 C. Schulzki-Haddouti @kooptech
&gt; MetaGer d&uuml;rfte im Moment die sicherste Suchmaschine weltweit sein</p>
<p>&gt; 8.4.2014 Stiftung Datenschutz @DS_Stiftung
&gt; Wenn das Suchergebnis anonym bleiben soll: @MetaGer, die gemeinn&uuml;tzige
&gt; Suchmaschine aus #Hannover</p>
<p>&gt; 8.4.2014 Markus K&auml;kenmeister @markus2009
&gt; Suchmaschine ohne Tracking</p>
<p>&gt; 8.4.2014 Marko [~sHaKaL~] @mobilef0rensics Nice; anonymous Search and find
&gt; with MetaGer</p>
<p>&gt; 7.4.2014 Anfahrer @anfahrer
&gt; Websuche mit #Datenschutz dank #MetaGer : Anonyme Suche und
&gt; Ergebnisse via Proxy</p>
<p>&gt; 8.4.2014 stupidit&eacute; pue @dummheitstinkt
&gt; wow, is this the MetaGer I used in the end 90s in internet cafes???
&gt; "Anonymes Suchen und Finden mit MetaGer | heise"</p></pre>
@endsection
# MetaGer

[MetaGer](https://metager.de) ist eine datenschutzfreundliche und freie Meta-Suchmaschine.

## Abhängigkeiten
* composer (https://getcomposer.org/)
* php7.0
  * php7.0-mbstring
  * php7.0-dom
  * php7.0-xml
* sqlite3
* redis-server
* Das Perl-Paket: Lingua::Identify (http://search.cpan.org/~ambs/Lingua-Identify-0.56/lib/Lingua/Identify.pm)

## MetaGer zu langsam?
Damit MetaGer so schnell wird, wie auf unserem Live-Server, erfordert es ein wenig Konfigurationsarbeit. Der Grund, warum die Version nach dem Checkout langsamer als normal ist, ist der, dass die eingestellten Suchmaschinen im Standard synchron abgefragt werden.
Das heißt, dass bei einer Suche mit 20 Suchmaschinen eine  Suchmaschine nach der anderen abgefragt wird.
Die parallele abarbeitung kann mit Hilfe von Laravels Queue-System ( https://laravel.com/docs/5.2/queues ) hergestellt werden.
Im Standard, ist in der Datei ".env" QUEUE_DRIVER=sync gesetzt.
Wir verwenden auf unseren Servern den QUEUE_DRIVER=redis und haben mit Hilfe von Supervisor ( https://laravel.com/docs/5.2/queues#supervisor-configuration ) eine Menge queue:worker Prozesse am laufen, die für eine parallele bearbeitung sorgen.

## Offizielle Dokumentation

Die Dokumentation ist im Wiki des Gitlab-Projektes zu finden.

## Beiträge

Vielen Dank, dass du erwägst, zu MetaGer beizutragen!
Leider sind wir noch nicht bereit, Änderungen von außen aufzunehmen.
Es steht dir jedoch frei, ein Ticket zu eröffnen.

## Sicherheitslücken

Falls du eine Sicherheitslücke findest oder dir etwas unsicher vorkommt,
zögere bitte nicht ein Ticket zu schreiben oder eine Mail an [office@suma-ev.de](mailto:office@suma-ev.de) zu senden.

## Lizenzen

Der MetaGer-eigene Code, sofern nicht anders anders angegeben, steht unter der [AGPL-Lizenz Version 3](https://www.gnu.org/licenses/agpl-3.0).

Eine Liste der Projekte, auf denen MetaGer basiert, und deren Lizenzen sind in der Datei LICENSE zu finden. 

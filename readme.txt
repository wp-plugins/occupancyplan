=== occupancyplan ===
Contributors: x000x
Donate link: http://www.gods4u.de/sponsoren/
Tags: occupancy plan, occupancyplan, occupancy, Belegungsplan, Belegungsuebersicht, Ferienwohnung, Ferienhaus, Kalender
Requires at least: 2.7.1 works only with !! PHP5 !! or higher
Tested up to: 2.7.1
Stable tag: trunk

Occupancy Plan ist ein einfaches Plugin fuer Wordpress, das eine Uebersicht von z.B. belegten Ferienwohnungen oder Ferienhaeuser kalendarisch anzeigt.

== Description ==

Occupancy Plan ist ein einfaches Plugin fuer Wordpress, das eine Uebersicht von z.B. belegten Ferienwohnungen oder Ferienhaeuser kalendarisch anzeigt.
Viele graphische Einstellungen fuer Farbe und Formen, sowie das Eintragen der belegten Tage im Kalender koennen ganz leicht und Benutzerfreundlich vom Admin vorgenommen werden. Des weiteren koennen - unabhaengig von einander - mehrere Uebersichten im Blog dargestellt werden. Somit lassen sich Belegungsplaene, beispielsweise auch fuer mehrere Objekte wie bei Pensionen ueblich, einpflegen.

== Installation ==
Requires PHP5 or higher! (Siehe unten)

1. Das Plugin: Wordpress occupancyplan herunterladen.
2. Die so erhaltene Zip-Datei: occupancyplan.zip entpacken.
3. Nun die enthaltenen PHP-Dateien in das Wordpress Plugin Verzeichnis (/wp-content/plugins/occupancyplan/) hochladen.
4. Im Administrationsbreich von Wordpress unter Plugins das Plugin occupancyplan aktivieren.
5. Zu Einstellungen => Belegungsplan wechseln und dort die diverse Einstellungen vornehmen.

Betreffend PHP4 habe ich von [Till folgenden Hinweis](http://www.gods4u.de/wp-plugin-occupancyplan/#comment-46 "Dateien mit PHP5 parsen") erhalten:
Bei vielen Hosting-Providern (z. B. 1&1) ist es möglich, die php-Dateien mit PHP5 zu parsen, anstatt dem (standardmäßig konfigurierten) parsen via PHP4-Interpreter. 
- Dies wird in der Regel durch einen ergänzenden Eintrag in der .htaccess (anzulegen im root-Verzeichnis) erreicht:

`# Switch to PHP5
AddType x-mapp-php5 .php
AddHandler x-mapp-php5 .php`

== Uninstall ==
1. Im Administrationsbreich von Wordpress unter Plugins das Plugin occupancyplan deaktivieren.
2. Sollen die Datenbanktabellen ebenfalls verschwinden, dann mit z.B. phpAdmin die entsprechende Datenbank wählen und folgende Tabellen löschen:
- !!! ACHTUNG !!!
- Damit gehen auch alle bereits eingetragenen Daten (vom Belegungsplan) verloren.`

- *belegung_config
- *belegung_daten
- *belegung_objekte
- Die Sterne sind durch die Einstellungen in ihrer wp-config.php ($table_prefix) zu ersetzen.

== Screenshots ==

1. Eine beispielhafte Darstellung der Uebersicht (wordpress_plugin_occupancyplan.jpg)

== Frequently Asked Questions ==

= Was muss ich in meine Seite/Artikel einfügen, damit der Belegungsplan erscheint? =
Ihr müsst die Seite/Artikel in der HTML-Ansicht (NICHT Grafisch) zum bearbeiten öffnen und den folgenden Text eintragen:
   <!-- belegungsplan 1 --> Dabei steht die Zahl für den Belegungsplan, der angezeigt werden soll.
   
= Ich habe das Plugin installiert, doch es funktioniert nicht, bzw. es wird nichts angezeigt. =
Bitte stellt sicher, dass auf eurem Server PHP ab Version 5 läuft.

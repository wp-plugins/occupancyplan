=== occupancyplan ===
Contributors: x000x
Donate link: http://www.gods4u.de/sponsoren/
Tags: occupancy plan, occupancyplan, occupancy, Belegungsplan, Belegungsuebersicht, Ferienwohnung, Ferienhaus, Kalender
Requires at least: 2.7.1 and PHP5
Tested up to: 4.0.
Stable tag: 1.0.2.6

Occupancy Plan ist ein einfaches Plugin fuer Wordpress, das eine Übersicht von z.B. belegten Ferienwohnungen oder Ferienhaeuser kalendarisch anzeigt.

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

Folgenden Hinweis habe ich von Till erhalten:
Bei vielen Hosting-Providern (z. B. 1&1) ist es möglich, die php-Dateien mit PHP5 zu parsen, anstatt dem (standardmäßig konfigurierten) parsen via PHP4-Interpreter. 
- Dies wird in der Regel durch einen ergänzenden Eintrag in der .htaccess (anzulegen im root-Verzeichnis) erreicht:

`# Switch to PHP5
AddType x-mapp-php5 .php
AddHandler x-mapp-php5 .php`

== Screenshots ==

1. Eine beispielhafte Darstellung der Uebersicht (wordpress_plugin_occupancyplan.jpg)

2. Anzeige in der Pluginübersicht

3. Menü - Belegungsplan

== Frequently Asked Questions ==

= Was muss ich in meine Seite/Artikel einfügen, damit der Belegungsplan erscheint? =
Ihr müsst die Seite/Artikel in der HTML-Ansicht (NICHT Grafisch) zum bearbeiten öffnen und den folgenden Text eintragen:
   &lt;!-- belegungsplan 1 --&gt; Dabei steht die Zahl für den Belegungsplan, der angezeigt werden soll.
   
= Ich habe das Plugin installiert, doch es funktioniert nicht, bzw. es wird nichts angezeigt. =
Bitte stellt sicher, dass auf eurem Server PHP ab Version 5 läuft.

== Changelog ==
-   1.0.0.0
   <p>Korrekturen an der readme.txt (Links korrigiert); Screenshot hinzugefuegt</p>
   <p>Korrekturen an der readme.txt (FAQ); Links korrigiert</p>
-   1.0.1.0
   <p>Plugin URI geändert</p>
   <p>BUGFIX occupancy_plan_options.php - Wenn mehr als ein Plan pro Seite angezeigt werden sollte, hat dies incht funktioniert</p>
-   1.0.2.0
   <p>BUGFIXes</p>
   <p></p>
-   1.0.2.1
   <p>Aktualisieren von Buchungen funktioniert jetzt</p>
   <p></p>
-   1.0.2.2
   <p>Eintragen von Buchungen bei weiteren Plaenen funktioniert ab dieser Version.</p>
   <p>Vor- und Zurueck-Button im Dashboard funktioniert bei jedem Kalender</p>
   <p>Plugin mit Wordpress-Version 3.0 erfolgreich getestet</p>
-   1.0.2.3
   <p>Anzeige des Kalenders bei Themes wie TwentyTen u.a. korrigiert</p>
   <p>Widget-Funktionalität hinzugefügt</p>
   <p>Plugin mit Wordpress-Version 3.1.1. erfolgreich getestet</p>
   <p></p>
-   1.0.2.4
   <p>kleinere Anpassungen</p>
   <p></p>
-   1.0.2.5
   <p>ENT_HTML401 nach 'ENT_HTML401' korrigiert</p>
   <p></p>
-   1.0.2.6
   <p>Übersetzungen korrigiert</p>
   <p></p>

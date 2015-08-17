#WPF 2015 Projekt 

Galerie zum WPF Moderne Webanwendungen mit CSS3, HTML5, PHP

#How to Install

1. Alle Dateien auf einen geeigneten Webserver mit SQL5.6.19 und PHP5.5.14 Unterstützung hochladen.
2. Die Datei db.sql in eine MySQL-Datenbank importieren. 
3. Die Zugangsdaten der Datenbank in die Datei config.php im Ordner "includes" hinzufügen:

private	$host = "localhost << jeweils zwischen den Klammern ändern";

private $user = "user";

private $pass = "pass";

private $dbname = "dbname";

Sollte jetzt laufen!

(Nicht getestet mit XAMPP)

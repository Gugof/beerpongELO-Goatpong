<?php

//**this file get the ELO from one Player the Database**//
// Überprüfen ob über GET gesendet wurde.
if (isset($_GET["ELObest"])) {

    // Den Zeichensatz über header() senden,
    // sonst werden Umlaute ggf. nicht richtig angezeigt.
    header('Content-Type: text/plain; charset=utf-8');

    // Eine Verbindung zur Datenbank aufbauen
    $verbindung = new PDO("mysql:host=localhost;dbname=goatpong", "root", "root");

    // Anweisung definieren
    $kommando = $verbindung->prepare("SELECT `BestELO`
                                   FROM `player`
                                   WHERE `ID` =  :ELObest");

    // Den Platzhalter in der Anweisung mit dem Suchbegriff ersetzen
    $kommando->bindValue(':ELObest', $_GET["ELObest"]);


    // Die vorbereitete Anweisung ausführen
    $kommando->execute();

    // Datensätze holen
    $datensaetze = $kommando->fetchAll(PDO::FETCH_OBJ);

    // Überprüfen ob Datensätze gefunden wurden
    if (count($datensaetze) > 0) {

        // Alle gefundenen Datensätze ausgeben
        foreach ($datensaetze as $datensatz) {
            echo '<p>' .$datensatz->BestELO.'</p>';
        }
    }
    else {
        echo 'Keine Datensätze gefunden!';
    }
}
?>
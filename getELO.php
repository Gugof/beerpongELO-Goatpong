<?php

//**this file get the ELO from one Player the Database**//
// Überprüfen ob über GET gesendet wurde.
if (isset($_GET["ELOP2_1"])) {

    // Den Zeichensatz über header() senden,
    // sonst werden Umlaute ggf. nicht richtig angezeigt.
    header('Content-Type: text/plain; charset=utf-8');

    // Eine Verbindung zur Datenbank aufbauen
    $verbindung = new PDO("mysql:host=localhost;dbname=goatpong", "root", "root");

    // Anweisung definieren
    $kommando = $verbindung->prepare("SELECT `ELO`
                                   FROM `player`
                                   WHERE `ID` =  :ELOP2_1");

    // Den Platzhalter in der Anweisung mit dem Suchbegriff ersetzen
    $kommando->bindValue(':ELOP2_1', $_GET["ELOP2_1"]);


    // Die vorbereitete Anweisung ausführen
    $kommando->execute();

    // Datensätze holen
    $datensaetze = $kommando->fetchAll(PDO::FETCH_OBJ);

    // Überprüfen ob Datensätze gefunden wurden
    if (count($datensaetze) > 0) {

        // Alle gefundenen Datensätze ausgeben
        foreach ($datensaetze as $datensatz) {
            echo '<p>' . $datensatz->ELO.'</p>';
        }
    }
    else {
        echo 'Keine Datensätze gefunden!';
    }
}
?>
<?php
session_start();
require_once('../db.php');
// if ( isset($_REQUEST['id']) and $_REQUEST['id'] != "") 
// {
//   $_SESSION['id'] = $_REQUEST['id'];
// }
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Mike Coustic - Gitarrist</title>
<meta name="description" content="Veranstaltungstermine von Mike Coustic. Zusätzlich Hörbeispiele.">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<!-- eigene CSS-Anweisungen -->
<link href="design.css" rel="stylesheet">
</head>
<body>
<!-- Hier kommt die loeschen-Seite -->
<div data-role="page" id="loeschen" data-theme="b">
  <?php anzeige_kopfbereich('Rangliste', false); ?>
  <div data-role="main" class="ui-content">
    <?php
    if ( isset($_SESSION['eingeloggt']) )
    {
      if ( isset($_GET['aktion']) and $_GET['aktion'] == 'loeschen' 
           and $_GET['id'] > 0
      )
      {
        $id = (INT) $_GET['id'];
        $loeschen = $db->prepare("DELETE FROM player WHERE id=(?) LIMIT 1");
        $loeschen->bind_param('i', $id);
        if ($loeschen->execute()) {
            echo "<h1>Datensatz Nr. ". $id." wurde gelöscht</h1>";
        }   
      }
      else
      {
        echo '<h1>Soll der folgende Termin WIRKLICH gelöscht werden?</h1>';
        $id = (INT) $_REQUEST['id'];
        $sql = "SELECT * FROM player WHERE id = '$id' ";
        if ($erg = $db->query($sql)) {
          $datensatz = $erg->fetch_object();
        }
        echo '<p>Jetzt <a href="loeschen.php?id='. $id .'&aktion=loeschen">';
        echo date("d.m.Y", strtotime($datensatz->datum)); 
        echo ' endgültig löschen</a> - bitte nicht kopflos nutzen!';
        echo '<p><i>Datum:</i><br>';
        echo date("d.m.Y", strtotime($datensatz->datum)); 
        echo '</p>';
        echo '<p><i>Beginn:</i><br>';
        echo $datensatz->beginn; 
        echo '</p>';
        echo '<p><i>Ort:</i><br>';
        echo $datensatz->ort; 
        echo '</p>';
        echo '<p><i>Anmerkung:</i><br>';
        echo $datensatz->anmerkung; 
        echo '</p>';
      }
    }
    else
    {
      echo "<h1>Bitte einloggen</h1>";
    }
    ?>
  </div>
  <?php anzeige_fussbereich('', false); ?>
</div>
</body>
</html>
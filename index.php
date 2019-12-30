<?php
//
//     Author:  Christopher Biller
//     e-mail:  christopherbiller@web.de
//     Github:  https://github.com/Gugof
//
//****This is a little Webapplication realized with HTML, PHP, Javascript and JQueryMobile. For the Backend it used a common MariaDB instance****//
//****The Purpose is to give the popular Partygame Beerpong an more competitiv charackter with an ELO Pointsystem like in chess or tabletennis****//
//****Its just a Fun Project i worked for myself and its not complete programmed in good practice code Style****//
session_start();
require_once('db.php');

?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>GOATHOUSEPONG</title>
<!-- <meta name="viewport" content="width=1024"> for static view on mobile devices-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css"
     rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="jquery.tablesort.js"></script><link rel="stylesheet" href="sortable-theme-dark.css" />
    <script src="sortable/sortable.min.js"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <!-- eigene CSS-Anweisungen -->

    <!-- Loader Circle -->
<link href="design.css" rel="stylesheet">

    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 30px;
            height:30px;
            -webkit-animation: spin 2s linear infinite; /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<script>
    var Spielzeit;

    var Player1_ID;
    var Player1;
    var Player1ELO;
    //var Player1BestELO;

    var Player2_ID ;
    var Player2;
    var Player2ELO;
    //var Player2BestELO;

    var Player3_ID ;
    var Player3 ;
    var Player3ELO;
    //var Player3BestELO;

    var Player4_ID ;
    var Player4 ;
    var Player4ELO;
    //var Player4BestELO;

    var T1ELO;
    var T2ELO;
    var TrefferT1 ;
    var TrefferT2 ;
    var Gewinner ;

    var T1ELO_new;
    var T2ELO_new;

    var Player1ELO_new;
    var Player2ELO_new;
    var Player3ELO_new;
    var Player4ELO_new;

    var T1Erwartungswert;
    var T2Erwartungswert;
</script>
<body>


<!-- Hier kommt die Startseite -->
<div data-role="page" id="startseite" data-theme="b">
  <?php anzeige_kopfbereich('startseite'); ?>

  <div data-role="main" class="ui-content">
    <h1>Goathousepong - Webapp</h1>

    <p>Dein Beerpong Elo-System für jedes Goathouse</p>

       
	<div data-role="controlgroup" data-type="horizontal">
        <input id="btn_spielen" type="submit" name="btn_spielen" onclick="spielen();" value="Spielen"/>
		<a href="#turniermodus" class="ui-btn">Turniermodus</a>
	</div>

      <div data-role="controlgroup" data-type="horizontal">
          <a href="#Rangliste" class="ui-btn">Rangliste</a>

          <a href="#Allespieler" class="ui-btn">Alle Spieler</a>
      </div>

      <div data-role="controlgroup" data-type="horizontal">
          <a href="#Gamelog" class="ui-btn">SpieleLog</a>
          <a href="#Auslosen" class="ui-btn">Auslosen</a>
      </div>
      <h4>Zur besseren Darstellung wird auf mobilen Geräten die Darstellung im Querformat empfohlen! </h4>
	 <h2>How does it work?</h2>
      <p>Diese Webapp fügt dem klassischen Beerpong ein ELO-System wie z.B. beim Schach oder Tischtennis hinzu.</p>
      <p>Ein neuer Spieler startet mit 1000 ELO Punkten. Wenn man gegen Gegner gewinnt bekommt man ELO Punkte hinzu,</p>
      <p>wenn man verliert, werden welche abgezogen. Gewinnt man gegen stärkere Gegner bekommt man entsprechen mehr Punkte,
      <p>genauso verliert man aber mehr wenn man gegen einen schwächeren Gegner verliert.</p>
      <p>Weil man Beerpong aber in Zweierteams spielt, werden hier der Durchschnitt der Teampartner errechnet.</p>
      <p>Dieser Spielt dann gegen den Durchschnitt des Gegners.</p>
      <p>Wer wissen will wie das ELO genau berechnet wird kann ans Ende der Seite schauen.</p>

      <?php
      $pdo = new PDO('mysql:host=localhost;dbname=goatpong', 'root', 'root');

      $statement = $pdo->prepare("SELECT * FROM gamelog");
      $statement->execute();
      $anzahl_user = $statement->rowCount();
      echo "<h2 style=color:red>Es wurden bis jetzt $anzahl_user Spiele gespielt</h2>";
      ?>


      <?php
      $sqlELO = "SELECT * FROM player ORDER BY ELO DESC LIMIT 3";
      if ($ergELO = $db->query($sqlELO)) {
          while ($datensatzELO = $ergELO->fetch_object()) {
              $datenELO[] = $datensatzELO;
          }
      }
      ?>

      <h2>TOP 3 ELO</h2>
      <table id="TOP3ELO" >
          <thead>
          <tr>
              <th>Platzierung</th>
              <th>Name</th>
              <th>ELO</th>

            </tr>
          <tr>
          <?php
          foreach ($datenELO as $keyELO => $inhalt) {
              ?>
                <td class="tabellentext">
                    <?php if($keyELO == 0){ ?>
                    <img src="bilder/Beerpong_gold.png" alt="" border="3" height="100" width="100" />
                    <?php } ?>
                    <?php if($keyELO == 1){ ?>
                        <img src="bilder/Beerpong_silber.png" alt="" border="3" height="100" width="100" />
                    <?php } ?>
                    <?php if($keyELO == 2){ ?>
                        <img src="bilder/Beerpong_bronze.png" alt="" border="3" height="100" width="100" />
                    <?php } ?>
                </td>

                  <td class="tabellentext">
                      <?php echo $inhalt->Name; ?>
                  </td>
                  <td class="tabellentext">
                      <?php echo $inhalt->ELO; ?>
                  </td>
              </tr>
              <?php
          }
          ?>
          </thead>
      </table>

      <?php
      $sqlGAMES = "SELECT * FROM player ORDER BY Games DESC LIMIT 3";
      if ($ergGAMES = $db->query($sqlGAMES)) {
          while ($datensatzGAMES = $ergGAMES->fetch_object()) {
              $datenGAMES[] = $datensatzGAMES;
          }
      }
      ?>
      <h2>Most Games</h2>
      <table id="TOP3GAMES" >
          <thead>
          <tr>
              <th>Platzierung</th>
              <th>Name</th>
              <th>Games</th>
              <th>ELO</th>

          </tr>
          <tr>
              <?php
              foreach ($datenGAMES as $keyGAMES => $inhalt) {
              ?>
              <td class="tabellentext">
                  <?php if($keyGAMES == 0){ ?>
                      <img src="bilder/Beerpong_gold.png" alt="" border="3" height="100" width="100" />
                  <?php } ?>
                  <?php if($keyGAMES == 1){ ?>
                      <img src="bilder/Beerpong_silber.png" alt="" border="3" height="100" width="100" />
                  <?php } ?>
                  <?php if($keyGAMES == 2){ ?>
                      <img src="bilder/Beerpong_bronze.png" alt="" border="3" height="100" width="100" />
                  <?php } ?>
              </td>

              <td class="tabellentext">
                  <?php echo $inhalt->Name; ?>
              </td>
              <td class="tabellentext">
                  <?php echo $inhalt->Games; ?>
              </td>
              <td class="tabellentext">
                  <?php echo $inhalt->ELO; ?>
              </td>
          </tr>
          <?php
          }
          ?>
          </thead>
      </table>

      <?php
      $sqlWL = "SELECT * FROM player ORDER BY Games DESC LIMIT 3";
      if ($ergWL = $db->query($sqlWL)) {
          while ($datensatzWL = $ergWL->fetch_object()) {
              $datenWL[] = $datensatzWL;
          }

      }
      ?>
      <h2>Top 3 Win/Lose %</h2>
      <table id="TOPWINLOSE" >
          <thead>
          <tr>
              <th>Platzierung</th>
              <th>Name</th>
              <th>Win/Lose</th>
              <th>ELO</th>

          </tr>
          <tr>
              <?php
              foreach ($datenWL as $keyWL => $inhalt) {
              ?>
              <td class="tabellentext">
                  <?php if($keyWL == 0){ ?>
                      <img src="bilder/Beerpong_gold.png" alt="" border="3" height="100" width="100" />
                  <?php } ?>
                  <?php if($keyWL == 1){ ?>
                      <img src="bilder/Beerpong_silber.png" alt="" border="3" height="100" width="100" />
                  <?php } ?>
                  <?php if($keyWL == 2){ ?>
                      <img src="bilder/Beerpong_bronze.png" alt="" border="3" height="100" width="100" />
                  <?php } ?>
              </td>

              <td class="tabellentext">
                  <?php echo $inhalt->Name; ?>
              </td>
              <td class="tabellentext">
                  <?php

                  if($inhalt->Games == 0){
                      $WL = 0;
                  }
                  else{$WL = round(($inhalt->Win / $inhalt->Games) * 100); }

                  echo ''.$WL.'%'; ?>
              </td>
              <td class="tabellentext">
                  <?php echo $inhalt->ELO; ?>
              </td>
          </tr>
          <?php
          }
          ?>
          </thead>
      </table>
      <h2>Berechnung des ELO's</h2>
      <p>Beim ELO wird zuerst ein Erwartungswert berechnet. Dabei hat ein Spieler der mehr Punkte hat auch einen höheren Erwartungswert. </p>
      <p>Die Formel für den Erwartungswert lautet:</p>
      <p>Erwartungswert_Spieler1 = 1/(1+10^((ELO_Spieler2 - ELO_Spieler1)/400))</p>
      <p>Wenn beide z.B. einen Elowert von 1000 haben, ist der Erwartungswert für beide Spieler 0.5</p>
      <p>Mit diesem Erwartungswert kann man nun den neuen Elowert berechnen: </p>
      <p>neuesELO_Gewinner = altesELO_Gewinner + k * (1 - Erwartungswert_Gewinner )</p>
      <p>neuesELO_Verlierer = altesELO_Verlierer + k * (0 - Erwartungswert_Verlierer )</p>
      <p>Dabei ist k die Gewichtung wie stark sich das Ergebnis auf die neue Zahl aufwirkt.</p>
      <p>Hier ist der Faktor k auf 80 gestellt. Dieser ist sehr hoch angesetzt was darauf zurückzuführen ist das die bekommenen/verlorenen Punkte auf zwei Spieler aufgeteilt werden müssen.</p>
      <p>Bei unserem Beispiel würde der Gewinner +40 und der Verlierer -40 bekommen.</p>
      <p>Diese werden geteilt damit beide Spiele eines Teams die gleichen Punkte bekommen/verlieren. Somit würde jeder Gewinner +20 und jeder Verlierer -20 bekommen.</p>
      <p>Wer noch genaueres wissen will kann auch auf <a href="https://de.wikipedia.org/wiki/Elo-Zahl ">Wikipedia</a> nachschauen.</p>


  </div>
    <script>

        function  spielen() {


            window.location.href = "http://192.168.178.26/#Spielen";

        }
    </script>
  
</div>



<!-- Hier kommt die Spielezusammenfassung -->
<div data-role="page" id="spielzusammenfassung" data-theme="b">


    <h1>Spielzusammenfassung</h1>

    <h2>Team 1</h2>
    <table>
        <div class="ui-field-contain">
            <td>
                <label id="label_spieler1">Spieler 1</label>
                <label id="label_spieler1_ELO">ELO</label>
                <label id="label_spieler2">Spieler 2</label>
                <label id="label_spieler2_ELO">ELO</label>
                <label id="label_T1_ELO">Team ELO</label>
            </td>
            <td>
                <label id="spieler1">.</label>
                <label id="spieler1_ELO">.</label>
                <label id="spieler2">.</label>
                <label id="spieler2_ELO">.</label>
                <label id="T1_ELO">.</label>
            </td>


        </div>
    </table>

    <h2>Team 2</h2>
    <table>
        <div class="ui-field-contain">
            <td>
                <label id="label_spieler3">Spieler 3</label>
                <label id="label_spieler3_ELO">ELO</label>
                <label id="label_spieler4">Spieler 4</label>
                <label id="label_spieler4_ELO">ELO</label>
                <label id="label_T2_ELO">Team ELO</label>
            </td>
            <td>
                <label id="spieler3">.</label>
                <label id="spieler3_ELO">.</label>
                <label id="spieler4">.</label>
                <label id="spieler4_ELO">.</label>
                <label id="T2_ELO">.</label>
            </td>
        </div>
    </table>
    <h2>Ergebnis</h2>
    <table>
        <div class="ui-field-contain">
            <td>
                <label id="label_Gewinner">Gewinner</label>
                <label id="label_TrefferT1">Treffer Team 1</label>
                <label id="label_TrefferT2">Treffer Team 2</label>
                <label id="label_neuesELO_P1">Spieler 1 Neues ELO</label>
                <label id="label_neuesELO_P2">Spieler 2 Neues ELO</label>
                <label id="label_neuesELO_P3">Spieler 3 Neues ELO</label>
                <label id="label_neuesELO_P4">Spieler 4 Neues ELO</label>
            </td>
            <td>
                <label id="Gewinner">.</label>
                <label id="TrefferT1">.</label>
                <label id="TrefferT2">.</label>
                <label id="neuesELO_P1">.</label>
                <label id="neuesELO_P2">.</label>
                <label id="neuesELO_P3">.</label>
                <label id="neuesELO_P4">.</label>
            </td>
            <td>
                <label id="platzalter1">.</label>
                <label id="platzhalter2">.</label>
                <label id="platzhalter3">.</label>
                <label id="diffP1">.</label>
                <label id="diffP2">.</label>
                <label id="diffP3">.</label>
                <label id="diffP4">.</label>
            </td>
        </div>
    </table>
    <input id="btn_back" type="submit" name="btn_back" onclick="back();" value="Zurück zum Hauptmenü"/>

    <script>

        function  back() {
            window.location.href = "http://192.168.178.26/#startseite";
            window.location.reload();


        }
    </script>
</div>

<!-- Hier kommen Allepspieler -->

<?php
$sql = "SELECT * FROM player ORDER BY ELO DESC";
if ($erg = $db->query($sql)) {
    while ($datensatz = $erg->fetch_object()) {
        $daten[] = $datensatz;
    }
}
?>

<div data-role="page" id="Allespieler" data-theme="b">
  <?php anzeige_kopfbereich('Rangliste'); ?>
  <div data-role="main" class="ui-content">
    <h1>Alle Spieler</h1>
      <p>Hier kann man nach allen Spielern suchen. Es werden Statistiken wie z.B. Siege, ELO, Treffer und Gewinnquote angezeigt.</p>
      <p>Außerdem kann man Spieler hinzufügen und löschen. Dies ist passwortgeschützt und nur Admins vorenthalten.</p>

	<div class="ui-field-contain">
	
		<table>
			<tr>
				<td width="45%">
					
					<input type="button" class="button" name="btn_insert" id="btn_insert" value="Spieler hinzufügen" />					
        			<input type="text" name="txt_insert" id="txt_insert" value="">
					<label for="label_insert">Bitte Name des Spielers eingeben, der hinzugefügt werden will</label>
				</td>
				<td width="45%">
					<input type="button" class="button" name="btn_deletet" id="btn_delete" value="Spieler löschen" />			
					<input type="text" name="txt_delete" id="txt_delete" value="">
					<label for="label_delete">Bitte ID des Spielers eingeben, der gelöscht werden soll</label>
				</td>				
           </tr>
        </table>				
    </div>

  

<script>

    $(document).ready( function () {
        $.extend( $.fn.dataTable.defaults, {
            searching: false,

        } );
        $('#allTable').DataTable( {
            paging: false
        } );
        $('#allTable').DataTable();

    } );
$(document).ready(function() {		
    $("#btn_insert").click(function(){
		 var inputVal = document.getElementById("txt_insert").value;

		 if(inputVal == "")
         {
             alert("keine Eingabe!");
         }
		 else {
             $.ajax({
                 type: "POST",
                 url: 'addplayer.php',
                 data: {message: inputVal},
                 success: function (data
                 ) {
                     window.location.reload()
                     alert(data);
                 },


                 error: function () {

                     alert("es ist ein Fehler aufgetreten");
                 }
             });
         }
    });

});

<?php
$pw = 'floriangpunktvonderfrau' ;  //bad practice never use pw in cleatext in javasrcipt but for this purpose sufficient
?>

$(document).ready(function() {		
    $("#btn_delete").click(function(){
		 var deleteVal = document.getElementById("txt_delete").value;
         var pw = <?php echo json_encode($pw); ?>;


        if(deleteVal == ""){
            alert("keine Eingabe!");
        }
        else {
            Check = prompt('Geben Sie das Passwort zum löschen ein', '');
            if (Check == pw) {


                $.ajax({
                    type: "POST",
                    url: 'deleteplayer.php',
                    data: {message: deleteVal},
                    success: function (data) {
                        window.location.reload()
                        alert(data);
                    },


                    error: function () {

                        alert("es ist ein Fehler aufgetreten!");
                    }
                });


            } else {
                alert("Falsches Passwort");
            }
        }
    });

});


</script>
	<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names..">
      <table id="allTable" data-role="table" class="ui-responsive" data-mode="reflow" data-column-btn-text="Spalten" >
      <thead>
        <tr>

          <th data-priority="1">Name</th>
          <th data-priority="1">ELO</th>
		  <th data-priority="1">Games Played</th>
          <th data-priority="1">Win</th>
          <th data-priority="1">Win/Lose</th>
          
          <th data-priority="1">Treffer</th>
		  <th data-priority="1">Gegentreffer</th>
          <th data-priority="1">T/GT</th>
          <th data-priority="1">ID</th>

        </tr>
      </thead>
      <tbody>

    <?php
    $count = 1;
    foreach ($daten as $inhalt) {
    ?>
        <tr>
            <td class="tabellentext">
                <?php echo $inhalt->Name; ?>
            </td>
			<td class="tabellentext">
                <?php echo $inhalt->ELO; ?>
            </td>
            <td class="tabellentext">
                <?php echo $inhalt->Games; ?>
            </td>

            <td class="tabellentext">
                <?php echo $inhalt->Win; ?>
            </td>
            <td class="tabellentext">
                <?php

                if($inhalt->Games == 0){
                    $WL = 0;
                }
                else{$WL = round(($inhalt->Win / $inhalt->Games) * 100); }

                echo ''.$WL.'%'; ?>
            </td>


            <td class="tabellentext">
                <?php echo $inhalt->Treffer; ?>
            </td>
			<td class="tabellentext">
                <?php echo $inhalt->Gegentreffer; ?>
            </td>
            <td class="tabellentext">
                <?php

                $TGT = $inhalt->Treffer - $inhalt->Gegentreffer;
                if($TGT >= 0){echo '+'.$TGT.'';}
                else{echo ''.$TGT.'';}
                ?>
            </td>
            <td class="tabellentext">
                <?php echo $inhalt->ID; ?>
            </td>
      </tr>
    <?php
    }
    ?>

      </tbody>
    </table>
	
	<script>


		function myFunction() {
		// Declare variables

		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput");
		filter = input.value.toUpperCase();
		table = document.getElementById("allTable");
		tr = table.getElementsByTagName("tr");

		// Loop through all table rows, and hide those who don't match the search query
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[1];
			if (td) {
				txtValue = td.textContent || td.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
					} else {
						tr[i].style.display = "none";
      }
    }
  }
}
</script>

  </div>
  
</div>
<!-- Hier kommt die Rangliste -->

<?php
$sqlrang = "SELECT * FROM player WHERE Games > 5 ORDER BY ELO DESC ";
if ($ergrang = $db->query($sqlrang)) {
    while ($datensatzrang = $ergrang->fetch_object()) {
        $datenrang[] = $datensatzrang;
    }
}
?>

<div data-role="page" id="Rangliste" data-theme="b">
    <?php anzeige_kopfbereich('Rangliste'); ?>
    <div data-role="main" class="ui-content">
        <h1>Rangliste</h1>
        <p>Hier ist die Rangliste für alle eingestuften Spieler</p>
        <p>Um ihr aufzutauchen muss man mindestens 5 Spiele gespielt haben.</p>




        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names..">
        <table id="rankTable" data-role="table" class="ui-responsive" data-mode="reflow" data-column-btn-text="Spalten" >
            <thead>
            <tr>
                <th data-priority="1">Rang</th>
                <th data-priority="1">Name</th>
                <th data-priority="1">ELO</th>
                <th data-priority="1">Games Played</th>
                <th data-priority="1">Win</th>
                <th data-priority="1">Win/Lose</th>

                <th data-priority="1">Treffer</th>
                <th data-priority="1">Gegentreffer</th>
                <th data-priority="1">T/GT</th>
                <th data-priority="1">ID</th>

            </tr>
            </thead>
            <tbody>

            <?php
            $countrang = 1;
            foreach ($datenrang as $inhaltrang) {
                ?>
                <tr>
                    <td class="tabellentext">
                        <?php echo $countrang;
                        $countrang++; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhaltrang->Name; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhaltrang->ELO; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhaltrang->Games; ?>
                    </td>

                    <td class="tabellentext">
                        <?php echo $inhaltrang->Win; ?>
                    </td>
                    <td class="tabellentext">
                        <?php

                        if($inhaltrang->Games == 0){
                            $WLrang = 0;
                        }
                        else{$WLrang = round(($inhaltrang->Win / $inhaltrang->Games) * 100); }

                        echo ''.$WLrang.'%'; ?>
                    </td>


                    <td class="tabellentext">
                        <?php echo $inhaltrang->Treffer; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhaltrang->Gegentreffer; ?>
                    </td>
                    <td class="tabellentext">
                        <?php

                        $TGTrang = $inhaltrang->Treffer - $inhaltrang->Gegentreffer;
                        if($TGTrang >= 0){echo '+'.$TGTrang.'';}
                        else{echo ''.$TGTrang.'';}
                        ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhaltrang->ID; ?>
                    </td>
                </tr>
                <?php
            }
            ?>

            </tbody>
        </table>

        <script>


            function myFunction() {
                // Declare variables

                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("myInput");
                filter = input.value.toUpperCase();
                table = document.getElementById("rankTable");
                tr = table.getElementsByTagName("tr");

                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        </script>

    </div>

</div>

<!-- Hier kommt die Auslosung -->

<?php

$conn = new mysqli('localhost', 'root', 'root', 'goatpong')
or die ('Cannot connect to db');

$result = $conn->query("select name,id from player");


?>

<div data-role="page" id="Auslosen" data-theme="b">
    <?php anzeige_kopfbereich('Rangliste'); ?>

    <div data-role="main" class="ui-content">
        <h1>AUSLOSEN</h1>
        <p>Wer kennt es nicht. Man hat nur einen Beerpongtisch und jeder will spielen. </p>
        <p>Mit dieser Seite kannst du alle Spieler eintragen die mitspielen wollen und danach wird ausgelost wer spielen darf.</p>
        <p>Danach wirst du mit den gesetzten Spielern zur Matchseite weitergeleitet.</p>

        <table>
            <tr>
                <td width="10%">
                    <label for="label_playernumber">Aus wieviel Spielern soll ausgewählt werden?</label>
                    <input type="text" name="txt_playernumber" id="txt_playernumber" value="">
                    <input type="button" class="button" name="btn_playernumber" id="btn_playernumber" value="Bestätigen" />
                </td>
            </tr>
        </table>


            <img src="bilder/underconstruction.png" alt="Selfhtml">
    </div>
</div>

<!-- Hier kommt die Turnierseite-->
<div data-role="page" id="turniermodus" data-theme="b">
    <?php anzeige_kopfbereich('Rangliste'); ?>

    <div data-role="main" class="ui-content">

        <img src="bilder/underconstruction.png" alt="Selfhtml">
    </div>
</div>
<!-- Hier kommt der Gamelog -->

<?php
$sql2 = "SELECT * FROM gamelog ORDER BY Timestemp DESC";
if ($erg2 = $db->query($sql2)) {
    while ($datensatz2 = $erg2->fetch_object()) {
        $daten2[] = $datensatz2;
    }
}
?>
<div data-role="page" id="Gamelog" data-theme="b">
    <?php anzeige_kopfbereich('Rangliste'); ?>

    <script>
        $(document).ready( function () {
            $.extend( $.fn.dataTable.defaults, {
                searching: false,

            } );
            $('#GamelogTable').DataTable( {
                paging: false
            } );
            $('#GamelogTable').DataTable();

        } );
    </script>
    <div data-role="main" class="ui-content">
        <h1>Gamelog</h1>
        <p>Hier werden alle Spiel mit Zeitstempel, Teilnehmern, ELO der Teams und sonst noch wichtige Statistiken gepspeichert.</p>
        <p>Zweck hiervon ist die Nachvollziehbarkeit aller Spiele. So kann sichergestellt werden das niemand "alleine" Spiele spielt oder manipuliert.</p>
        <p>Weil es eine Tabelle mit vielen Spalten ist, kann es auf einem kleinen Bildschirm zur unübersichtlichen Darstellung führen.</p>

        <?php
        $pdo = new PDO('mysql:host=localhost;dbname=goatpong', 'root', 'root');

        $statement = $pdo->prepare("SELECT * FROM gamelog");
        $statement->execute();
        $anzahl_user = $statement->rowCount();
        echo "<h2 style=color:red>Es wurden bis jetzt $anzahl_user Spiele gespielt</h2>";
        ?>

        <input type="text" id="myInput2" onkeyup="myFunction2()" placeholder="Search for anything...">
        <table id="GamelogTable" data-role="table" class="ui-responsive"  data-column-btn-text="Spalten" >
            <thead>
            <tr>
                <th data-priority="1">ID</th>
                <th data-priority="1">Timestemp</th>
                <th data-priority="1">Spieler 1</th>
                <th data-priority="1">Spieler 2</th>
                <th data-priority="1">Spieler 3</th>
                <th data-priority="1">Spieler 4</th>
                <th data-priority="1">Team 1 ELO</th>
                <th data-priority="1">Team 2 ELO</th>
                <th data-priority="1">Gewinner</th>
                <th data-priority="1">Treffer Team 1</th>
                <th data-priority="1">Treffer Team 2</th>
                <th data-priority="10">Spieler 1 ELO alt</th>
                <th data-priority="10">Spieler 1 ELo neu</th>
                <th data-priority="10">Spieler 2 ELO alt</th>
                <th data-priority="10">Spieler 2 ELo neu</th>
                <th data-priority="10">Spieler 3 ELO alt</th>
                <th data-priority="10">Spieler 3 ELo neu</th>
                <th data-priority="10">Spieler 4 ELO alt</th>
                <th data-priority="10">Spieler 4 ELo neu</th>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach ($daten2 as $inhalt) {
                ?>
                <tr>
                    <td class="tabellentext">
                        <?php echo $inhalt->ID; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Timestemp; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spieler1; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spieler2; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spieler3; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spieler4; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Team1ELO; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Team2ELO; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Gewinner; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->TrefferT1; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->TrefferT2; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spieler1_ELO; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spieler1ELO_neu; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spieler2_ELO; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spiele2ELO_neu  ; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spieler3_ELO; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spieler3ELO_neu; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spieler4_ELO; ?>
                    </td>
                    <td class="tabellentext">
                        <?php echo $inhalt->Spieler4ELO_neu; ?>
                    </td>

                </tr>
                <?php
            }
            ?>

            </tbody>
        </table>


        <script>
            function myFunction2() {
                $(document).ready(function(){
                    $("#myInput2").on("keyup", function() {
                        var value = $(this).val().toLowerCase();
                        $("#GamelogTable tr").filter(function() {
                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                        });
                    });
                });
            }
        </script>

    </div>

</div>

<!-- Hier kommt die Spieleseite -->
<div data-role="page" id="Spielen" data-theme="b">

  <?php anzeige_kopfbereich('Rangliste'); ?>
  <div data-role="main" class="ui-content">
    <h1>Spielen</h1>
      <p>Hier wird das Beerpongspiel ausgetragen! Um Fragen oder Misständen zuvorzukommen Hier eine kleine Anleitung:</p>
      <p>1. Man wählt seine Spieler aus. Man kann nur Spieler auswählen die in der Rangliste stehen. Doppelte Spieler setzen oder nur zu 2. oder 3. spielen ist nicht möglich</p>
      <p>2. Danach wählt man "Spielen".</p>
      <p>3. Sobald das Match vorbei ist trägt man das Ergebnis ein drückt auf "Spiel bestätigen". Dabei muss ein Team 10 Treffer und das andere weniger haben.</p>
      <p>4. Danach werden alle Daten ausgewertet und man gelangt zu einer Zusammenfassung.</p>
	<h2>TEAM 1</h2>	
	
	<?php

		$conn = new mysqli('localhost', 'root', 'root', 'goatpong') 
		or die ('Cannot connect to db');

		$result = $conn->query("select name,id from player");
		
	
	?>
	<table>
		<div class="ui-field-contain">
		<td>
		<h3>Player 1</h3>
		</td>
		<td>

			<select name="P1_1" id="P1_1">
                <option disabled selected value>Choose Player</option>
                <?php
                while ($row = $result->fetch_assoc()) {

                    unset($id, $name);
                    $id = $row['id'];
                    $name = $row['name'];
                    echo '<option value='.$id.'>'.$name.'</option>';
                }
                ?>
            </select>


		</td>
            <td>
                <label id="label_ELOP1">ELO</label>
                <label id="ELOP1">0</label>
            </td>
	</table>

	<table>
		<td>
		<h4>Combined ELO:</h4>
		</td>
			<td>
				<label id="T1_Combined"></label>		
			</td>
	
	
	</table>
	<?php

		$conn = new mysqli('localhost', 'root', 'root', 'goatpong') 
		or die ('Cannot connect to db');

		$result = $conn->query("select name,id from player");
	
		?>
	<table>
		<div class="ui-field-contain">
		<td>
		<h3>Player 2</h3>
		</td>
		<td>
			<select name="P2_1" id="P2_1">
            <option disabled selected value>Choose Player</option>
			<?php
				while ($row = $result->fetch_assoc()) {

                  unset($id, $name);
                  $id = $row['id'];
                  $name = $row['name'];

                    echo '<option value='.$id.'>'.$name.'</option>';
					}
					?>
			</select>
    	</div>
        <td>
            <label id="label_ELOP2">ELO</label>
            <label id="ELOP2">0</label>
        </td>

			<script>
                let ID1_1;
                let name1_1;
                let ID2_1;
                let name2_1;
                let ID3_2;
                let name3_2;
                let ID4_2;
                let name4_2
			$('#P1_1').on("change",function(){
                name1_1 =  $("option:selected", this).text();
                ID1_1 = $("option:selected", this).val();
                // Ein XMLHTTP-Request-Objekt erzeugen.
                var xhr = new XMLHttpRequest();


                // XMLHTTP-Request zur Datei: antwort.php öffnen und den Suchbegriff anhängen.
                xhr.open("GET", "getELO.php?ELOP2_1=" + ID1_1, true);

                // XMLHTTP-Request senden.
                xhr.send();

                // Auf eine Antwort warten
                xhr.onreadystatechange = function() {

                    document.getElementById("ELOP1").innerHTML =  xhr.responseText;
                    let ELOP1_1_int = Number(document.getElementById("ELOP1").textContent);
                    let ELOP2_1_int = Number(document.getElementById("ELOP2").textContent);
                    let ELO_Combined = Math.round((ELOP2_1_int + ELOP1_1_int) /2);
                    document.getElementById("T1_Combined").innerHTML =ELO_Combined;
                }
					});
			
			
			$('#P2_1').on("change",function(){
                name2_1 =  $("option:selected", this).text();
                ID2_1 = $("option:selected", this).val();

                // Ein XMLHTTP-Request-Objekt erzeugen.
                var xhr = new XMLHttpRequest();


                        // XMLHTTP-Request zur Datei: antwort.php öffnen und den Suchbegriff anhängen.
                        xhr.open("GET", "getELO.php?ELOP2_1=" + ID2_1, true);

                        // XMLHTTP-Request senden.
                        xhr.send();

                        // Auf eine Antwort warten
                        xhr.onreadystatechange = function() {

                            document.getElementById("ELOP2").innerHTML =xhr.responseText;
                            let ELOP2_1_int = Number(document.getElementById("ELOP2").textContent);
                            let ELOP1_1_int = Number(document.getElementById("ELOP1").textContent);
                            let ELO_Combined = Math.round((ELOP2_1_int + ELOP1_1_int) / 2);
                            document.getElementById("T1_Combined").innerHTML = ELO_Combined;

                        }
					});
			</script>
	</table>
	
	<table>




			<tr>
				<td>
					<img src="bilder/beer_pong_setup.png" alt="Selfhtml">
				</td>
				
				<td>
				    <input id="btn_starten" type="submit" name="btn_starten" onclick="starten();" value="Spielen"/>

                        <!--<input id="btn_stop" type="submit" name="btn_stop" onclick="stoppen();" value="Spiel beenden"/> -->
                        <h4 id="spielstatus">Spieler auswählen und starten</h4>
                        <div id="loader"class="loader" hidden></div>

                    <fieldset id="dis_bestätigen" disabled>
                        <input id="btn_bestätigen" type="submit" name="btn_bestätigen" onclick="bestätigen();" value="Spiel bestätigen"/>



                </td>

                <td>
                    <fieldset id="dis_erg" disabled>
                        <h2>Ergebnis:</h2>
                        <h4>Treffer Team 1:</h4>
                        <input type="text" name="txt_Treffer_T1" id="txt_Treffer_T1" value="">
                        <h4>Treffer Team 2:</h4>
                        <input type="text" name="txt_Treffer_T2" id="txt_Treffer_T2" value="">
                    </fieldset>
                </td>


			</tr>
	</table>


	<h2>TEAM 2</h2>	
		<?php

		$conn = new mysqli('localhost', 'root', 'root', 'goatpong') 
		or die ('Cannot connect to db');

		$result = $conn->query("select name,id from player");
	
		?>
		<table>
		<div class="ui-field-contain">
		<td>
		<h3>Player 3</h3>
		</td>
		<td>
			<select name="P3_2" id="P3_2">
                <option disabled selected value>Choose Player</option>
				<?php
				while ($row = $result->fetch_assoc()) {

                  unset($id, $name);
                  $id = $row['id'];
                  $name = $row['name']; 
                  echo '<option value='.$id.'>'.$name.'</option>';
					}
					?>
			</select>
		</td>
            <td>
                <label id="label_ELOP3">ELO</label>
                <label id="ELOP3">0</label>
            </td>
    	</div>
	</table>

	<table>
		<td>
		<h4>Combined ELO:</h4>
		</td>
			<td>
				<label id="T2_Combined"></label>		
			</td>
	
	
	</table>
		<?php

		$conn = new mysqli('localhost', 'root', 'root', 'goatpong') 
		or die ('Cannot connect to db');

		$result = $conn->query("select name,id from player");
	
		?>
		<table>
		<div class="ui-field-contain">
		<td>
		<h3>Player 4</h3>
		</td>
		<td>
			<select name="P4_2" id="P4_2">
                <option disabled selected value>Choose Player</option>
				<?php
				while ($row = $result->fetch_assoc()) {

                  unset($id, $name);
                  $id = $row['id'];
                  $name = $row['name']; 
                  echo '<option value='.$id.'>'.$name.'</option>';
					}
					?>
			</select>
		</td>
            <td>
                <label id="label_ELOP4">ELO</label>
                <label id="ELOP4">0</label>
            </td>

    	</div>
	</table>
		
		<script>
			$('#P3_2').on("change",function(){
                name3_2 =  $("option:selected", this).text();
                ID3_2 = $("option:selected", this).val();

                // Ein XMLHTTP-Request-Objekt erzeugen.
                var xhr = new XMLHttpRequest();


                // XMLHTTP-Request zur Datei: antwort.php öffnen und den Suchbegriff anhängen.
                xhr.open("GET", "getELO.php?ELOP2_1=" + ID3_2, true);

                // XMLHTTP-Request senden.
                xhr.send();

                // Auf eine Antwort warten
                xhr.onreadystatechange = function() {

                    document.getElementById("ELOP3").innerHTML = xhr.responseText;
                    let ELOP4_2_int = Number(document.getElementById("ELOP3").textContent);
                    let ELOP3_2_int = Number(document.getElementById("ELOP4").textContent);
                    let ELO_Combined = Math.round((ELOP3_2_int + ELOP4_2_int) / 2);
                    document.getElementById("T2_Combined").innerHTML = ELO_Combined;

                }
					});

			
			$('#P4_2').on("change",function(){
                name4_2 =  $("option:selected", this).text();
                ID4_2 = $("option:selected", this).val();

                // Ein XMLHTTP-Request-Objekt erzeugen.
                var xhr = new XMLHttpRequest();


                // XMLHTTP-Request zur Datei: antwort.php öffnen und den Suchbegriff anhängen.
                xhr.open("GET", "getELO.php?ELOP2_1=" + ID4_2, true);

                // XMLHTTP-Request senden.
                xhr.send();

                // Auf eine Antwort warten
                xhr.onreadystatechange = function() {

                    document.getElementById("ELOP4").innerHTML = xhr.responseText;
                    let ELOP3_2_int = Number(document.getElementById("ELOP3").textContent);
                    let ELOP4_2_int = Number(document.getElementById("ELOP4").textContent);
                    let ELO_Combined = Math.round((ELOP3_2_int + ELOP4_2_int) / 2);
                    document.getElementById("T2_Combined").innerHTML = ELO_Combined;

                }
					});
			</script>
	
		
</div>	
</div>

<script>


    function starten(){
        if (confirm("Das Spiel wirklich starten?")) {
            if(ID1_1 == ID2_1 || ID1_1 == ID3_2 || ID1_1 == ID4_2 || ID2_1 == ID3_2 || ID2_1 == ID4_2 || ID3_2 == ID4_2 ){

                alert("Doppelte oder zu wenig Spieler ausgewählt!")
            }

           else if(typeof name1_1 === "undefined" || typeof name2_1 === "undefined" || typeof name3_2 === "undefined" || typeof name4_2 === "undefined" ){
                alert("Doppelte oder zu wenig Spieler ausgewählt!")
            }
            else {



                document.getElementById("loader").removeAttribute("hidden");
                document.getElementById("spielstatus").innerText= "Spiel läuft...";

                //disable all Playeroption
                document.getElementById("P1_1").disabled = true;
                document.getElementById("P2_1").disabled = true;
                document.getElementById("P3_2").disabled = true;
                document.getElementById("P4_2").disabled = true;

                //disable startButton and enable Result inserts and confirm button
                document.getElementById("btn_starten").disabled = true;
                document.getElementById('dis_erg').removeAttribute('disabled');
                document.getElementById('dis_bestätigen').removeAttribute('disabled');
            }
        } else {

        }
    };

    function stoppen(){
        if (confirm("Das Spiel wirklich beenden?")) {
            document.getElementById('dis_erg').removeAttribute('disabled');
            document.getElementById('dis_bestätigen').removeAttribute('disabled');

        } else {

        }
    };

    function bestätigen(){
        if (confirm("Das Spiel wirklich bestätigen?")) {


            let T1 = document.getElementById("txt_Treffer_T1");
            let T2 = document.getElementById("txt_Treffer_T2");

            //check if Result possible
            if((T1.value <= 10 &&  T1.value >= 0) && (T2.value <= 10 && T2.value >= 0 )) {
                if(T1.value == 10 || T2.value == 10 )
                {

                    //----- GET BEST ELO -----//

                    //Player1//
                    // Ein XMLHTTP-Request-Objekt erzeugen.
                    /*var xhrP1 = new XMLHttpRequest();


                    // XMLHTTP-Request zur Datei: antwort.php öffnen und den Suchbegriff anhängen.
                    xhrP1.open("GET", "getBestELO.php?ELObest=" + ID1_1, true);

                    // XMLHTTP-Request senden.
                    xhrP1.send();

                    // Auf eine Antwort warten
                    xhrP1.onreadystatechange = function() {

                        Player1BestELO = xhrP1.responseText;
                        alert(Player1BestELO);
                        //Player1BestELO = Player1BestELO.replace('<p>','');
                        //Player1BestELO = Player1BestELO.replace('</p>','');


                    }

                    //Player2//
                    // Ein XMLHTTP-Request-Objekt erzeugen.
                    var xhrP2 = new XMLHttpRequest();


                    // XMLHTTP-Request zur Datei: antwort.php öffnen und den Suchbegriff anhängen.
                    xhrP2.open("GET", "getBestELO.php?ELObest=" + ID2_1, true);

                    // XMLHTTP-Request senden.
                    xhrP2.send();

                    // Auf eine Antwort warten
                    xhrP2.onreadystatechange = function() {

                        Player2BestELO = xhrP2.responseText;
                        //Player2BestELO = Player2BestELO.replace('<p>','');
                        //Player2BestELO = Player2BestELO.replace('</p>','');

                    }

                    //Player3//
                    // Ein XMLHTTP-Request-Objekt erzeugen.
                    var xhrP3 = new XMLHttpRequest();


                    // XMLHTTP-Request zur Datei: antwort.php öffnen und den Suchbegriff anhängen.
                    xhrP3.open("GET", "getBestELO.php?ELObest=" + ID3_2, true);

                    // XMLHTTP-Request senden.
                    xhrP3.send();

                    // Auf eine Antwort warten
                    xhrP3.onreadystatechange = function() {

                        Player3BestELO = xhrP3.responseText;
                        //Player3BestELO = Player3BestELO.replace('<p>','');
                        //Player3BestELO = Player3BestELO.replace('</p>','');

                    }

                    //Player4//
                    // Ein XMLHTTP-Request-Objekt erzeugen.
                    var xhrP4 = new XMLHttpRequest();


                    // XMLHTTP-Request zur Datei: antwort.php öffnen und den Suchbegriff anhängen.
                    xhrP4.open("GET", "getBestELO.php?ELObest=" + ID4_2, true);

                    // XMLHTTP-Request senden.
                    xhrP4.send();

                    // Auf eine Antwort warten
                    xhrP4.onreadystatechange = function() {

                        Player4BestELO = xhrP4.responseText;
                        //Player4BestELO = Player4BestELO.replace('<p>','');
                        // = Player4BestELO.replace('</p>','');

                    }*/

                    //----- GET BEST ELO -----//


                    //Spielzeit = document.getElementById("stopwatch").textContent;

                    Player1_ID = ID1_1;
                    Player1 = name1_1;
                    Player1ELO  = Number(document.getElementById("ELOP1").textContent);

                    Player2_ID =ID2_1;
                    Player2  = name2_1;
                    Player2ELO  = Number(document.getElementById("ELOP2").textContent);

                    Player3_ID =ID3_2;
                    Player3  = name3_2;
                    Player3ELO  = Number(document.getElementById("ELOP3").textContent);

                    Player4_ID =ID4_2;
                    Player4  = name4_2;
                    Player4ELO  = Number(document.getElementById("ELOP4").textContent);

                    T1ELO  = Number(document.getElementById("T1_Combined").textContent);
                    T2ELO  = Number(document.getElementById("T2_Combined").textContent);
                    TrefferT1  = T1.value;
                    TrefferT2  = T2.value;
                    Gewinner = "";

                    var WinP1 = 0;
                    var WinP2 = 0;
                    var WinP3 = 0;
                    var WinP4 = 0;




                    /* ----- ELO CALCULATION ----- */
                    if(T1ELO > T2ELO)
                    {
                        T1Erwartungswert = 1 / (1 +  Math.pow(10,((T2ELO-T1ELO)/400)));

                        T2Erwartungswert = 1 - T1Erwartungswert;

                    }
                    else{
                        T2Erwartungswert = 1 / (1 +  Math.pow(10,((T1ELO-T2ELO)/400)));
                        T1Erwartungswert = 1 - T2Erwartungswert;
                    }

                    if(Number(T1.value) > Number(T2.value)){
                        alert("Team 1 hat gewonnen");
                        Gewinner = "Team 1";
                        WinP1 = 1;
                        WinP2 = 1;
                        T1ELO_new = Math.round(T1ELO + 80*(1 - T1Erwartungswert));
                        T2ELO_new = Math.round(T2ELO + 80*(0 - T2Erwartungswert));

                    }
                    if(Number(T2.value) > Number(T1.value)){
                        alert("Team 2 hat gewonnen");
                        Gewinner ="Team 2";
                        WinP3 = 1;
                        WinP4 = 1;
                        T1ELO_new = Math.round(T1ELO + 80*(0 - T1Erwartungswert));
                        T2ELO_new = Math.round(T2ELO + 80*(1 - T2Erwartungswert));
                    }

                    Player1ELO_new = Player1ELO + Math.round((T1ELO_new - T1ELO) /2);
                    Player2ELO_new = Player2ELO + Math.round((T1ELO_new - T1ELO) /2);
                    Player3ELO_new = Player3ELO + Math.round((T2ELO_new - T2ELO) /2);
                    Player4ELO_new = Player4ELO + Math.round((T2ELO_new - T2ELO) /2);
                    /* ----- UPDATE IF NEW ELO HIGH ----- */

                    /*alert(Player1BestELO);
                    alert(Player1ELO_new);
                    if(Player1ELO_new > Player1BestELO )
                    {

                        alert(Player1BestELO);
                        $.ajax({
                            type: "POST",
                            url: 'updateElohigh.php',
                            data:{
                                playerelo_new: 2000,
                                playerid:Player1_ID


                            },
                            success: function(data){

                            },


                            error:function(){

                                alert("es ist ein Fehler beim aktualisieren des ELO Höchstwert aufgetreten (Player 1)");
                            }
                        });
                    }

                    if(Player2ELO_new > Player2BestELO )
                    {
                        $.ajax({
                            type: "POST",
                            url: 'updateElohigh.php',
                            data:{
                                playerelo_new: Player2ELO_new,
                                playerid:Player2_ID

                            },
                            success: function(data){

                            },


                            error:function(){

                                alert("es ist ein Fehler beim aktualisieren des ELO Höchstwert aufgetreten (Player 2)");
                            }
                        });
                    }

                    if(Player3ELO_new > Player3BestELO)
                    {
                        $.ajax({
                            type: "POST",
                            url: 'updateElohigh.php',
                            data:{
                                playerelo_new: Player3ELO_new,
                                playerid:Player3_ID
                            },
                            success: function(data){

                            },


                            error:function(){

                                alert("es ist ein Fehler beim aktualisieren des ELO Höchstwert aufgetreten (Player 3)");
                            }
                        });
                    }

                    if(Player4ELO_new > Player4BestELO)
                    {
                        $.ajax({
                            type: "POST",
                            url: 'updateElohigh.php',
                            data:{
                                playerelo_new: Player4ELO_new,
                                playerid:Player4_ID
                            },
                            success: function(data){

                            },


                            error:function(){

                                alert("es ist ein Fehler beim aktualisieren des ELO Höchstwert aufgetreten (Player 4)");
                            }
                        });
                    }*/





                    /* -----  ELO CALCULATION ----- */



                    //write game in database
                    $.ajax({
                    type: "POST",
                    url: 'writegame.php',
                    data:{
                        //spielzeit:Spielzeit

                         player1id:Player1_ID
                        , player1name:Player1
                        , player1elo:Player1ELO

                        , player2id:Player2_ID
                        , player2name:Player2
                        , player2elo:  Player2ELO

                        , player3id: Player3_ID
                        , player3name:Player3
                        , player3elo:Player3ELO

                        , player4id:Player4_ID
                        , player4name: Player4
                        , player4elo:Player4ELO

                        , t1elo:T1ELO
                        , t2elo:T2ELO
                        , treffer1:TrefferT1
                        , treffer2:TrefferT2

                        ,t1elo_new: T1ELO_new
                        ,T2elo_new: T2ELO_new

                        ,player1elo_new: Player1ELO_new
                        ,player2elo_new:Player2ELO_new
                        ,player3elo_new:Player3ELO_new
                        ,player4elo_new:Player4ELO_new

                        ,gewinner: Gewinner

                    },
                    success: function(data){
                        alert(data);
                    },


                    error:function(){

                        alert("es ist ein Fehler aufgetreten");
                    }
                });
                    //** UPDATE PLAYER IN DATABASE **//
                    //**Spieler 1**//
                    $.ajax({
                        type: "POST",
                        url: 'updateplayer.php',
                        data:{
                              playerid:Player1_ID
                            , treffer1:TrefferT1
                            , treffer2:TrefferT2
                            ,playerelo_new: Player1ELO_new
                            ,win: WinP1
                        },
                        success: function(data){

                        },


                        error:function(){

                            alert("es ist ein Fehler aufgetreten");
                        }
                    });


                    //**Spieler 2**//
                    $.ajax({
                        type: "POST",
                        url: 'updateplayer.php',
                        data:{
                            playerid:Player2_ID
                            , treffer1:TrefferT1
                            , treffer2:TrefferT2
                            ,playerelo_new: Player2ELO_new
                            ,win: WinP2
                        },
                        success: function(data){

                        },


                        error:function(){

                            alert("es ist ein Fehler aufgetreten");
                        }
                    });


                    //**Spieler 3**//
                    $.ajax({
                        type: "POST",
                        url: 'updateplayer.php',
                        data:{
                            playerid:Player3_ID
                            , treffer1:TrefferT2
                            , treffer2:TrefferT1
                            ,playerelo_new: Player3ELO_new
                            ,win: WinP3
                        },
                        success: function(data){

                        },


                        error:function(){

                            alert("es ist ein Fehler aufgetreten");
                        }
                    });


                    //**Spieler 4**//
                    $.ajax({
                        type: "POST",
                        url: 'updateplayer.php',
                        data:{
                            playerid:Player4_ID
                            , treffer1:TrefferT2
                            , treffer2:TrefferT1
                            ,playerelo_new: Player4ELO_new
                            ,win: WinP4
                        },
                        success: function(data){

                        },


                        error:function(){

                            alert("es ist ein Fehler aufgetreten");
                        }
                    });

                    var diffP1 = Player1ELO_new - Player1ELO;
                    var diffP2 = Player2ELO_new - Player2ELO;
                    var diffP3 = Player3ELO_new - Player3ELO;
                    var diffP4 = Player4ELO_new - Player4ELO;

                    //check if + or - and add + String if positive
                    if(diffP1 >= 0){ diffP1 = "+" + diffP1;        }
                    if(diffP2 >= 0){ diffP2 = "+" + diffP2;        }
                    if(diffP3 >= 0){ diffP3 = "+" + diffP3;        }
                    if(diffP4 >= 0){ diffP4 = "+" + diffP4;        }



                    //$('#spielzusammenfassung').find("label[id=Uhrzeit]").html(Date.now());
                    //$('#spielzusammenfassung').find("label[id=Spielzeit]").html(Spielzeit);
                    //update spielezusammenfassung
                    $('#spielzusammenfassung').find("label[id=spieler1]").html(Player1);
                    $('#spielzusammenfassung').find("label[id= spieler1_ELO]").html(Player1ELO);

                    $('#spielzusammenfassung').find("label[id=spieler2]").html(Player2);
                    $('#spielzusammenfassung').find("label[id= spieler2_ELO]").html(Player2ELO);
                    $('#spielzusammenfassung').find("label[id=  T1_ELO]").html(T1ELO);

                    $('#spielzusammenfassung').find("label[id=spieler3]").html(Player3);
                    $('#spielzusammenfassung').find("label[id= spieler3_ELO]").html(Player3ELO);

                    $('#spielzusammenfassung').find("label[id=spieler4]").html(Player4);
                    $('#spielzusammenfassung').find("label[id= spieler4_ELO]").html(Player4ELO);
                    $('#spielzusammenfassung').find("label[id=  T2_ELO]").html(T2ELO);

                    $('#spielzusammenfassung').find("label[id=  Gewinner]").html(Gewinner);
                    $('#spielzusammenfassung').find("label[id=  TrefferT1]").html(TrefferT1);
                    $('#spielzusammenfassung').find("label[id=  TrefferT2]").html(TrefferT2);
                    $('#spielzusammenfassung').find("label[id=  neuesELO_P1]").html(Player1ELO_new);
                    $('#spielzusammenfassung').find("label[id=  neuesELO_P2]").html(Player2ELO_new);
                    $('#spielzusammenfassung').find("label[id=  neuesELO_P3]").html(Player3ELO_new);
                    $('#spielzusammenfassung').find("label[id=  neuesELO_P4]").html(Player4ELO_new);

                    $('#spielzusammenfassung').find("label[id=  label_neuesELO_P1]").html(Player1 + " Neues ELO");
                    $('#spielzusammenfassung').find("label[id=  label_neuesELO_P2]").html(Player2 + " Neues ELO");
                    $('#spielzusammenfassung').find("label[id=  label_neuesELO_P3]").html(Player3 + " Neues ELO");
                    $('#spielzusammenfassung').find("label[id=  label_neuesELO_P4]").html(Player4 + " Neues ELO");

                    $('#spielzusammenfassung').find("label[id=  diffP1]").html(diffP1);
                    $('#spielzusammenfassung').find("label[id=  diffP2]").html(diffP2);
                    $('#spielzusammenfassung').find("label[id=  diffP3]").html(diffP3);
                    $('#spielzusammenfassung').find("label[id=  diffP4]").html(diffP4);



                    window.location.href = "http://192.168.178.26/#spielzusammenfassung";


                }
                else{alert("Sinnloses Ergebnis");}
            }
            else{ alert("Sinnloses Ergebnis");}
        } else {

        }
    };
</script>



</body>
</html>
<?php 
    if(isset($_POST)) 
	{



        $player1id = $_POST['player1id'];
        $player1name = $_POST['player1name'];
        $player1elo = $_POST['player1elo'];

        $player2id = $_POST['player2id'];
        $player2name = $_POST['player2name'];
        $player2elo = $_POST['player2elo'];

        $player3id = $_POST['player3id'];
        $player3name = $_POST['player3name'];
        $player3elo = $_POST['player3elo'];

        $player4id = $_POST['player4id'];
        $player4name = $_POST['player4name'];
        $player4elo = $_POST['player4elo'];

        $t1elo = $_POST['t1elo'];
        $t2elo = $_POST['t2elo'];
        $treffer1 = $_POST['treffer1'];
        $treffer2 = $_POST['treffer2'];

        $t1elo_new = $_POST['t1elo_new'];
        $t2elo_new = $_POST['T2elo_new'];

        $player1elo_new = $_POST['player1elo_new'];
        $player2elo_new = $_POST['player2elo_new'];
        $player3elo_new = $_POST['player3elo_new'];
        $player4elo_new = $_POST['player4elo_new'];

        $gewinner = $_POST['gewinner'];







		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = "goatpong";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}


        $sql = "Insert into gamelog (Spieler1,Spieler2,Spieler3,Spieler4,Spieler1_ELO,Spieler2_ELO,Spieler3_ELO,Spieler4_ELO,
        Team1ELO,Team2ELO,Gewinner,TrefferT1,TrefferT2,Spieler1ELO_neu,Spiele2ELO_neu,Spieler3ELO_neu,Spieler4ELO_neu)
        VALUES ( '$player1name','$player2name','$player3name','$player4name','$player1elo','$player2elo','$player3elo','$player4elo','$t1elo','$t2elo','$gewinner', '$treffer1','$treffer2','$player1elo_new','$player2elo_new','$player3elo_new','$player4elo_new')";



        if ($conn->query($sql) === TRUE) {
            echo "Spiel wurde gespeichert";

        }
        else
        {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();

         
    }    
    
?>
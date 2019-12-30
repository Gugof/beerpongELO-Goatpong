<?php 
    if(isset($_POST)) 
	{

        $playerelo_new = $_POST['playerelo_new'];
        $playerid = $_POST['playerid'];

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


        $sql = "Update Player Set BestELO = '$playerelo_new' WHERE ID = '$playerid'   ";


        if ($conn->query($sql) === TRUE) {


        }
        else
        {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();

         
    }    
    
?>
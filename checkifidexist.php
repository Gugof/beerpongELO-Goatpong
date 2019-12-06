<?php

//**this file check if the Player exist in the database**//

    if(isset($_POST)) 
	{
        $message = $_POST['message'];
        
        
		
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

		$sql = "SELECT COUNT(*) from player where ID = '$message'";

		if ($conn->query($sql) < 1) {
		echo "Spieler wurde erfolgreich gelöscht";
		
		} 
		else 
		{
		echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();

         
    }    
    
?>
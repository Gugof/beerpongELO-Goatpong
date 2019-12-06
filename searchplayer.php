<?php 
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

		
		
	
		$sql = "SELECT * FROM player where Name = '$message' OR Id = '$message'";
		
				
			


		if ($conn->query($sql) === TRUE) {
			while ($datensatz = $erg->fetch_object()) {
					$daten[] = $datensatz;
				}
		echo "Spieler wurde erfolgreich gelöscht";
		$response = $daten;
		
		} 
		else 
		{
		echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();

         
    }    
    
?>
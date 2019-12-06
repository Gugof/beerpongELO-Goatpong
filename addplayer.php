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

		if(is_numeric($message))
		{
			echo "Es dürfen keine Zahlen im Namen sein!";
		}
		else
		{
		$sql = "Insert into player (name) Values ('$message')";


		if ($conn->query($sql) === TRUE) {


            echo "Spieler wurde hinzugefügt";

		
		} 
		else 
		{
		echo "Error: ". $conn->error;
		}
		}
		$conn->close();

         
    }    
    
?>
<?php

//**this file delete Players from the database**//


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

		$sql = "Delete From player where ID = '$message'";

		if ($conn->query($sql) === TRUE) {
		    if($conn->affected_rows == 0){
		        echo "ID nicht vorhanden!";
            }
            else{
                echo "Spieler wurde erfolgreich gelöscht";
            }


		} 
		else 
		{
		echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();

         
    }    
    
?>
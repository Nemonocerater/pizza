
<?php

function getPizzaDbConnection()
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "pizza";

	$conn = new mysqli($servername, $username, $password, $database);
	if ($conn->connect_error) {
		throw new Exception ("Pizza database connection could not be created");
	} 

	return $conn;
}

?>

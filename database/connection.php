<?php

	$host = "localhost";
	$user = "root";
	$password = "abc1234567890";
	$dbName = "scoring";

	$conn = new mysqli($host, $user, $password, $dbName);

	if($conn->connect_errno){
		echo "Failed to connect to MySQL: ( ".$conn->connect_errno ." ) ".$conn->connect_error;
	}
?> 
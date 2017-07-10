<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    	$event_name = $_GET['event_name'];
		$event_host = $_GET['event_host'];
		$event_description = $_GET['event_description'];

		$sql = "CALL create_criteria('".$event_name."','".$event_host."','".$event_description."')";

		if(!$conn->query($sql)){
			echo "CALL failed: ( ".$conn->errno." ) " . $conn->error;
		}
		else{
			echo("Success!");
		}
	}
	else{
		echo "Invalid Request!";
	}
?>
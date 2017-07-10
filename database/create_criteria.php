<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    	$event_id = $_GET['event_id'];
		$criteria_desc = $_GET['criteria_desc'];
		$criteria_weight = $_GET['criteria_weight'];

		$sql = "CALL create_criteria('".$event_id."','".$criteria_desc."','".$criteria_weight."')";

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
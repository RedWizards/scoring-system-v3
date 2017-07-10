<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    	$team_id = $_GET['team_id'];
		$participant_firstName = $_GET['participant_firstName'];
		$participant_lastName = $_GET['participant_lastName'];
		$participant_email = $_GET['participant_email'];
		$participant_contactNo = $_GET['participant_contactNo'];

		$sql = "CALL add_participant('".$team_id."','".
			$participant_firstName."','".
			$participant_lastName."','".
			$participant_email."','".
			$participant_contactNo."')";

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
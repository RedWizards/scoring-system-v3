<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$team_id = $_POST['team_id'];
		$participant_firstName = $_POST['participant_firstName'];
		$participant_lastName = $_POST['participant_lastName'];
		$participant_email = $_POST['participant_email'];
		$participant_contactNo = $_POST['participant_contactNo'];

		$sql = "CALL add_participant('".$team_id."','".
			$participant_firstName."','".
			$participant_lastName."','".
			$participant_email."','".
			$participant_contactNo."')";

		if($result = $conn->query($sql)){
			$row = $result->fetch_assoc();
			echo(json_encode($row));
			$result->free();
		}
		else{
			echo "CALL failed: ( ".$conn->errno." ) " . $conn->error;
		}
	}
	else{
		echo "Invalid Request!";
	}
?>
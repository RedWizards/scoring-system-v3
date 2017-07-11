<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$event_name = $_POST['event_name'];
		$event_host = $_POST['event_host'];
		$event_description = $_POST['event_description'];

		$sql = "CALL create_event('".$event_name."','".$event_host."','".$event_description."')";

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
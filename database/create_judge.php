<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    	$event_id = $_GET['event_id'];
		$judge_name = $_GET['judge_name'];

		$sql = "CALL create_judge('".$event_id."','".$judge_name."')";

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
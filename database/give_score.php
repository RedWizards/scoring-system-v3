<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$judge_id = $_POST['judge_id'];
		$criteria_id = $_POST['criteria_id'];
		$project_id = $_POST['project_id'];
		$score = $_POST['score'];

		$sql = "CALL give_score('".
			$judge_id."','".
			$criteria_id."','".
			$project_id."','".
			$score."')";

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
<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    	$judge_id = $_GET['judge_id'];
		$criteria_id = $_GET['criteria_id'];
		$project_id = $_GET['project_id'];
		$score = $_GET['score'];

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
<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$event_id = $_POST['event_id'];
		$judge_name = $_POST['judge_name'];

		$sql = "CALL create_judge('".$event_id."','".$judge_name."')";

		if($result = $conn->query($sql)){
			$row = $result->fetch_assoc();

			session_start();
			if (!isset($_SESSION['judge_id'])) {
			  $_SESSION['judge_id'] = $row['id'];
			}
			
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
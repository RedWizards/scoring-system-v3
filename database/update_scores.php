<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		

    	$score_id = $_POST['score_id'];
    	$score = $_POST['score'];

		$sql = "CALL update_score(".$score_id.",".$score.")";

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
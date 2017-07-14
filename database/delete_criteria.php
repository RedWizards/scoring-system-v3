<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$criteria_id = $_POST['criteria_id'];

		$sql = "DELETE FROM criteria WHERE criteria_id = $criteria_id";

		if($result = $conn->query($sql)){
			echo 1;
		}
		else{
			echo("Cannot delete criteria. Scoring already started!");
		}
	}
	else{
		echo "Invalid Request!";
	}
?>
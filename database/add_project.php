<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			echo(json_encode($_POST));
    	$team_id = $_POST['team_id'];
    	$event_id = $_POST['event_id'];
		$project_name = $_POST['project_name'];
		$project_type = $_POST['project_type'];
		$short_description = $_POST['short_desc'];
		$long_description = $_POST['long_desc'];



		$sql = "CALL add_project(".
			$team_id.",".
			$event_id.",'".
			$project_name."','".
			$project_type."','".
			$short_description."','".
			$long_description."','".
			rand(1,200).
			"')";

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
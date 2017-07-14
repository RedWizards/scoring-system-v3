<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$team_id = $_POST['team_id'];

		$sql = "DELETE FROM team WHERE team_id = $team_id";

		if($result = $conn->query($sql)){
			echo 1;
		}
		else{
			echo($conn->error);
			echo("Cannot remove team. Scoring already started!");
		}
	}
	else{
		echo "Invalid Request!";
	}
?>
<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		$team_name = $_POST['team_name'];

		$sql = "CALL register_team('".
			$team_name."')";

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
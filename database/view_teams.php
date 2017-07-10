<?php
	header('Content-type: application/json');
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    	$event_id = $_GET['event_id'];

		$sql = "CALL view_teams('".$event_id."')";

		if(!($res = $conn->query($sql))){
			echo "SELECT failed: (" . $conn->errno . ") " . $conn->error;
		}
		else{
			$teams = array();

			while($row = $res->fetch_assoc()){
				array_push($teams, $row);
			}

			echo json_encode($teams);
		}
	}
	else{
		echo "Invalid Request!";
	}
?>
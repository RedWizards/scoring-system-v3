<?php
	header('Content-type: application/json');
	require('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$event_id = $_GET['event_id'];
		$team_id = $_GET['team_id'];
		
		$sql = "CALL view_criteria('".$event_id."')";

		if(!($res = $conn->query($sql))){
			echo "CALL failed: ( ".$conn->errno." ) " . $conn->error;
		}
		else{

			$criterias = array();

			while($row = $res->fetch_assoc()){
				array_push($criterias, $row);
			}

		}

		$res->free();
		
		$sql = "CALL view_judges('".$event_id."')";

		if(!($res = $conn->query($sql))){
			echo "CALL failed: ( ".$conn->errno." ) " . $conn->error;
		}
		else{

			$judges = array();

			while($row = $res->fetch_assoc()){
				array_push($judges, $row);
			}

			print_r($judges);
		}
	}
	else{
		echo "Invalid Request!";
	}
?>
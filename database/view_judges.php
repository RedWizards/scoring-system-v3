<?php
	header('Content-type: application/json');
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    	$event_id = $_GET['event_id'];

		$sql = "CALL view_judges('".$event_id."')";

		if(!($res = $conn->query($sql))){
			echo "SELECT failed: (" . $conn->errno . ") " . $conn->error;
		}
		else{
			$judges = array();

			while($row = $res->fetch_assoc()){
				array_push($judges, $row);
			}

			echo json_encode($judges);
		}
	}
	else{
		echo "Invalid Request!";
	}
?>
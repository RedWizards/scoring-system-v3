<?php
	header('Content-type: application/json');
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    	$event_id = $_GET['event_id'];

		$sql = "CALL view_criteria('".$event_id."')";

		if(!($res = $conn->query($sql))){
			echo "SELECT failed: (" . $conn->errno . ") " . $conn->error;
		}
		else{
			$criteria = array();

			while($row = $res->fetch_assoc()){
				array_push($criteria, $row);
			}

			echo json_encode($criteria);
		}
	}
	else{
		echo "Invalid Request!";
	}
?>
<?php
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$event_id = $_POST['event_id'];
		$criteria_desc = $_POST['criteria_desc'];
		$criteria_weight = $_POST['criteria_weight'];
		$criteria_longdesc = $_POST['criteria_longdesc'];

		$sql = "CALL create_criteria('".$event_id."','".$criteria_desc."','".$criteria_weight."','".$criteria_longdesc."')";

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
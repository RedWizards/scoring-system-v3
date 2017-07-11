<?php
	header('Content-type: application/json');
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$sql = "SELECT * FROM team";

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
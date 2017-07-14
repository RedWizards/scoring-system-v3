<?php
	require_once('connection.php');

	$sql = "DELETE FROM team";

	if($conn->query($sql)){
		$conn->next_result();

		$sql = "DELETE FROM judge";

		if($result = $conn->query($sql)){
			$conn->next_result();

			$sql = "DELETE FROM criteria";

			if($result = $conn->query($sql)){
				$conn->next_result();

				header('Location: ../server/');
			}
			else{
				echo $conn->error;
			}
		}
		else{
			echo $conn->error;
		}
	}
	else{
		echo $conn->error;
	}
?>
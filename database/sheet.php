<?php

	header('Content-type: application/json');

	$scoreSheet = [];
	
	$criteria1["criteria_id"] = "1";
	$criteria1["name"] = "Technical Difficulty";
	$criteria1["description"] = "Includes the technicality of the application";
	$criteria1["weight"] = 25;
	$criteria1["score"] = 0;
	
	$criteria2["criteria_id"] = "2";
	$criteria2["name"] = "Business Impact";
	$criteria2["description"] = "Business impact impact impact impact";
	$criteria2["weight"] = 25;
	$criteria2["score"] = 0;
	
	$criteria3["criteria_id"] = "3";
	$criteria3["name"] = "Innovation";
	$criteria3["description"] = "Inno innov inoova innvat innovation";
	$criteria3["weight"] = 25;
	$criteria3["score"] = 0;
	
	$criteria4["criteria_id"] = "4";
	$criteria4["name"] = "Demo";
	$criteria4["description"] = "Demoooooooooooooo";
	$criteria4["weight"] = 25;
	$criteria4["score"] = 0;
	
	// append to the array event
	$scoreSheet[] = $criteria1;
	$scoreSheet[] = $criteria2;
	$scoreSheet[] = $criteria3;
	$scoreSheet[] = $criteria4;
	
	
	echo json_encode($scoreSheet);

?>
<?php
	header('Content-type: application/json');
	require_once('connection.php');

	mysqli_set_charset($conn, "utf8");

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {

		$event_id = $_GET['event_id'];

    	$judges = array();

    	//Query all judges
		$sql = "CALL view_judges(".$event_id.")";
		$result = $conn->query($sql);
		if($result){
		    // Cycle through results
		    while ($row = $result->fetch_object()){

		    	//convert judge_id to int
		    	$row->judge_id = intval($row->judge_id);

		        array_push($judges,$row);
		    }
		    
		    // Free result set
		    $result->close();
		    $conn->next_result();
		}

		$temp_judges = array();
		foreach($judges as $judge){

			// print_r($judges);

			//Query all judges
			$sql = "CALL judge_scoresheet(".$judge->judge_id.")";
			$result = $conn->query($sql);
			if($result){
				$teams = array();
			    while ($row = $result->fetch_object()){
			    	// print_r($row);
			    	$team = array();
			    	$criteria = array();
			    	$total = 0;

			    	
			    	foreach($row as $key => $value){
			    		switch($key){
			    			case 'project_id':
			    				$team['project_id'] = intval($row->project_id);
			    				break;
			    			case 'project_name':
			    				$team['project_name'] = $row->project_name;
			    				break;
			    			case 'team_id':
			    				$team['team_id'] = intval($row->team_id);
			    				break;
			    			case 'team_name':
			    				$team['team_name'] = $row->team_name;
			    				break;
			    			default:
			    				$total += floatval($value);
			    				$temp_criteria = array();
			    				$temp_criteria['criteria_desc'] = $key;
			    				$temp_criteria['score'] = floatval($value);
			    				array_push($criteria, $temp_criteria);
			    		}
				    	unset($key);
				    	unset($value);
			    	}
			    	$team['criteria'] = $criteria;
			    	array_push($teams, $team);
			    }
			    $judge->teams = $teams;
			    
			    // Free result set
			    $result->close();
			    $conn->next_result();
			}
			array_push($temp_judges, $judge);
			// print_r($temp_judges);
		}
		unset($judge);

		echo json_encode($temp_judges);
	}
	else{
		echo "Invalid Request!";
	}
?>
<?php
	header('Content-type: application/json');
	require_once('connection.php');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    	$event_id = $_GET['event_id'];
    	$judge_id = $_GET['judge_id'];

    	//array variables
    	$teams = array();
    	$criterias = array();

    	//Query all teams
		$sql = "CALL view_teams(".$event_id.")";
		$result = $conn->query($sql);
		if($result){
		    // Cycle through results
		    while ($row = $result->fetch_object()){

		    	//convert team_id and project_id to int
		    	$row->team_id = intval($row->team_id);
		    	$row->project_id = intval($row->project_id);

		        array_push($teams,$row);
		    }
		    
		    // Free result set
		    $result->close();
		    $conn->next_result();
		}

		$sql = "CALL view_criteria(".$event_id.")";
		$result = $conn->query($sql);
		if($result){
		    // Cycle through results
		    while ($row = $result->fetch_object()){
		    	//convert criteria_id to int
		    	$row->criteria_id = intval($row->criteria_id);
		    	//convert criteria_weight to float
				$row->criteria_weight = floatval($row->criteria_weight);
		        array_push($criterias,$row);
		    }
		    // Free result set
		    $result->close();
		    $conn->next_result();
		}



		// $temp_teams = $teams;	//transfer data to a temporary variable
		// $teams = [];			//reset $teams
		// //loop through teams
		// foreach($temp_teams as $team){

		// 	$team_id = $team->team_id;

		// 	//Query all members
		// 	$sql = "CALL view_members(".$team_id.")";
		// 	$result = $conn->query($sql);
		// 	if($result){
  //   			$members = array();

		// 	    // Cycle through results
		// 	    while ($row = $result->fetch_object()){

		// 	    	//convert member_id to int
		// 	   		$row->participant_id = intval($row->participant_id);

		// 	        array_push($members, $row);
		// 	    }

		// 	    $team->members = $members;

		// 	    // Free result set
		// 	    $result->close();
		// 	    $conn->next_result();
		// 	}

		// 	array_push($teams, $team);
		// }

		//Set team score_details
		$temp_teams = array();

		foreach($teams as $team){
			$total = 0;
		
			$temp_criterias = array();
			//loop all through criterias
			foreach($criterias as $criteria){

				$sql = "CALL view_score(".$team->project_id.",".$judge_id.",".$criteria->criteria_id.")";

				$result = $conn->query($sql);

				if($result){
				    $row = $result->fetch_object();

					if(isset($row)){
						//convert score_id to int and score to float
					    $row->score_id = intval($row->score_id);
						$row->score = floatval($row->score);
					}
					else{
						$score = array();
						$score['score_id'] = null;
						$score['score'] = "";

						$row = (object) $score;
					}

				    $total += (int)$row->score;

				    //add to json the score data
					$criteria->score_details = $row;

					array_push($temp_criterias, $criteria);

				    // Free result set
				    $result->close();
				    $conn->next_result();
				}
				else{
					$score_details = array();

					//lol. fix this asap
					$score_details['score_id'] = null;
					$score_detials['score'] = 0;

					//add to json the score data
					$criteria->score_details = $score_details;

					array_push($temp_criterias, $criteria);
				}
			}
			$team->criteria = $temp_criterias;
			$team->total = $total;
			$temp_teams[] = json_encode($team);
		}

		$final_result = array();

		foreach($temp_teams as $team){
			array_push($final_result, json_decode($team));
		}

		echo json_encode($final_result);
	}
	else{
		echo "Invalid Request!";
	}
?>
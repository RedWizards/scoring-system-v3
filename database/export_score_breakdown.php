<?php
	header('Content-Type: text/csv; charset=utf-8');  
	header('Content-Disposition: attachment; filename=score_breakdown.csv');  
	
	require_once('connection.php');

	mysqli_set_charset($conn, "utf8");

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		// $event_id = $_GET['event_id'];
		$event_id = 1;
		 
	    $output = fopen("php://output", "w");

	    $query = "Call view_judges(".$event_id.")";

	    $judges = array();

	    if(!($res = $conn->query($query))){
			echo "SELECT failed: (" . $conn->errno . ") " . $conn->error;
		}
		else{

			while($row = $res->fetch_object()){
				array_push($judges,(array) $row);
			}

			 // Free result set
		    $res->close();
		    $conn->next_result();
		}

		foreach($judges as $judge){

			$sql = "Call judge_scoresheet(".$judge['judge_id'].")";
		    unset($judge['judge_id']);

		    fputcsv($output, (array) $judge);

		    if(!($res = $conn->query($sql))){
				echo "Call failed: (" . $conn->errno . ") " . $conn->error;
			}
			else{

				$first = true;
			    while($row = $res->fetch_object())  
			    {  
			    	unset($row->project_id);
			    	unset($row->team_id);

			    	if ($first) {
				        fputcsv($output, array_keys((array) $row));
				        $first = false;
				    }

			        fputcsv($output, (array) $row);  
			    }  
			    fputcsv($output, ["",""]);
				 // Free result set
			    $res->close();
			    $conn->next_result();
			}
		}

	    header('Content-Type: text/csv; charset=utf-8');  
	    header('Content-Disposition: attachment; filename=total_score_summary.csv');  
	    $output = fopen("php://output", "w");  
	    // fputcsv($output, array('ID', 'First Name', 'Last Name', 'Email', 'Joining Date');  
	    $query = "Call app_total_scoresheet(".$event_id.")";  
	    $result = $conn->query($query);  

	    $first = true;
	    $scores = array();

	    while($row = $result->fetch_object())  
	    {
	    	if ($first) {
		        fputcsv($output, array_keys((array)$row));
		        $first = false;
		    }

		    array_push($scores, array_slice((array)$row,1));

	        fputcsv($output, (array)$row);  
	    }  
		// Free result set
		$result->close();

		$complete_scores = 0;
		$total_scores = array();
		$average = array();

		//initialize total_scores with 0s
		for ($x = 0; $x < (count($scores[0])); $x++)
		{
		    array_push($total_scores, 0);
		    array_push($average, 0);
		}

		//summation of scores excluding judges with not complete scores
		for($x = 0; $x < count($scores); $x++){
			//loop by row
			$complete = true;

			$keys = array_keys($scores[$x]);
			for($y = 0; $y < count($keys); $y++){

				//loop by column
				if($scores[$x][$keys[$y]] == null){
					$complete = false;
				}
			}

			if($complete == true){
				$complete_scores++;

				//add to total_scores array
				for($y = 0; $y < count($keys); $y++){
					$total_scores[$y] = $total_scores[$y] + $scores[$x][$keys[$y]];
				}
			}
		}

		//average scores with the number of complete scores
		for($y = 0; $y < count($total_scores); $y++){
			$average[$y] = round(($total_scores[$y] / $complete_scores),6);
		}

		array_unshift($total_scores,"Total*");
		array_unshift($average,"Average**");


		fputcsv($output, ["",""]);
		fputcsv($output, ["Number of Complete Scores: $complete_scores",""]);
		fputcsv($output, ($total_scores));  
		fputcsv($output, ($average));

		fputcsv($output, ["",""]);
		fputcsv($output, ["",""]);

		fputcsv($output, ["Note:",""]);
		fputcsv($output, ["*Summation of scores excluding judges with incomplete scores",""]);
		fputcsv($output, ["**Average of scores",""]);

	    fclose($output);
	}  
	else{
		echo('Invalid Request!');
	}
?>
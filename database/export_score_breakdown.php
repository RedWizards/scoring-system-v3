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
	    while($row = $result->fetch_object())  
	    {  
	    	if ($first) {
		        fputcsv($output, array_keys((array)$row));
		        $first = false;
		    }
	        fputcsv($output, (array) $row);  
	    }  
		// Free result set
		$result->close();

	    fclose($output);
	}  
	else{
		echo('Invalid Request!');
	}
?>
<?php
	header('Content-Type: text/csv; charset=utf-8');  
	header('Content-Disposition: attachment; filename=score_breakdown.csv');
	
	require_once('connection.php');

	mysqli_set_charset($conn, "utf8");

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$event_id = $_GET['event_id'];
		 
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
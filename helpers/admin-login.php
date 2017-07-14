<?php
	session_start();
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$password = $_POST['password'];

    	if(isset($_SESSION['admin'])){
    		header('Location: ../server/dashboard.php');
    	}
    	else{
			if(strcmp($password, 'T3chEvents') == 0){
	    		$_SESSION['admin'] = true;
	    		header('Location: ../server/dashboard.php');
	    	}
	    	else{
	    		header('Location: ../server/index.php');
	    	}    		
    	}
    	
	}
	else{
		echo "Invalid Request!";
	}
?>

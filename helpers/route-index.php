<?php
	session_start();
	if(isset($_SESSION['finished'])){
		if($_SESSION['finished'] == true){
			header('Location: done.php');
		}
		else{
			header('Location: scoresheet.php');
		}
	}
?>

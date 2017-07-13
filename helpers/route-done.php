<?php 
	session_start();
	if(isset($_SESSION['finished'])){
		if(!$_SESSION['finished']){
			header('Location: scoresheet.php');
		}
	}
	else{
		header('Location: index.php');
	}
?>
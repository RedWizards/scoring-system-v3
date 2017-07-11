<?php
	session_start();
	if(isset($_SESSION['registered'])){
		header('Location: done.php');
	}
?>
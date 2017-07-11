<?php
	session_start();
	if(!isset($_SESSION['judge_id'])){
		header('Location: index.php');
	}
?>

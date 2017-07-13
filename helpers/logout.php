<?php
	session_start();
	unset($_SESSION['judge_id']);
	$_SESSION['finished'] = true;
	header('Location: ../done.php');
?>

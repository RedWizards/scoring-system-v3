<?php
	session_start();
	unset($_SESSION['judge_id']);
	header('Location: ../done.php');
?>

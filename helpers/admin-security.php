<?php
	session_start();
	if(!isset($_SESSION['admin'])){
    	header('Location: ../server/index.php');
    }
?>

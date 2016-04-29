<?php
	session_start();
	$_SESSION['filter'] = $_GET['filter'];
	header("Location: fcIndex.php");
?>
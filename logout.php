<?php
	session_start();
	session_unset();
	session_destroy();
	$source = $_POST['source'];
	header("Location: {$source}");
?>

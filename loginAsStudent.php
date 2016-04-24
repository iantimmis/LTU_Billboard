<?php
	session_start();
	$sourcePage = $_POST['source'];
	$username= $_POST["studentEmail"];
	$password= $_POST["studentPassword"];

	$servername = "localhost";
	$dbusername = "root";
	$dbpassword = "root";
	$dbname = "LTUBillboard";
	// Create connection
	$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT * FROM user_account WHERE user_email='{$username}' AND login_password='{$password}';";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		$userInfo=$result->fetch_assoc();
	} else {
		echo "0 results";
	}
	$conn->close();
	
	$_SESSION['userId'] = $userInfo['userId'];
	$_SESSION["firstName"] = $userInfo['first_name'];
	$_SESSION["lastName"] = $userInfo['last_name'];
	$_SESSION['isAdmin'] = $userInfo['is_admin'];
	
	header("Location: {$sourcePage}");
?>
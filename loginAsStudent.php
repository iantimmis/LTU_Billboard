<?php
	session_start();
	$username= $_POST["studentUsername"];
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
	$sql = "SELECT * FROM user_account WHERE act_username='{$username}' AND act_password='{$password}';";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		$userInfo=$result->fetch_assoc();
	} else {
		echo "0 results";
	}
	$conn->close();
	
	$_SESSION['userId'] = $userInfo['id'];
	$_SESSION["firstName"] = $userInfo['first_name'];
	$_SESSION["lastName"] = $userInfo['last_name'];
	$_SESSION['orgList'] = $userInfo['org_list'];
	$_SESSION['eventList'] = $userInfo['event_list'];
	$_SESSION['isAdmin'] = $userInfo['is_admin'];
	
	header("Location: eventpage.php");
?>
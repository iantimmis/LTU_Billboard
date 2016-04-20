<?php
	session_start();
	$username= $_POST["orgUsername"];
	$password= $_POST["orgPassword"];

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
	$sql = "SELECT * FROM ltuorganization WHERE login_username='{$username}' AND login_password='{$password}';";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		$userInfo=$result->fetch_assoc();
	} else {
		echo "0 results";
	}
	$conn->close();
	
	$_SESSION['orgId'] = $userInfo['id'];
	$_SESSION["orgName"] = $userInfo['the_name'];
	$_SESSION["orgDesc"] = $userInfo['description'];
	$_SESSION['orgWebsite'] = $userInfo['website'];
	
	header("Location: eventpage.php");
?>
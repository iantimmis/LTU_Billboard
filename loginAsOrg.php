<?php
	session_start();
	$sourcePage = $_POST['source'];
	$username= $_POST["orgEmail"];
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
	$sql = "SELECT * FROM ltuorganization WHERE org_email='{$username}' AND login_password='{$password}';";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		$userInfo=$result->fetch_assoc();
		
		$_SESSION['orgId'] = $userInfo['orgId'];
		$_SESSION["orgName"] = $userInfo['org_name'];
		$_SESSION["orgDesc"] = $userInfo['org_description'];
		$_SESSION['orgWebsite'] = $userInfo['org_website'];
	} else {
		echo "0 results";
	}
	$conn->close();
	
	
	header("Location: {$sourcePage}");
?>
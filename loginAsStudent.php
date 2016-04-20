<?php
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
		$evtInfo=$result->fetch_assoc();
	} else {
		echo "0 results";
	}
	$conn->close();
	
	//header("Location: eventpage.php");
?>
<!doctype html>
<html>
<head>
<title>logged in page</title>
</head>
<body>
<pre>
<?php echo $evtInfo['first_name'] . " " . $evtInfo['last_name']; ?>
</pre>
</body>
</html>
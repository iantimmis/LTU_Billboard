<?php
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "LTUBillboard";
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	if($_POST['actType']=="orgAct"){	
		$sql = "INSERT INTO ltuorganization (org_name,org_description,org_website,login_password,org_email)
			values ('{$_POST['orgName']}','{$_POST['orgDesc']}','{$_POST['orgUrl']}','{$_POST['orgPassword']}','{$_POST['orgEmail']}');";
	
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		$conn->close();
	}
	elseif($_POST['actType']=="studentAct"){
		$sql = "INSERT INTO user_account (first_name,last_name,login_password,is_admin,user_email)
			values ('{$_POST['firstName']}','{$_POST['lastName']}','{$_POST['stuPassword']}',0,'{$_POST['stuEmail']}');";
	
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		$conn->close();
	}
	
	header("Location: fcIndex.php");
?>
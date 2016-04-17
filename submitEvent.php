<?php
	$evtPrivate = $_POST["evtPrivate"];
	$evtName = $_POST["evtName"];
	$evtBuildingRoom = $_POST["evtBuildingRoom"];
	$evtCategory = $_POST["evtCategory"];
	$evtDate = $_POST["evtDate"];
	$evtTime = $_POST["evtTime"];
	$evtDesc = $_POST["evtDesc"];
	$evtOrgId = $_POST["evtOrgId"];
	$servername = "localhost";
	$username = "root";
	$password = "root";
	
	$evtInfo = array("isPrivate"=>$_POST["evtPrivate"], 'evtName'=>$_POST["evtBuildingRoom"],'evtCategory'=>$_POST['evtCategory'],
					'evtDate'=>$_POST['evtDate'],'evtTime'=>$_POST['evtTime'],'evtDesc'=>$_POST['evtDesc'],'evtOrgId'=>$_POST['evtOrgId']);
	$dbname = "LTUBillboard";
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO LTUEvents (evtPrivate, evtName, evtBuildingRoom,evtCategory,evtDate,evtTime,evtDesc,evtOrgId)
			VALUES ({$evtPrivate}, '{$evtName}', '{$evtBuildingRoom}', '{$evtCategory}', '{$evtDate}', '{$evtTime}', '{$evtDesc}', {$evtOrgId})";
 
	if ($conn->query($sql) === TRUE) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();
	
	header("Location: eventpage.php");
?>

<!doctype html>
<html>
<head>
<title>Submit Event</title>
</head>

<body>
<pre>
</pre>
</body>
</html>
<?php
	$evtPrivate = $_POST["evtPrivate"];
	$evtName = $_POST["evtName"];
	$evtBuildingRoom = $_POST["evtBuildingRoom"];
	$evtCategory = $_POST["evtCategory"];
	$evtDate = $_POST["evtDate"];
	$evtStartTime = $_POST["evtStartTime"];
	$evtEndTime = $_POST["evtEndTime"];
	$evtDesc = $_POST["evtDesc"];
	$evtOrgId = $_POST["evtOrgId"];
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$evtInfo = array("isPrivate"=>$_POST["evtPrivate"], 'evtName'=>$_POST["evtName"],'evtBuildingRoom'=>$_POST["evtBuildingRoom"],'evtCategory'=>$_POST['evtCategory'],
					'evtDate'=>$_POST['evtDate'],'evtStart'=>$_POST['evtStartTime'],'evtEnd'=>$_POST['evtEndTime'],'evtDesc'=>$_POST['evtDesc'],'evtOrgId'=>$_POST['evtOrgId']);
	$dbname = "LTUBillboard";
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO LTUEvents (org_id,is_private, evt_name, evt_room,evt_category,evt_date,evt_start_time,evt_end_time,evt_desc)
			VALUES ({$evtOrgId},{$evtPrivate}, '{$evtName}', '{$evtBuildingRoom}', '{$evtCategory}', '{$evtDate}', '{$evtStartTime}', '{$evtEndTime}', '{$evtDesc}')";
 
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
<?php print_r($_POST);?><br /> <br />
<?php print_r($evtInfo);?><br /><br />
<?php echo $dbname;?>
</pre>
</body>
</html>
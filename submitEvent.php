<?php
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "LTUBillboard";
	$source = $_POST['submit'];
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	if(strcmp($source,'createEvent.php')==0){
		$sql = "INSERT INTO LTUEvents (org_id,is_private, evt_name, evt_room, evt_category,evt_start_date,evt_end_date,evt_start_time,evt_end_time,evt_desc,evt_url,evt_visible)
				VALUES ({$_POST["evtOrgId"]},{$_POST["evtPrivate"]}, '{$_POST["evtName"]}', '{$_POST["evtBuildingRoom"]}', '{$_POST["evtCategory"]}',
				'{$_POST["evtStartDate"]}','{$_POST["evtEndDate"]}', '{$_POST["evtStartTime"]}', '{$_POST["evtEndTime"]}', '{$_POST["evtDesc"]}','{$_POST["evtUrl"]}',1)";
	 
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
	else{
		$sql = "INSERT INTO LTUEvents (org_id,is_private, evt_name, evt_room, evt_category,evt_start_date,evt_end_date,evt_start_time,evt_end_time,evt_desc,evt_url,evt_visible)
				VALUES (-{$_POST['evtUserId']},1, '{$_POST["evtName"]}', '{$_POST["evtBuildingRoom"]}', 'User Created',
				'{$_POST["evtStartDate"]}','{$_POST["evtEndDate"]}', '{$_POST["evtStartTime"]}', '{$_POST["evtEndTime"]}', '{$_POST["evtDesc"]}','N/A',1)";
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
	$conn->close();
	
	header("Location: {$source}");
?>
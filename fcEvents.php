<?php
	session_start();
    $servername="localhost";
    $username="root";
    $password="root";
    $dbname="LTUBillboard";
	
	if (isset($_SESSION['userId'])){
		$userInfo['userId'] = $_SESSION['userId'];
		$userInfo['firstName'] = $_SESSION["firstName"];
		$userInfo['lastName'] = $_SESSION["lastName"];
		$userInfo['isAdmin'] = $_SESSION['isAdmin'];
		$userId = $userInfo['userId'];
		$message  = $userInfo['firstName'] . " " . $userInfo['lastName'];
	} elseif (isset($_SESSION['orgId'])) {
		$orgInfo['id'] = $_SESSION['orgId'];
		$orgInfo['name'] = $_SESSION['orgName'];
		$orgInfo['desc'] = $_SESSION['orgDesc'];
		$orgInfo['website'] = $_SESSION['orgWebsite'];
		$message = $orgInfo['name'];
	} else {
		$message = "No One";
	}
	
	$filterSet = isset($_SESSION['filter']);
	if($filterSet)
	{
		$filter = $_SESSION['filter'];
	}
	if(!$filterSet || ($filterSet && (strcmp($_SESSION['filter'],"non")==0))){//no filter
		$con=new mysqli($servername, $username, $password, $dbname);
		if($con->connect_error)
		{
			die("Connection failed:" . $con->connect_error);
		}
		$evtSql="SELECT * FROM ltuevents ORDER BY evt_start_date";
		$evtResult=$con->query($evtSql);
		
		$orgSql="SELECT orgId, org_name FROM ltuorganization order by orgId;";
		$orgResult=$con->query($orgSql);
		if($orgResult->num_rows==0)
		{
			
		}
		else
		{
			$orgArray=array();
			while($orgRow=$orgResult->fetch_assoc())
			{
				array_push($orgArray,$orgRow);
			}
		}
		if($evtResult->num_rows==0)
		{
		}
		else
		{
			$jsonArray=array();
			while($row=$evtResult->fetch_assoc())
			{
				$evt_orgId = $row['org_id'];
				$evt_orgName = $evt_orgId>0 ? $orgArray[$evt_orgId-1]['org_name'] : "User Created";
				$start=$row['evt_start_date'] . "T" . $row['evt_start_time'];
				$end=$row['evt_end_date'] . "T" . $row['evt_end_time'];
				$duration = $start . " to " . $end;
				$subArray=array("id"=>$row['eventId'], "org_id" => $row['org_id'], "org_name" => $evt_orgName, "title" => $row['evt_name'], 
					"desc"=>$row['evt_desc'], "room"=>$row['evt_room'],"start" => $start, "end" => $end, "link" => $row['evt_url'],
					"date"=>$row['evt_start_date'], "start_time" =>$row['evt_start_time'], "end_time" =>$row['evt_end_time']);
			$jsonArray[]=$subArray;
			}
			echo json_encode($jsonArray);
		}
    $con->close();
	}
	$hasEvents = true;
	$hasOrgs = true;
	$userArray  = array();
	if($filterSet) {
		if(strcmp($_SESSION['filter'],"add")==0){//filter for events added to calendar
			$con=new mysqli($servername, $username, $password, $dbname);
			if($con->connect_error)
			{
				die("Connection failed:" . $con->connect_error);
			}
			$orgSql="SELECT orgId, org_name FROM ltuorganization ORDER BY orgId;";//get all organizations
			$orgResult=$con->query($orgSql);
			if($orgResult->num_rows==0){}
			else
			{
				$orgArray=array();
				while($orgRow=$orgResult->fetch_assoc())
				{
					array_push($orgArray,$orgRow);
				}
			}
			$evtUserSql="SELECT eventId FROM user_event_join WHERE userId = {$userInfo['userId']};";//get ids of events user added to calendar
			$evtUserResult=$con->query($evtUserSql);
			if($evtUserResult->num_rows==0){
				$hasEvents = false;
			}
			else
			{
				while($userRow=$evtUserResult->fetch_assoc())
				{
					array_push($userArray,$userRow['eventId']);
				}
			}
			if($hasEvents){
				$evtSql="SELECT * FROM ltuevents ORDER BY evt_start_date";//get all events
				$evtResult=$con->query($evtSql);
				if($evtResult->num_rows==0){}
				else
				{
					$jsonArray=array();
					while($row=$evtResult->fetch_assoc())
					{
						if(in_array($row['eventId'],$userArray)){
							$evt_orgId = $row['org_id'];
							$evt_orgName = $orgArray[$evt_orgId-1]['org_name'];
							$start=$row['evt_start_date'] . "T" . $row['evt_start_time'];
							$end=$row['evt_end_date'] . "T" . $row['evt_end_time'];
							$duration = $start . " to " . $end;
							$subArray=array("id"=>$row['eventId'], "org_id" => $row['org_id'], "org_name" => $evt_orgName, "title" => $row['evt_name'], 
								"desc"=>$row['evt_desc'], "room"=>$row['evt_room'],"start" => $start, "end" => $end, "link" => $row['evt_url'],
								"date"=>$row['evt_start_date'], "start_time" =>$row['evt_start_time'], "end_time" =>$row['evt_end_time']);
							$jsonArray[]=$subArray;
						}
						if($row['org_id'] == -1 * $userInfo['userId']){
							$evt_orgId = $row['org_id'];
							$evt_orgName = "User Created";
							$start=$row['evt_start_date'] . "T" . $row['evt_start_time'];
							$end=$row['evt_end_date'] . "T" . $row['evt_end_time'];
							$duration = $start . " to " . $end;
							$subArray=array("id"=>$row['eventId'], "org_id" => $row['org_id'], "org_name" => $evt_orgName, "title" => $row['evt_name'], 
								"desc"=>$row['evt_desc'], "room"=>$row['evt_room'],"start" => $start, "end" => $end, "link" => $row['evt_url'],
								"date"=>$row['evt_start_date'], "start_time" =>$row['evt_start_time'], "end_time" =>$row['evt_end_time']);
							$jsonArray[]=$subArray;
						}
					}
					echo json_encode($jsonArray);
				}
				$con->close();
			}
		}
		if(strcmp($_SESSION['filter'],"org")==0){
			$con=new mysqli($servername, $username, $password, $dbname);
			if($con->connect_error)
			{
				die("Connection failed:" . $con->connect_error);
			}
			$orgSql="SELECT orgId, org_name FROM ltuorganization ORDER BY orgId;";//get all organizations
			$orgResult=$con->query($orgSql);
			if($orgResult->num_rows==0){}
			else
			{
				$orgArray=array();
				while($orgRow=$orgResult->fetch_assoc())
				{
					array_push($orgArray,$orgRow);
				}
			}
			$orgUserSql="SELECT orgId FROM user_org_join WHERE userId = {$userInfo['userId']};";//get ids of events user added to calendar
			$orgUserResult=$con->query($orgUserSql);
			if($orgUserResult->num_rows==0){
				$hasOrgs = false;
			}
			else
			{
				while($userRow=$orgUserResult->fetch_assoc())
				{
					array_push($userArray,$userRow['orgId']);
				}
			}
			if($hasOrgs){
				$evtSql="SELECT * FROM ltuevents Where org_id > 0 ORDER BY evt_start_date";//get all events
				$evtResult=$con->query($evtSql);
				if($evtResult->num_rows==0){}
				else
				{
					$jsonArray=array();
					while($row=$evtResult->fetch_assoc())
					{
						if(in_array($row['org_id'],$userArray)){
							$evt_orgId = $row['org_id'];
							$evt_orgName = $orgArray[$evt_orgId-1]['org_name'];
							$start=$row['evt_start_date'] . "T" . $row['evt_start_time'];
							$end=$row['evt_end_date'] . "T" . $row['evt_end_time'];
							$duration = $start . " to " . $end;
							$subArray=array("id"=>$row['eventId'], "org_id" => $row['org_id'], "org_name" => $evt_orgName, "title" => $row['evt_name'], 
								"desc"=>$row['evt_desc'], "room"=>$row['evt_room'],"start" => $start, "end" => $end, "link" => $row['evt_url'],
								"date"=>$row['evt_start_date'], "start_time" =>$row['evt_start_time'], "end_time" =>$row['evt_end_time']);
							$jsonArray[]=$subArray;
						}
					}
					echo json_encode($jsonArray);
				}
				$con->close();
			}
		}
		if(strcmp($_SESSION['filter'],"mine")==0){
			$con=new mysqli($servername, $username, $password, $dbname);
			if($con->connect_error)
			{
				die("Connection failed:" . $con->connect_error);
			}
			$negUserId = -1 * $userInfo['userId'];
			$evtSql="SELECT * FROM ltuevents Where org_id = {$negUserId} ORDER BY evt_start_date";//get all events
			$evtResult=$con->query($evtSql);
			if($evtResult->num_rows==0){}
			else
			{
				$jsonArray=array();
				while($row=$evtResult->fetch_assoc())
				{
					$evt_orgId = $row['org_id'];
					$evt_orgName = "User Created";
					$start=$row['evt_start_date'] . "T" . $row['evt_start_time'];
					$end=$row['evt_end_date'] . "T" . $row['evt_end_time'];
					$duration = $start . " to " . $end;
					$subArray=array("id"=>$row['eventId'], "org_id" => $row['org_id'], "org_name" => $evt_orgName, "title" => $row['evt_name'], 
						"desc"=>$row['evt_desc'], "room"=>$row['evt_room'],"start" => $start, "end" => $end, "link" => $row['evt_url'],
						"date"=>$row['evt_start_date'], "start_time" =>$row['evt_start_time'], "end_time" =>$row['evt_end_time']);
					$jsonArray[]=$subArray;
					
				}
				echo json_encode($jsonArray);
			}
			$con->close();
		
		}
	}
	
	
?>
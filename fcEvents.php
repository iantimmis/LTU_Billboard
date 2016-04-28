<?php
    $servername="localhost";
    $username="root";
    $password="root";
    $dbname="LTUBillboard";

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
        //do nothing
    }
    else
    {
        $jsonArray=array();
        while($row=$evtResult->fetch_assoc())
        {
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
        echo json_encode($jsonArray);
    }
	
	
    $con->close();
?>
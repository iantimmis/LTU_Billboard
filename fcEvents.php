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
    $sql="SELECT * FROM ltuevents ORDER BY evt_start_date";
    $result=$con->query($sql);
    if($result->num_rows==0)
    {
        //do nothing
    }
    else
    {
        $jsonArray=array();
        while($row=$result->fetch_assoc())
        {
			$start=$row['evt_start_date'] . "T" . $row['evt_start_time'];
			$end=$row['evt_end_date'] . "T" . $row['evt_end_time'];
            $subArray=array("id"=>$row['eventId'], "org_id" => $row['org_id'], "title" => $row['evt_name'], 
                "desc"=>$row['evt_desc'], "room"=>$row['evt_room'],"start" => $start, "end" => $end, "link" => $row['evt_url']);
	    $jsonArray[]=$subArray;
        }
        echo json_encode($jsonArray);
    }
    $con->close();
?>
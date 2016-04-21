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
    $sql="SELECT * FROM ltuevents ORDER BY evtOrgId";
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
  	    $start=$row['evtDate'] . "T" . $row['evtTime'];
            $end=$row['evtEndDate'] . "T" . $row['evtEndTime'];
            $subArray=array("id" => $row['evtOrgId'], "title" => $row['evtName'], 
                "start" => $start, "end" => $end, "url" => $row['evtURL']);
	    $jsonArray[]=$subArray;
        }
        echo json_encode($jsonArray);
    }
    $con->close();
?>
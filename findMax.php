<!DOCTYPE html>
<html>
<body>
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
    $sql="SELECT COUNT(*) FROM ltuevents WHERE evt_visible=false";
    $result=$con->query($sql);
    if($result->num_rows==0)
    {
         //do nothing
    }
    else
    {
        $row=$result->fetch_array();
        $maxPage=$row[0]/20;
        echo $maxPage;
    }

    $con->close();
?>
</body>
</html>
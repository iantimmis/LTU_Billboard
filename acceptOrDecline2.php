<!DOCTYPE html>
<html>
<body>
  <!-- Handles accepting or declining an event or organization request -->
  <?php
    $action=intval($_POST["a"]);
    $showType=intval($_POST["type"]);
    $checkedIds=preg_split("/,/", ($_POST["data"]));
    $servername="localhost";
    $username="root";
    $password="root";
    $dbname="LTUBillboard";

    $con=new mysqli($servername, $username, $password, $dbname);
    if($con->connect_error)
    {
        die("Connection failed:" . $con->connect_error);
    } 
    if($showType==0)
    {
        if($action==1)
        {
            foreach($checkedIds as $id)
            {
                $sql="UPDATE ltuevents
                      SET evt_visible=1
                      WHERE eventId=" . intval($id);
                $con->query($sql);
            }
        }
        else
        {
            foreach($checkedIds as $id)
            {
                $sql="DELETE FROM ltuevents
                      WHERE eventId=" . intval($id);
                $con->query($sql);
            }
        }
    }
    else
    {
        if($action==1)
        {
            foreach($checkedIds as $id)
            {
                $sql="UPDATE ltuorganization
                      SET org_accepted=true 
                      WHERE orgId=" . intval($id);
                $con->query($sql);
            }
        }
        else
        {
            foreach($checkedIds as $id)
            {
                $sql="DELETE FROM ltuorganization
                      WHERE orgId=" . intval($id);
                $con->query($sql);
            }
        }
    }
    echo "";
    $con->close(); 
  ?>
</body>
</html>

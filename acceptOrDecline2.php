<!DOCTYPE html>
<html>
<body>
  <?php
    $action=intval($_POST["a"]);
    $showType=intval($_POST["type"]);
    $checkedIds=preg_split("/,/", ($_POST["data"]));
    $servername="localhost";
    $username="root";
    $password="root";
    $dbname="LTUBillboard";

/*FIND WAY TO HANDLE SQL QUERY ERRORS AS WELL AS TRACK HOW MANY REQUEST WERE FOUND (IF FOUND)
  IMPLEMENT SQL QUERY */


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
                      SET evt_visible=true 
                      WHERE eventId=" . intval($id);
                $con->query($sql);
            }
            /*echo "___ event requests were found and accepted.";*/
        }
        else
        {
            foreach($checkedIds as $id)
            {
                $sql="DELETE FROM ltuevents
                      WHERE eventId=" . intval($id);
                $con->query($sql);
            }
            /*echo "___ event requests were found and deleted.";*/
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
            /*echo "___ organization requests were found and accepted.";*/
        }
        else
        {
            foreach($checkedIds as $id)
            {
                $sql="DELETE FROM ltuorganization
                      WHERE orgId=" . intval($id);
                $con->query($sql);
            }
            /*echo "___ organization requests were found and deleted.";*/
        }
    }
    echo "";
    $con->close(); 
  ?>
</body>
</html>
<!DOCTYPE html>
<html>
<body>
<!--Edit existing announcement -->
<?php
    $editAnnouncement=$_POST['editAnn'];
    $anId=intval($_POST['annId']);
    $servername="localhost";
    $username="root";
    $password="root";
    $dbname="LTUBillboard";

    $con=new mysqli($servername, $username, $password, $dbname);
    if($con->connect_error)
    {
        die("Connection failed:" . $con->connect_error);
    }
    $sql="UPDATE admin_announcements 
          SET announce_desc ='" . $editAnnouncement . "'" . 
          "WHERE announceId=" . $anId;
    if ($con->query($sql) === TRUE)
    {
        echo "Record successfully edited.";
    } 
    else
    {
	echo "Error: " . $sql . "<br>" . $con->error;
    }
    $con->close();
    header("Location: admin.php");
?>
</body>
</html>
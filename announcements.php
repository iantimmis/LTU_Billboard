<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="favicon.ico"/>
</head>
<body>
<!-- Add new announcement -->
<?php
    $sourcePage = $_POST['source'];
    $newAnnouncement=$_POST['addAnn'];
    $servername="localhost";
    $username="root";
    $password="root";
    $dbname="LTUBillboard";

    $con=new mysqli($servername, $username, $password, $dbname);
    if($con->connect_error)
    {
        die("Connection failed:" . $con->connect_error);
    }
        $sql="INSERT INTO admin_announcements (announce_desc) VALUES ('" . $newAnnouncement . "')";
        if ($con->query($sql) === TRUE)
        {
            echo "New record created successfully";
        } 
        else
        {
	   echo "Error: " . $sql . "<br>" . $con->error;
        }
        $con->close();
        header("Location: {$sourcePage}");
?>
</body>
</html>

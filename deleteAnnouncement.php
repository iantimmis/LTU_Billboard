<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="favicon.ico"/>
</head>
</head>
<body>
<!-- Delete selected announcement -->
<?php
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
    $sql="DELETE FROM admin_announcements 
          WHERE announceId=" . $anId;
    if ($con->query($sql) === TRUE)
    {
        echo "Record successfully removed.";
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

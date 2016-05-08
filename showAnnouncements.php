<!-- Shows current announcements -->
<?php
    function showAnnounce()
    {
    $servername="localhost";
    $username="root";
    $password="root";
    $dbname="LTUBillboard";

    $con=new mysqli($servername, $username, $password, $dbname);
    if($con->connect_error)
    {
        die("Connection failed:" . $con->connect_error);
    }
    $sql="SELECT * FROM admin_announcements";
    $result=$con->query($sql);
    if($result->num_rows==0)
    {
        echo "<h4>No announcements</h4>";
    }
    else
    {
        while($row=$result->fetch_assoc())
        {
            echo "<li>" . $row['announce_desc'] . "</li>";         
        }
    }
    $con->close();
    }
?>

<?php
    function upcomingEvents()
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
    $sql="SELECT evt_name, evt_start_date, evt_url FROM ltuevents 
          WHERE ( ( DAY(evt_start_date) >= DAY(CURDATE()) ) AND
                  ( MONTH(evt_start_date) = MONTH(CURDATE()) ) AND
                  ( YEAR(evt_start_date) = YEAR(CURDATE()) )
                )
          OR
                (
                  ( MONTH(evt_start_date) > MONTH(CURDATE()) ) AND
                  ( YEAR(evt_start_date) = YEAR(CURDATE()) ) 
                )
          OR
                YEAR(evt_start_date) > YEAR(CURDATE())  
          ORDER BY evt_start_date ASC LIMIT 0, 5";
    $result=$con->query($sql);
    if($result->num_rows==0)
    {
        echo "<h4>No upcoming events</h4>";
    }
    else
    {
        while($row=$result->fetch_assoc())
        {
            $newDate= new DateTime($row['evt_start_date']);
            echo "<li><a href='" . $row['evt_url'] . "'>" . $newDate->format('F j') .
                 ": " . $row['evt_name'] . "</a></li>"; 
                
        }
    }
    $con->close();
    }
?>
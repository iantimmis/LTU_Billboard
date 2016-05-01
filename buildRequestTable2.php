<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <style>
    table{
      width:100%;
      background-color:#f3f7ff;
      color:black;
      border-collapse:collapse;
      padding:0;
    }
    tr, td{
      border-bottom: 0.1em solid white;
    }
    td, th{
      text-align:left;
      verticle-align:center;
      padding:0.25em;
      font-size:1.05em;
      white-space:nowrap;
      text-overflow:ellipsis;
      overflow:hidden;
    }
    #noEvents, #noOrgs{
      color:black;
      align:center;
    }
  </style>
</head>
<body>
<?php
    $currpage=intval($_GET["page"]);
    $sortBy=intval($_GET["sort"]);
    $showType=intval($_GET["type"]);
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
        if($sortBy==0)
        {   
            $sql="SELECT * FROM ltuevents WHERE evt_visible=false ORDER BY eventId DESC LIMIT " . (20*$currpage) . ",20 ";  
        }
        else
        {  
            $sql="SELECT * FROM ltuevents WHERE evt_visible=false ORDER BY evt_start_date ASC LIMIT " . (4*$currpage) . ",20 ";
        }
        $result=$con->query($sql);
        if($result->num_rows==0)
        {
            echo "<h3 id='noEvents'>No new events</h3>";
        }
        else
        {  
            echo "<div id='rTable'>";
            echo "<table id='reqs'>\n";
            echo "<tr><th style='width:5%;'>Id</th><th style='width:35%;'>Name</th><th style='width:25%;'>Type</th>
                  <th style='width:15%;'>Date</th><th style='width:15%;'>Time</th>
                  <th style='width:5%;'>Y/N</th></tr>";
            $count=1;
            while($row=$result->fetch_assoc())
            {
                $newDate= new DateTime($row['evt_start_date']);
                $newTime= new DateTime($row['evt_start_time']);

                echo "<tr><td class='reqNum'>" . $row['eventId']. "</td><td>" . $row['evt_name'] . "</td><td>" . 
                    $row['evt_category'] . "</td><td>" . $newDate->format('n-j-y') . 
                    "</td><td>" . $newTime->format('g:ia') . 
                    "</td><td><input class='checks' id='" . $count . "' type='checkbox' /></td></tr>\n";
                $count++;
            }
            echo "</table>"; 
            echo "</div>";
        }
    }
    else
    {
        $sql="SELECT * FROM ltuorganization WHERE org_accepted=false ORDER BY orgId DESC LIMIT " . (20*$currpage) . ",20 ";  
        $result=$con->query($sql);
        if($result->num_rows==0)
        {
            echo "<h3 id='noOrgs'>No new organizations</h3>";
        }
        else
        {   
            echo "<div id='rTable'>";
            echo "<table id='reqs'>\n";
            echo "<tr><th style='width=5%;'>Id</th><th style='width:25%;'>Name</th><th style='width:25%;'>Description</th>
                  <th style='width:20%;'>Website</th><th style='width:20%;'>Email</th>
                  <th style='width:5%;'>Y/N</th></tr>";
            $count=1;
            while($row=$result->fetch_assoc())
            {
                echo "<tr><td class='reqNum'>" . $row['orgId'] . "</td><td>" . $row['org_name'] . "</td><td>" . 
                    $row['org_description'] . "</td><td>" . $row['org_website'] . 
                    "</td><td>" . $row['org_email'] . 
                    "</td><td><input class='checks' id='" . $count . "' type='checkbox' /></td></tr>\n";	
                $count++;
            }
            echo "</table>"; 
            echo "</div>";
        }
    }
    $con->close();
?>
</body>
</html>

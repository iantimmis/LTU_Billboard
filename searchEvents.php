<!DOCTYPE html>
<html>
<head>
  <link href="fcStylesheet.css" rel="stylesheet" type="text/css" />
  <link href="adminStylesheet.css" rel="stylesheet" type="text/css" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link href="bootstrap.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="jquery-2.2.2.min.js"></script>
	<script src='jquery.min.js'></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
	<script src='moment.min.js'></script>
	<script src='fullcalendar.js'></script>
	<script type="text/javascript" src="bootstrap.min.js"></script>
  <style>
    #searchName
    {
      font-size:1.2em;
      text-decoration:underline;
    }
    #searchRes
    {
      height:30em;
      max-height:30em;
      overflow-y:auto;
      overflow-x:auto;
    }
  </style>
  <script>
    /*Show modal when search phrase is submitted*/
    function loading()
    {
      $('#searchModal').modal('show');
    }
  </script>
</head>
<body onload="loading()"> 
<!-- Simple search through the names of only accepted events -->
<?php
    $servername="localhost";
    $username="root";
    $password="root";
    $dbname="LTUBillboard";

    $phrase=$_POST['keywords'];
    $searches=explode(" ", $phrase); /*Split up each word from search*/

    $searchWords=implode("%' AND evt_name LIKE '%", $searches);  /*Combine into one string that includes sql wildcards and LIKE operator*/
    $con=new mysqli($servername, $username, $password, $dbname);
    if($con->connect_error)
    {
        die("Connection failed:" . $con->connect_error);
    } 

    /* -- searchModal -- */
    echo "<div id='searchModal' class='modal fade' role='dialog'>
           <div class='modal-dialog'>
           <div class='modal-content'>
           <div class='modal-header'>
             <h4 class='modal-title'>Search Results</h4>
           </div>
           <div class='modal-body'>
             <div id='searchRes'>";   

        $sql="SELECT * FROM ltuevents WHERE evt_name LIKE '%$searchWords%' AND evt_visible=1"; /*Add opening and closing wildcards before query*/
        $result=$con->query($sql);
        if($result->num_rows==0)
        {
            echo "No events matched your search.";
        } 
        else
        {
	   while($row=$result->fetch_assoc())
           {
               $newDate= new DateTime($row['evt_start_date']);
               $newTime= new DateTime($row['evt_start_time']);

               $sql2="SELECT org_name FROM ltuorganization WHERE orgId=" . $row['org_id'];
               $result2=$con->query($sql2);

               echo "<span id='searchName'>" . $row['evt_name'] . "</span><br/>
                     <b>Date:</b> " . $newDate->format('n-j-y') . "<br/>
                     <b>Time:</b> " . $newTime->format('g:ia') . "<br/>
                     <b>Room:</b> " . $row['evt_room'] . "<br/>
                     <b>Description:</b> " . $row['evt_desc'] . "<br/>";
                     if($result2->num_rows>0)
                     {
                         $row2=$result2->fetch_array();
                         echo "<b>Organization:</b> " . $row2[0] . "<br/>";
                     }
                     if($row['is_private']==0)
                     {
                         echo "<b>Private: </b>No<br/>";
                     }
                     else
                     {
                         echo "<b>Private: </b>Yes<br/>";
                     }
               echo "<b>Website: </b><a href='" . $row['evt_url'] . "'>" . $row['evt_url'] . "</a><br/><br/>";                    
           }
        }
        echo "</div><a class='btn btn-primary' href='admin.php' role='button'>Return to Admin Page</a>";
        echo "</div></div></div></div>";
        /* -- end of searchModal -- */

        $con->close();
?>
</body>
</html>
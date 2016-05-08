<!-- Both functions build dynamic content to be displayed inside their respective modal -->
<?php   
   function modalTable()
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
            echo "<h4 class='modal-title'>No current announcements</h4>";
        }
        else
        {  
            /*Builds table of current announcements*/
            echo "<div class='table-responsive'>
              <table class='table'>
                <thead>
                  <th style='width:10%;'>Id</th><th style='width:60%;'>Announcement</th>
                  <th style='width:15%;'>Show/Hide</th><th style='width:15%;'>Delete</th>
                </thead>
                <tbody>";
            $count=0;
            $ids=array();
            while($row=$result->fetch_assoc())
            {
                echo "<tr><td>" . $row['announceId'] . "</td><td>" . $row['announce_desc'] . "</td>
                      <td><a id='showHide' href='#openEdit" . $row['announceId'] . "' data-toggle='collapse'>
                          <span class='glyphicon glyphicon-plus' aria-hidden='true'></span><span> / </span>
                          <span class='glyphicon glyphicon-minus' aria-hidden='true'></span></a></td>";

                 echo "<td><form action='deleteAnnouncement.php' method='POST' role='form'>
                        <button type='submit' class='btn btn-link' name='annId' value='" . $row['announceId'] . "'>Delete</button>
                     </form></td></tr>";
                    
                $ids[]=$row['announceId'];
                $count++;    
            }
            echo "</tbody></table></div>";

            /*Builds collapse sections to edit or delete an announcement*/
            for($i=0; $i<$count; $i++)
            {
               echo "<div id='openEdit" . $ids[$i] . "' class='collapse'>   
                       <form action='editAnnouncements.php' method='POST' role='form'>
                         <div class='form-group'>
                           <label for='editAnn'>Edit Announcement " . $ids[$i] . "</label>
                           <textarea class='form-control' rows='6' id='editAnn' name='editAnn'>";
                $sql="SELECT announce_desc FROM admin_announcements WHERE announceId=" . $ids[$i];
                $result=$con->query($sql);
                if($result->num_rows==0)
                {
                    echo "Error. Announcement not found.";
                }
                else
                {
                    $ann=$result->fetch_array(); 
                    echo $ann[0];
                } 
                echo "</textarea></div>
                      <button type='submit' class='btn btn-default' name='annId' value='" . $ids[$i] . "'>Confirm Changes</button>
                      </form></div>";
            }
        }
        $con->close();
    }

    function buildOrgList()
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
        $sql="SELECT * FROM ltuorganization";
        $result=$con->query($sql);
        if($result->num_rows==0)
        {
            echo "<h4 class='modal-title'>No current organizations</h4>";
        }
        else
        {  
            /*Builds table of current organizations*/
            echo "<div class='table-responsive'>
              <table class='table'>
                <thead>
                  <th>Id</th><th>Name</th><th>Show/Hide</th>
                </thead>
                <tbody>";
            $count=0;
            $ids=array();
            while($row=$result->fetch_assoc())
            {
                echo "<tr><td>" . $row['orgId'] . "</td><td>" . $row['org_name'] . "</td>
                      <td><a id='showHide' href='#openInfo" . $row['orgId'] . "' data-toggle='collapse'>
                          <span class='glyphicon glyphicon-plus' aria-hidden='true'></span><span> / </span>
                          <span class='glyphicon glyphicon-minus' aria-hidden='true'></span></a></td></tr>";

                $ids[]=$row['orgId'];
                $count++;    
            }
            echo "</tbody></table></div>";

            /*Builds collapse sections to show more information on an organization*/
            for($i=0; $i<$count; $i++)
            {
                echo "<div id='openInfo" . $ids[$i] . "' class='collapse'>   ";
                $sql="SELECT * FROM ltuorganization WHERE orgId=" . $ids[$i];
                $result=$con->query($sql);
                if($result->num_rows==0)
                {
                    echo "Error. Organization not found.";
                }
                else
                {
                    $org=$result->fetch_assoc(); 
                    echo "<span id='orgTitle'>Organization " . $ids[$i] . "</span><br/>";
                    echo "<b>Name:</b> " . $org['org_name'] . "<br/>";
                    echo "<b>Description:</b> " . $org['org_description'] . "<br/>";
                    echo "<b>Website:</b> " . $org['org_website'] . "<br/>";
                    echo "<b>Email:</b> " . $org['org_email'];
                } 
                echo "</div>";   
            }
        }
        $con->close();
    }
?>

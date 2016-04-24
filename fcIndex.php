<?php
	session_start();
	$thisPage = "fcIndex.php";
	//check session to see if logged in and user and get info if true
	$loggedInAsUser = false;
	$loggedInAsOrg = false;
	if (isset($_SESSION['userId'])){
		$userInfo['userId'] = $_SESSION['userId'];
		$userInfo['firstName'] = $_SESSION["firstName"];
		$userInfo['lastName'] = $_SESSION["lastName"];
		$userInfo['isAdmin'] = $_SESSION['isAdmin'];
		$userId = $userInfo['userId'];
		$message  = $userInfo['firstName'] . " " . $userInfo['lastName'];
		$loggedInAsUser = true;
	} elseif (isset($_SESSION['orgId'])) {
		$orgInfo['id'] = $_SESSION['orgId'];
		$orgInfo['name'] = $_SESSION['orgName'];
		$orgInfo['desc'] = $_SESSION['orgDesc'];
		$orgInfo['website'] = $_SESSION['orgWebsite'];
		$loggedInAsOrg = true;
		$message = $orgInfo['name'];
	} else {
		$message = "No One";
	}
	$loggedIn = $loggedInAsOrg || $loggedInAsUser;
?>
<!DOCTYPE html>
<html>
<head>
  <title>LTU Billboard</title>
  <link href="fcStylesheet.css" rel="stylesheet" type="text/css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  
  <link href="bootstrap.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="jquery-2.2.2.min.js"></script>
	<script type="text/javascript" src="bootstrap.min.js"></script>
  <link rel='stylesheet' href='fullcalendar.css' />
  <script src='jquery.min.js'></script>
  <script src='moment.min.js'></script>
  <script src='fullcalendar.js'></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#calendar').fullCalendar({
        header:{
          left: "prev,next today",
          center:"title",
          right:"month,basicWeek,basicDay"
        },
        eventLimit:true,
        eventColor:"#004299",
        timeFormat:"h:mmt",
        eventSources:[
          {
            url:'fcEvents.php',	
          },
        ],
        events:[
          {
            title:"Spring 2016 Job Fair",
            start:"2016-03-01T09:00:00",
            end:"2016-03-01T16:00:00",
            className:"fair"
          },
          {
            title:"Career Center Management Webinar",
            start:"2016-03-01T18:00:00",
            end:"2016-03-01T20:00:00"
          },
          {
            title:"Distinguished Lecturer",
            start:"2016-03-24T18:00:00",
            end:"2016-03-24T20:00:00",
            url:"eventpage.html"
          },
          {
            title:"Computer Science Seminar: Android Auto",
            start:"2016-03-30T12:30:00",
            end:"2016-03-30T13:30:00",
            url:"eventpage.html"
          },
          {
            title:"Blue and White Days",
            start:"2016-04-01"
          },
          {
            title:"Semi-Annual Code Challenge",
            start:"2016-04-03T08:30:00"
          },         
       ],
       eventClick:function(event){
         if(event.url){
           window.open(event.url, "_blank", "scrollbars=yes,resizable=yes,width=600,height=600,top=50,left=50");
           return false;
         }
       } 
      })
    });
  </script>	

<?php require 'requiredHeader.php';?>
  <div id="theWrap">
    <div id="topWrap">
      <div class="subheader">
        <span class="subTitle">Announcements</span>
        <ul class="subList">
          <li>Mid-Semester break runs from March 7 to March 11.</li>
          <li>Classes resume March 14.</li>
        </ul>
      </div>
      <div class="subheader">
        <span class="subTitle">Upcoming Events</span>
        <ul class="subList">
          <li><a href="eventpage.html">March 24: Distinguished Lecturer</a></li>
          <li><a href="">March 30: Computer Science Seminar: Android Auto</a></li>
          <li><a href="">April 1: Blue and White Days</a></li>
          <li><a href="">April 3: Semi-Annual Code Challenge</a></li>
        </ul>
      </div>
    </div>
    <div id="calendarWrapper">
      <div  id="dropdown">
        Filter By:&nbsp;
        <select>
          <option>Architecture + Design</option>
          <option>Arts + Science</option>
          <option>Engineering</option>
          <option>Student Interests</option>
        </select>
      </div>
    </div>
    <div id="calWrap">
      <div id='calendar'></div>
    </div>
  </div>
  <div id="bottomWrapper">
    <footer>
      Created By: Matthew Castaldini, Hanan Jalnko, Kathleen Napier, Ian Tammis
    </footer>
  </div>
</body>
</html>


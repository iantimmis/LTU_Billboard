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
  <link rel='stylesheet' href='fullcalendar.css' />
  <script src='jquery.min.js'></script>
  <script src='moment.min.js'></script>
  <script src='fullcalendar.js'></script>
  <script type="text/javascript" src="bootstrap.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#calendar').fullCalendar({
        eventClick:  function(event, jsEvent, view) {
			
            $('#eventModalLabel').html(event.title);
            $('#modalDesc').html(event.desc);
			$('#modalDateTime').html(event.end);
			$('#modalOrgNum').html(event.org_id);
			$('#modalRoom').html(event.room)
            $('#modalEvtLink').attr('href',event.link);
            $('#eventModal').modal();
			return false;
        },
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
          }         
       ]
      })
	  
	  $("#orgAct").hide();
				$("input[name=actType]").on( "change", function() {
					var target = $(this).val();
					$(".chooseActType").hide();
					$("#"+target).show();
				});
    }
	);
  </script>	
	</head>
	<body>
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
	<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title" id="eventModalLabel"></h2>
		  </div>
		  <div class="modal-body">
			<div id="modalDesc" align="center"></div>
			<table class="info">
			<tr>
				<td>Start Time: <span id="modalDateTime"></span></td>
				<td>Org Number: <span id="modalOrgNum"></span></td>
			</tr>
			<tr>
				<td>Room: <span id="modalRoom"></span></td>
				<td>Link: <a class="orglink" id="modalEvtLink" target="_blank">link</a></td>
			</tr>
			</table>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-primary" data-dismiss="modal">Add to Calendar</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>	
  <div id="bottomWrapper">
    <footer>
      Created By: Matthew Castaldini, Hanan Jalnko, Kathleen Napier, Ian Tammis
    </footer>
  </div>
</body>
</html>


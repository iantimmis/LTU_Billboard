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
	require_once 'mobile_detect.php';//required file for checking for mobile
	$detect = new Mobile_Detect;//variable for mobile detection
	if($detect->isMobile()){echo "ismobile";}//if mobile
	if($detect->isTablet()){}//if tablet
	//http://mobiledetect.net/
	$filterSet= isset($_SESSION['filter']);
	if($filterSet)
		$filter = $_SESSION['filter'];
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
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
	<script src='moment.min.js'></script>
	<script src='fullcalendar.js'></script>
	<script type="text/javascript" src="bootstrap.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#calendar').fullCalendar({
				eventClick:  function(event, jsEvent, view) {
					
					$('#eventModalLabel').html(event.title);
					$('#modalDesc').html(event.desc);
					$('#modalDate').html(event.date);
					$('#modalStartTime').html(event.start_time);
					$('#modalEndtime').html(event.end_time);
					$('#modalOrgName').html(event.org_name);
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
			
			$("#createStuAct").validate({
				"rules" : {
					"stuPassword" : {
						"minlength" : 8},
					"confirmStuPassword" : {
						"equalTo" : "#stuPassword"}
				}
			});
			
			$("#selectId").on("change", function(){
				$("#dropdown").submit();
			});
		});//end of doc.ready
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
      <form method="get" action="changeFilter.php" id="dropdown">
        Filter By:&nbsp;
        <select id="selectId" name="filter">
			<option value="none" <?php if($filterSet){if(strcmp($filter,'none')==0){echo "selected";}}?> >Show All</option>
			<option value="arch" <?php if($filterSet){if(strcmp($filter,'arch')==0){echo "selected";}}?> >Architecture + Design</option>
			<option value="arts" <?php if($filterSet){if(strcmp($filter,'arts')==0){echo "selected";}}?> >Arts + Science</option>
			<option value="eng" <?php if($filterSet){if(strcmp($filter,'eng')==0){echo "selected";}}?> >Engineering</option>
			<option value="stud" <?php if($filterSet){if(strcmp($filter,'stud')==0){echo "selected";}}?> >Student Interests</option>
			<?php if($loggedInAsUser){
					if($filterSet){
						if(strcmp($filter,'mine')==0)
							{echo "<option value='mine' selected> I've signed up for</option>";}
					}
					else{ echo "<option value='mine'> I've signed up for</option>";}
				}?>
        </select>
      </form>
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
			<br />
			<div class="row">
				<div class="col-sm-6" align="center"><span id="modalDate"></span><br /><span id="modalStartTime"></span> to <span id="modalEndtime"></span></div>
				<div class="col-sm-6" align="center">Organization: <span id="modalOrgName"></span></div>
			</div>
			<br />
			<div class="row">
				<div class="col-sm-6" align="center">Room: <span id="modalRoom"></span></div>
				<div class="col-sm-6" align="center">Link: <a class="orglink" id="modalEvtLink" target="_blank">link</a></div>
			</div>
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
      Created By: Matthew Castaldini, Hanan Jalnko, Kathleen Napier, Ian Timmis
    </footer>
  </div>
</body>
</html>


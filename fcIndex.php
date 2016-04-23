<?php
	session_start();

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
</head>
<body>
  <header>
    <?php if($loggedInAsOrg) echo "<a href='createEvent.php'><button class='event'>Create Event</button></a>";?>
	<a href="fcIndex.php" id=logo>LTU Billboard</a>
    <?php if(!$loggedIn): ?>
    <span class="log-in">
      <button id="loginButton" data-toggle="modal" data-target="#loginModal">Log In</button>
      <br/>
      Not a user? <a href="">Join Now</a>
    </span>
	<?php else: ?>
	<span class="log-in">
	<form action="logout.php" method="post" role="form">
      <button id="logoutButton" type="submit">Log Out</button>
	  </form>
    </span>
	<?php endif ?>
    <br/>
    <br/>
    <br/>
  </header>
  <?php if(!$loggedInAsOrg && !$loggedInAsUser): ?>
	<!-- Modal -->
	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <!-- Modal header with close button and title-->
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title" id="loginModal">Log-In or Create Account</h2>
		  </div>
		  
		  <!-- Modal body -->
		  <div class="modal-body">
		  
			<!-- Tabs for either log-in or account creation -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#loginAsStudent" aria-controls="loginAsStudent" role="tab" data-toggle="tab">Log-In As Student</a></li>
				<li role="presentation"><a href="#loginAsOrg" aria-controls="loginAsOrg" role="tab" data-toggle="tab">Log-In As Organization</a></li>
			</ul>
			
			<!-- Div for tab content -->
			<div class="tab-content">
			<!-- First tab for account creation form -->
				
				
				
				<!-- Second tab for student log-in form -->
				<div role="tabpanel" class="tab-pane active" id="loginAsStudent"><br>
				<!--Form for logging in. Just takes username, password -->
				<form action="loginAsStudent.php" method ="post" role="form">
				  <!-- Username row -->
				  <div class="form-group row">
					<label for="studentEmail" class="col-sm-4 form-control-label" align="right">Email</label>
					<div class="col-sm-7">
					  <input type="email" class="form-control" id="studentEmail" name="studentEmail" placeholder="user@example.com">
					</div>
				  </div>
				  <!-- Password row -->
				  <div class="form-group row">
					<label for="studentPassword" class="col-sm-4 form-control-label" align="right">Password</label>
					<div class="col-sm-5">
					  <input type="password" class="form-control" id="studentPassword" name="studentPassword" placeholder="Password">
					</div>
				  </div>
				  <!-- Submit button -->
					<hr />
				  <div class="form-group row">
					<div class="col-sm-12" align="right">
						<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="submit">Submit</button>
						<button type = "submit" class ="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
				</div>
				</form>
				<!-- End of log-in form and 2nd tab -->
				</div>
				
				<!-- Third tab for Organization log-in form -->
				<div role="tabpanel" class="tab-pane" id="loginAsOrg"><br>
				<!--Form for logging in. Just takes username, password -->
				<form action="loginAsOrg.php" method ="post" role="form">
				  <!-- Username row -->
				  <div class="form-group row">
					<label for="orgEmail" class="col-sm-4 form-control-label" align="right">Email</label>
					<div class="col-sm-7">
					  <input type="Email" class="form-control" id="orgEmail" name ="orgEmail" placeholder="org@example.com">
					</div>
				  </div>
				  <!-- Password row -->
				  <div class="form-group row">
					<label for="orgPassword" class="col-sm-4 form-control-label" align="right">Password</label>
					<div class="col-sm-5">
					  <input type="password" class="form-control" id="orgPassword" name="orgPassword" placeholder="Password">
					</div>
				  </div>
				<!-- Submit button -->
					<hr />
				  <div class="form-group row">
					<div class="col-sm-12" align="right">
						<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="submit">Submit</button>
						<button type = "submit" class ="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
				</div>
				</form>
				<!-- End of log-in form and 3rd tab -->
				</div>
				
				
				
			</div>
		  </div>
		  
		</div>
	  </div>
	</div>
	<?php endif ?>
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


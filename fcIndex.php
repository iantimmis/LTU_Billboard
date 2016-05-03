<?php
	session_start();
	$thisPage = "fcIndex.php";
	
	//data validation for logging in
	$email = $password = $type = "";
	$emailErr = $passwordErr = $loginMessage = "";
	$loginAttempted = $loginSuccess = false;
	$servername = "localhost";
	$dbusername = "root";
	$dbpassword = "root";
	$dbname = "LTUBillboard";
	// Create connection
	$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$loginAttempted = true;
		if(!empty($_POST['type'])){$type = $_POST['type'];}
		if(strcmp($type,'stu')==0)//logging in as student
		{
			if(empty($_POST['studentEmail']))
				$emailErr = "Email is Required";
			elseif(empty($_POST['studentPassword']))
				$passwordErr = "Password is Required";
			else
			{
				$email = cleanInput($_POST['studentEmail'],$dbname);
				$password = cleanInput($_POST['studentPassword'],$dbname);
				$sql = "SELECT * FROM user_account WHERE user_email='{$email}' AND login_password='{$password}';";
				$result = $conn->query($sql);
				if($result->num_rows==0){$loginMessage="Login Failed";}
				else
				{
					$loginSuccess = true;
					$userInfo = $result->fetch_assoc();
					$_SESSION['userId'] = $userInfo['userId'];
					$_SESSION["firstName"] = $userInfo['first_name'];
					$_SESSION["lastName"] = $userInfo['last_name'];
					$_SESSION['isAdmin'] = $userInfo['is_admin'];
					$_SESSION['userEmail'] = $userInfo['user_email'];
					$loginMessage = "Login Successful";
				}
			}
		}
		if(strcmp($type,'org')==0)//logging in as an organization
		{
			if(empty($_POST['orgEmail']))
				$emailErr = "Email is Required";
			elseif(empty($_POST['orgPassword']))
				$passwordErr = "Password is Required";
			else
			{
				$email = cleanInput($_POST['orgEmail'],$dbname);
				$password = cleanInput($_POST['orgPassword'],$dbname);
				$sql = "SELECT * FROM ltuorganization WHERE org_email='{$email}' AND login_password='{$password}';";
				$result = $conn->query($sql);
				if($result->num_rows==0){$loginMessage="Login Failed";}
				else
				{
					$loginSuccess = true;
					$orgInfo=$result->fetch_assoc();
					$_SESSION['orgId'] = $orgInfo['orgId'];
					$_SESSION["orgName"] = $orgInfo['org_name'];
					$_SESSION["orgDesc"] = $orgInfo['org_description'];
					$_SESSION['orgWebsite'] = $orgInfo['org_website'];
					$_SESSION['orgEmail'] = $orgInfo['org_email'];
					$loginMessage = "Login Successful";
				}
			}
		}
	}
	
	function cleanInput($input,$db){
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		$input = mysqli_real_escape_string($db,$input);
      	return $input;
	}
	$conn->close();
	
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
	
	
	//Checking if mobile user
	require_once 'mobile_detect.php';//required file for checking for mobile
	$detect = new Mobile_Detect;//variable for mobile detection
	$isMobile = $detect->isMobile();
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
				]
			})//end of full calendar
			
			//used for create account panel radio buttons
			$("#orgAct").hide();
			$("input[name=actType]").on( "change", function() {
				var target = $(this).val();
				$(".chooseActType").hide();
				$("#"+target).show();
			});
			
			//validation for student account creation
			$("#createStuAct").validate({
				"rules" : {
					"confirmStuPassword" : {
						"equalTo" : "#stuPassword"}
				}
			});
			$("#createOrgAct").validate({
				"rules" : {
					"confirmOrgPassword" : {
						"equalTo" : "#orgPassword"}
				}
			});
			
			$("#studentForm").validate({});
			$("#orgForm").validate({});
			//used for the calendar filter
			$("#selectId").on( "change", function(){
				$("#dropdown").submit();
			});
			
			$("#createAccountLink").on("click", function(){
				$('#loginModal').modal('show');
				$('#loginTabs a:last').tab('show');
			});
			<?php if($loginAttempted):?>
				<?php if(strcmp($type,'stu')==0):?>
					<?php if(!$loginSuccess):?>
			//login student fail
			$('#loginModal').modal('show');
			$('#stuLoginMessage').html("Login Failed");
			$('#stuLoginMessage').toggleClass('error');
					<?php endif; ?>
				<?php elseif(strcmp($type,'org')==0): ?>
					<?php if(!$loginSuccess):?>
			$('#loginModal').modal('show');
			$('#loginTabs a[href="#loginAsOrg"]').tab('show')
			$('#orgLoginMessage').html("Login Failed");
			$('#orgLoginMessage').toggleClass('error');
					<?php endif;?>
				<?php endif;?>
			
			<?php endif?>
			
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
        Event Filter:&nbsp;
        <select id="selectId" name="filter">
			<option value="non" <?php if($filterSet){if(strcmp($filter,'non')==0){echo "selected";}}?> >Show All</option>
			<option value="arc" <?php if($filterSet){if(strcmp($filter,'arc')==0){echo "selected";}}?> >Architecture + Design</option>
			<option value="mcs" <?php if($filterSet){if(strcmp($filter,'mcs')==0){echo "selected";}}?> >Arts + Science</option>
			<option value="eng" <?php if($filterSet){if(strcmp($filter,'eng')==0){echo "selected";}}?> >Engineering</option>
			<option value="stu" <?php if($filterSet){if(strcmp($filter,'stu')==0){echo "selected";}}?> >Student Interests</option>
			<?php if($loggedInAsUser){
					if($filterSet){
						if(strcmp($filter,'add')==0)
						{
							echo "<option value='add' selected>Added to Calendar</option>";
							echo "<option value='org'>My Organizations</option>";
							echo "<option value='mine'>My Events</option>";
						}
						elseif(strcmp($filter,'org')==0)
						{
							echo "<option value='add'>Added to Calendar</option>";
							echo "<option value='org' selected>My Organizations</option>";
							echo "<option value='mine'>My Events</option>";
						}
						elseif(strcmp($filter,'mine')==0)
						{
							echo "<option value='add'>Added to Calendar</option>";
							echo "<option value='org'>My Organizations</option>";
							echo "<option value='mine' selected>My Events</option>";
						}
						else
						{ 
							echo "<option value='add'>Added to Calendar</option>";
							echo "<option value='org'>My Organizations</option>";
							echo "<option value='mine'>My Events</option>";
						}
					}
					else
					{
						echo "<option value='add'>Added to Calendar</option>";
						echo "<option value='org'>My Organizations</option>";
						echo "<option value='mine'>My Events</option>";
					}
				}?>
        </select>
      </form>
	  <?php if ($loggedInAsUser): ?>
	  <div id = "privateEvtId">
		Add your own event to the calendar here: <button id="privateEvtButton" data-toggle="modal" data-target="#privateEventModal">Add Event</button>
	  </div>
	  <?php endif?>
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
	<div class="modal fade" id="privateEventModal" tabindex="-1" role="dialog" aria-labelledby="privateEventModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title" id="privateEventModalLabel">Create Private Event</h2>
		  </div>
		  <div class="modal-body">
			<div align="center">Here you can add your own event to your calendar.<br />It won't show up on anyone else's calendar.</div>
			<br />
			<form action="submitEvent.php" method="post" role="form" id="privateEventForm">
				<div class="form-group row">
					<label for="evtName" class="col-sm-3 form-control-label" align="right">Name</label>
					<div class="col-sm-3">
						<input required type="text" class="form-control" id="evtName" name ="evtName" placeholder="Name" />
					</div>
					<label for="evtBuildingRoom" class="col-sm-2 form-control-label" align="right">Location:</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="evtBuildingRoom" name="evtBuildingRoom" placeholder="Location" />
					</div>
				</div>
				<div class="form-group row">
					<label for="evtStartDate" class="col-sm-3 form-control-label" align="right">Start/End Date:</label>
					<div class="col-sm-4">
						<input type="date" class="form-control" id="evtStartDate" name="evtStartDate" />
					</div>
					<div class="col-sm-4">
						<input type="date" class="form-control" id="evtEndDate" name="evtEndDate" />
					</div>
				</div>
				<div class="form-group row">
					<label for="evtStartTime" class="col-sm-3 form-control-label" align="right">Start/End Time:</label>
					<div class="col-sm-4">
						<input type="time" class="form-control" id="evtStartTime" name="evtStartTime" min="0"/>
					</div>
					<div class="col-sm-4">
						<input type="time" class="form-control" id="evtEndTime" name="evtEndTime" min ="0" />
					</div>
				</div>
				<div class="form-group row">
					<label for="evtDesc" class="col-sm-3 form-control-label" align="right">Description:</label>
					<div class="col-sm-8">
						<textarea class="form-control" id="evtDesc" name="evtDesc" rows="3" placeholder="Add any extra information for yourself."></textarea>
					</div>
				<div class="col-sm-1">
					<input type="hidden" class="form-control" id="evtUserId" name="evtUserId" value=<?php echo $userId;?>></input>
				</div>
				</div>
		  </div>
		  <div class="modal-footer">
			<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="fcIndex.php">Add to Calendar</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		  </form>
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


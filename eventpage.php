<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "LTUBillboard";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//check session to see if logged in and user and get info if true
$loggedInAsUser = false;
if (isset($_SESSION['userId'])){
	$userInfo['userId'] = $_SESSION['userId'];
	$userInfo['firstName'] = $_SESSION["firstName"];
	$userInfo['lastName'] = $_SESSION["lastName"];
	$userInfo['isAdmin'] = $_SESSION['isAdmin'];
	$userId = $userInfo['userId'];
	$joinQuery = "SELECT eventId FROM user_event_join where userId={$userId};";
	$joinResult = $conn->query($joinQuery);
	$eventIds = array();
	if ($joinResult->num_rows > 0) {
		while($joinRow = $joinResult->fetch_assoc()) {
			array_push($eventIds,$joinRow['eventId']);
		}
	} else {
		echo "0 results";
	}
	$eventsInfo = array();
	foreach ($eventIds as $evtId){
		$evtQuery = "SELECT * FROM ltuevents where eventId={$evtId};";
		$evtResult = $conn->query($evtQuery);
		if ($evtResult->num_rows > 0) {
			while($evtRow = $evtResult->fetch_assoc()) {
				array_push($eventsInfo,$evtRow);
			}
		} else {
			echo "0 events";
		}
	}
	$numEvents = count($eventsInfo);
	
	$joinQuery2 = "SELECT orgId FROM user_org_join where userId={$userId};";
	$joinResult2 = $conn->query($joinQuery2);
	$orgIds = array();
	if ($joinResult2->num_rows > 0) {
		while($joinRow2 = $joinResult2->fetch_assoc()) {
			array_push($orgIds,$joinRow2['orgId']);
		}
	} else {
		echo "0 results";
	}
	$orgsInfo = array();
	foreach ($orgIds as $orgId){
		$orgQuery = "SELECT * FROM ltuorganization where orgId={$orgId};";
		$orgResult = $conn->query($orgQuery);
		if ($orgResult->num_rows > 0) {
			while($orgRow = $orgResult->fetch_assoc()) {
				array_push($orgsInfo,$orgRow);
			}
		} else {
			echo "0 events";
		}
	}
	$numOrgs = count($orgsInfo);
	
	$conn->close();
	$message  = $userInfo['firstName'] . " " . $userInfo['lastName'];
	$loggedInAsUser = true;
} elseif (isset($_SESSION['orgId'])) {
	$orgInfo['id'] = $_SESSION['orgId'];
	$orgInfo['name'] = $_SESSION['orgName'];
	$orgInfo['desc'] = $_SESSION['orgDesc'];
	$orgInfo['website'] = $_SESSION['orgWebsite'];
	$message = $orgInfo['name'];
} else {
	
	$message = "No One";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
        <link rel="icon" href="favicon.ico"/>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Event Page</title>
	
	<link href="stylesheet.css" rel="stylesheet" type="text/css">
	
	<link href="bootstrap.min.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="jquery-2.2.2.min.js"></script>
	<script type="text/javascript" src="bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function() {
			$("#anEvent").on("click",toggleModal);
		});
		function toggleModal(evt){
			$('#eventModal').modal('toggle');
		}
		$(function() {
			$("#anEvent2").on("click",toggleModal2);
		});
		function toggleModal2(evt){
			$('#eventModal2').modal('toggle');
		}
		$(function() {
			$("#loginId").on("click",toggleLoginModal);
		});
		function toggleLoginModal(evt){
			$('#loginModal').modal('toggle');
		}
    </script>
  
	<style type="text/css">
	a.orglink{
		text-decoration:none;
		color:black;
	}
	a.orglink:hover{
		color:blue;
	}
	body {
		background-color: #0066cc;
		color: #ffffff
	}
	footer{
		position: fixed;
		bottom: 0px;
		text-align: center;
	}
	header{
		text-align: right;
		padding-bottom:0;
		margin-bottom:0;
	}
	div.main {
		padding-top: 25px;
		text-align: center;
	}
	table.info {
		
		width: 100%;
		text-align: center;
		border-collapse: collapse;
		height: 100px;
	}
	p.desc {
		padding-bottom:15px;
		text-align: center;
		border-bottom: solid;
		border-width: 1px;
	}
	.modal-body {
		color:black;
	}
	.modal-title{
		color:black;
	}
  </style>
</head>

<body>
<header>
	Close
</header>
<div class="main">
	<h1><div id="anEvent">Distinguished Lecturer</div></h1>
	<h1><?php echo "Logged in as: {$message}";?></h1>

<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title" id="eventModalLabel">Distinguished Lecturer</h2>
      </div>
      <div class="modal-body">
        <p>David Vaglia presents an overview of the status of electricity generating nuclear power plants around the world and the current status and probable future of nuclear power.<br><br>He will discuss the different nuclear power plant designs in relation to recent nuclear incidents and the impact those incidents have had on the industry.
		<table class="info">
		<tr>
			<td>Thursday, March 24 6-8 p.m.</td>
			<td>College of Engineering</td>
		</tr>
		<tr>
			<td>UTLC, T429</td>
			<td><a class="orglink" href="http://www.ltu.edu/external_attach/images/vaglia_talk.pdf">Download Flier</a></td>
		</tr>
		</table>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal">RSVP</button>
		<button type="button" class="btn btn-primary" data-dismiss="modal">Add to Calendar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php if($loggedInAsUser){for ($eventNum = 0; $eventNum<$numEvents;$eventNum++): ?>
<button type="button" class="btn btn-default" data-toggle="modal" data-target="#eventModal<?php echo $eventNum ?>">
<?php echo $eventsInfo[$eventNum]['evt_name']?></button><br /><br />
<!-- Dynamic Modal -->
<div class="modal fade" id="eventModal<?php echo $eventNum ?>" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel<?php echo $eventNum ?>">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title" id="eventModalLabel<?php echo $eventNum ?>"><?php echo $eventsInfo[$eventNum]['evt_name']?></h2>
      </div>
      <div class="modal-body">
		<?php echo $eventsInfo[$eventNum]['evt_desc']?>
		<table class="info">
		<tr>
			<td><?php echo "Date: {$eventsInfo[$eventNum]['evt_start_date']}<br />Start Time: {$eventsInfo[$eventNum]['evt_start_time']} End Time: {$eventsInfo[$eventNum]['evt_end_time']}"?></td>
			<td><?php echo "Event Org IdNum: {$eventsInfo[$eventNum]['org_id']}"?></td>
		</tr>
		<tr>
			<td><?php echo "Building/Room: {$eventsInfo[$eventNum]['evt_room']}"?></td>
			<td><a class="orglink" href="<?php echo $eventsInfo[$eventNum]['evt_url']?>">Event Page</a></td>
		</tr>
		</table>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal">RSVP</button>
		<button type="button" class="btn btn-primary" data-dismiss="modal">Add to Calendar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endfor;} ?>
<?php if($loggedInAsUser){for ($orgNum = 0; $orgNum<$numOrgs;$orgNum++): ?>
<button type="button" class="btn btn-default" data-toggle="modal" data-target="#orgModal<?php echo $orgNum ?>">
<?php echo $orgsInfo[$orgNum]['org_name']?></button><br /><br />
<!-- Dynamic Modal -->
<div class="modal fade" id="orgModal<?php echo $orgNum ?>" tabindex="-1" role="dialog" aria-labelledby="orgModalLabel<?php echo $orgNum ?>">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title" id="orgModalLabel<?php echo $orgNum ?>"><?php echo $orgsInfo[$orgNum]['org_name']?></h2>
      </div>
      <div class="modal-body">
		<?php echo "Description: {$orgsInfo[$orgNum]['org_description']}"?>
		<table class="info">
		<tr>
			<td><?php echo "Name: {$orgsInfo[$orgNum]['org_name']}"?></td>
			<td><?php echo "Website: {$orgsInfo[$orgNum]['org_website']}"?></td>
		</tr>
		</table>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal">RSVP</button>
		<button type="button" class="btn btn-primary" data-dismiss="modal">Add to Calendar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endfor;} ?>
</div>
<footer>
Matthew Castaldini Hanan Jalnko Kathleen Napier Ian Timmis
</footer>
</table>
</body>
</html>

<?php
session_start();
$thisPage = "dashboard.php";
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
$loggedInAsOrg = false;
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
	function cmpEvent(array $a, array $b) {//supporting function for usort
		if ($a['evt_start_date'] < $b['evt_start_date']) {
			return -1;
		} else if ($a['evt_start_date'] > $b['evt_start_date']) {
			return 1;
		} else {
			return 0;
		}
	}
	usort($eventsInfo, 'cmpEvent');//sorts event info by start date, ascending
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
	function cmpOrg(array $a, array $b) {//supporting function for usort
		if ($a['org_name'] < $b['org_name']) {
			return -1;
		} else if ($a['org_name'] > $b['org_name']) {
			return 1;
		} else {
			return 0;
		}
	}
	usort($orgsInfo, 'cmpOrg');//sorts organizations by name, alphabetically
	$numOrgs = count($orgsInfo);
	
	$conn->close();
	$message  = $userInfo['firstName'] . " " . $userInfo['lastName'];
	$loggedInAsUser = true;
} elseif (isset($_SESSION['orgId'])) {
	$orgInfo['id'] = $_SESSION['orgId'];
	$orgInfo['name'] = $_SESSION['orgName'];
	$orgInfo['desc'] = $_SESSION['orgDesc'];
	$orgInfo['website'] = $_SESSION['orgWebsite'];
	$loggedInAsOrg =true;
	$message = $orgInfo['name'];
} else {
	
	$message = "No One";
}

$loggedIn = $loggedInAsOrg || $loggedInAsUser
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link href="stylesheet.css" rel="stylesheet" type="text/css" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="bootstrap.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="jquery-2.2.2.min.js"></script>
		<script type="text/javascript" src="bootstrap.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#orgAct").hide();
				$("input[name=actType]").on( "change", function() {
					var target = $(this).val();
					$(".chooseActType").hide();
					$("#"+target).show();
				});
			});
		</script>
		<title>Dashboard</title>
		<style type="text/css">
		footer{
			position: fixed;
			bottom: 0px;
			text-align: center;
		}
		body, html {
		  margin: 0;
		  overflow: hidden;
		  height:100%;
		}

		@media (min-width: 768px){
		  #left {
			position: relative;
			top: 0;
			bottom: 0;
			left: 0;
			width: 50%;
			overflow-y: scroll; 
		  }

		  #right {
			position: relative;
			top: 0;
			bottom: 0;
			right: 0;
			overflow-y: scroll;
			width: 50%;
		  }
		}

		#left {
		  text-align: center;
		  height:90%;
		}

		#right {
		  height:90%;
		  text-align: center;
		}

		#left::-webkit-scrollbar,
		#right::-webkit-scrollbar{
			display: none;
		}
		
		.userInformation{
			position:relative;
			left: 100px;
		}
		</style>
		<?php require 'requiredHeader.php'?>
		<div class="userInformation">
		<form>
		<?php echo "Hello, {$userInfo['firstName']} {$userInfo['lastName']}, below are the events you've added to your calendar and organizations you've subscribed to."?>
		</form>
		</div>
		<div class ="col-sm-4" id="left">
			<h1>Events</h1>
			<?php for ($eventNum = 0; $eventNum<$numEvents;$eventNum++): ?>
			<p>
				<?php echo "{$eventsInfo[$eventNum]['evt_name']}: {$eventsInfo[$eventNum]['evt_start_date']}, 
							{$eventsInfo[$eventNum]['evt_start_time']} to {$eventsInfo[$eventNum]['evt_end_time']} in {$eventsInfo[$eventNum]['evt_room']}.<br />
							Click <a href='{$eventsInfo[$eventNum]['evt_url']}'>here</a> for more information.";?>
			</p>
			<?php endfor; ?>
		</div>
		<div class="col-sm-4" id="right">
			<h1>Organizations</h1>
			<?php for ($orgNum = 0; $orgNum<$numOrgs;$orgNum++):?>
			<p>
				<?php echo "{$orgsInfo[$orgNum]['org_name']}: {$orgsInfo[$orgNum]['org_website']}";?>
			</p>
			<?php endfor;?>
		</div>
		<footer>
			Matthew Castaldini Hanan Jalnko Kathleen Napier Ian Timmis
		</footer>
	</body>
</html>		
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

$sql = "SELECT * FROM ltuevents where id=1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	$evtInfo=$result->fetch_assoc();
} else {
	echo "0 results";
}
if(isset($_SESSION['userId'])){
	$userInfo['userId'] = $_SESSION['userId'];
	$userInfo['firstName'] = $_SESSION["firstName"];
	$userInfo['lastName'] = $_SESSION["lastName"];
	$userInfo['orgList'] = $_SESSION['orgList'];
	$userInfo['eventList'] = $_SESSION['eventList'];
	$userInfo['isAdmin'] = $_SESSION['isAdmin'];
}else{
	echo "Failed Log In";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
	<h1><div id="anEvent2"><?php echo $evtInfo['evt_name']?></div></h1>
	<h1><?php echo $userInfo['firstName'];?>

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

<!-- Dynamic Modal -->
<div class="modal fade" id="eventModal2" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel2">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title" id="eventModalLabel2"><?php echo $evtInfo['evtName']?></h2>
      </div>
      <div class="modal-body">
		<?php echo $evtInfo['evtDesc']?>
		<table class="info">
		<tr>
			<td><?php echo "Date: {$evtInfo['evtDate']} Time: {$evtInfo['evtTime']}"?></td>
			<td><?php echo "Event Org IdNum: {$evtInfo['evtOrgId']}"?></td>
		</tr>
		<tr>
			<td><?php echo "Building/Room: {$evtInfo['evtBuildingRoom']}"?></td>
			<td><a class="orglink" href="http://bit.ly/1ogtLMJ">Event Page</a></td>
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

<h1><div id="loginId"><br>Log In</div></h1>

<!-- Modal. #loginModal in function -->
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
			<li role="presentation" class="active"><a href="#login" aria-controls="login" role="tab" data-toggle="tab">Log-In</a></li>
			<li role="presentation"><a href="#createAccount" aria-controls="createAccount" role="tab" data-toggle="tab">Create Account</a></li>
		</ul>
		
		<!-- Div for tab content -->
		<div class="tab-content">
		
			<!-- First tab for log-in form -->
			<div role="tabpanel" class="tab-pane active" id="login"><br>
			<!--Form for logging in. Just takes username, password -->
			<form>
			  <!-- Email row -->
			  <div class="form-group row">
				<label for="inputEmail3" class="col-sm-4 form-control-label" align="right">Email</label>
				<div class="col-sm-7">
				  <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
				</div>
			  </div>
			  <!-- Password row -->
			  <div class="form-group row">
				<label for="inputPassword3" class="col-sm-4 form-control-label" align="right">Password</label>
				<div class="col-sm-5">
				  <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
				</div>
			  </div>
			  
			</form>
			<!-- End of log-in form and first tab -->
			</div>
			
			<!-- Second tab for account creation form -->
			<div role="tabpanel" class="tab-pane" id="createAccount"><br>
			<!--Form for account creation. Just takes username, password twice, and account type -->
			<form>
			  <!-- Email row -->
			  <div class="form-group row">
				<label for="inputEmail3" class="col-sm-4 form-control-label" align="right">Email</label>
				<div class="col-sm-7">
				  <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
				</div>
			  </div>
			  <!-- Password row -->
			  <div class="form-group row">
				<label for="inputPassword3" class="col-sm-4 form-control-label" align="right">Password</label>
				<div class="col-sm-5">
				  <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
				</div>
			  </div>
			  <!-- Confirm password row -->
			  <div class="form-group row">
				<label for="inputPassword3" class="col-sm-4 form-control-label" align="right">Confirm Password</label>
				<div class="col-sm-5">
				  <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
				</div>
			  </div>
			  <!-- Radio button row. -->
			  <div class="form-group row">
				<label class="col-sm-4" align="right">Account Type</label>
				<div class="col-sm-5">
				  <div class="radio-inline">
					<label>
					  <input type="radio" name="actTypeRadio" id="studentAct" value="studentAct" checked>
					  Student
					</label>
				  </div>
				  <div class="radio-inline">
					<label>
					  <input type="radio" name="actTypeRadio" id="orgAct" value="orgAct">
					  Organization
					</label>
				  </div>
				</div>
			  </div>
			</form>
			<!-- End of account creation form and second tab -->
			</div>
			
		</div>
      </div>
	  
	  <!-- Modal bottom buttons-->
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<br><br><br>



</div>
<footer>
Matthew Castaldini Hanan Jalnko Kathleen Napier Ian Timmis
</footer>
</table>
</body>
</html>

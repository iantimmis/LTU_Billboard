<?php
	session_start();
	$thisPage = 'createEvent.php';
	
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
	
	$endDateEarly = $endTimeEarly = false;
	$eventSuccess = true;
	$name = $url = $room = $desc = $message = "";
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(!empty($_POST['type'])){$type = $_POST['type'];}
		if(strcmp($type,'createEvt')==0)
		{
			$startDate = new DateTime($_POST['evtStartDate']);
			$endDate = new DateTime($_POST['evtEndDate']);
			$startTime = new DateTime($_POST['evtStartTime']);
			$endTime = new DateTime($_POST['evtEndTime']);
			if($endDate < $startDate)
			{
				$endDateEarly = true;
				$eventSuccess = false;
			}
			elseif($endDate == $startDate)
			{
				if($endTime <= $startTime)
				{
					$endTimeEarly = true;
					$eventSuccess = false;
				}
			}
			if(!$eventSuccess)
			{
				if(!empty($_POST['evtName'])){$name = $_POST['evtName'];}
				if(!empty($_POST['evtUrl'])) {$url  = $_POST['evtUrl'];}
				if(!empty($_POST['evtBuildingRoom'])){$room = $_POST['evtBuildingRoom'];}
				if(!empty($_POST['evtDesc'])){$desc = $_POST['evtDesc'];}
				$message = "Event Creation Failed.";
			}
			else
			{
				$name = cleanInput($_POST['evtName'],$conn);
				$url = cleanInput($_POST['evtUrl'],$conn);
				$room = cleanInput($_POST['evtBuildingRoom'],$conn);
				$desc = cleanInput($_POST['evtDesc'],$conn);
				$sql = "INSERT INTO LTUEvents (org_id,is_private, evt_name, evt_room, evt_category,evt_start_date,evt_end_date,evt_start_time,evt_end_time,evt_desc,evt_url,evt_visible)
					VALUES ({$_POST["evtOrgId"]},{$_POST["evtPrivate"]}, '{$name}', '{$room}', '{$_POST["evtCategory"]}',
					'{$_POST["evtStartDate"]}','{$_POST["evtEndDate"]}', '{$_POST["evtStartTime"]}', '{$_POST["evtEndTime"]}', '{$desc}','{$url}',1)";
			
				if ($conn->query($sql) === TRUE) {
					$message = "Event Created Successfully";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
		}
	}
	function cleanInput($input,$conn){
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		$input = mysqli_real_escape_string($conn,$input);
		return $input;
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(!empty($_POST['type'])){$type = $_POST['type'];}
		if(strcmp($type,'stu')==0)//logging in as student
		{
			$loginAttempted = true;
			if(empty($_POST['studentEmail']))
				$emailErr = "Email is Required";
			elseif(empty($_POST['studentPassword']))
				$passwordErr = "Password is Required";
			else
			{
				$email = cleanInput($_POST['studentEmail'],$conn);
				$password = cleanInput($_POST['studentPassword'],$conn);
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
					$_SESSION['userPassword'] = $userInfo['login_password'];
					$_SESSION['receiveEmails'] = $userInfo['receive_emails'];
					$loginMessage = "Login Successful";
				}
			}
		}
		if(strcmp($type,'org')==0)//logging in as an organization
		{
			$loginAttempted =true;
			if(empty($_POST['orgEmail']))
				$emailErr = "Email is Required";
			elseif(empty($_POST['orgPassword']))
				$passwordErr = "Password is Required";
			else
			{
				$email = cleanInput($_POST['orgEmail'],$conn);
				$password = cleanInput($_POST['orgPassword'],$conn);
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
					$_SESSION['orgPassword'] = $orgInfo['login_password'];
					$loginMessage = "Login Successful";
				}
			}
		}
		if(strcmp($type,'orgCreate')==0)//Creating organiaztion
		{
			$loginAttempted = true;
			$orgName = cleanInput($_POST['orgName'],$conn);
			$orgDesc = cleanInput($_POST['orgDesc'],$conn);
			$orgUrl = cleanInput($_POST['orgUrl'],$conn);
			$orgPassword = cleanInput($_POST['orgCreatePassword'],$conn);
			$orgEmail = cleanInput($_POST['orgEmail'],$conn);
			$sql = "INSERT INTO ltuorganization (org_name,org_description,org_website,login_password,org_email,org_accepted)
			values ('{$orgName}','{$orgDesc}','{$orgUrl}','{$orgPassword}','{$orgEmail}',0);";
			$errorMessage = "";
			if ($conn->query($sql) === TRUE) {
				//echo "New record created successfully";
			} else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
			$sql = "SELECT orgId FROM ltuorganization WHERE org_email='{$orgEmail}' AND login_password='{$orgPassword}';";
			$result = $conn->query($sql);
			if($result->num_rows==0){$loginMessage="Login Failed";}
			else
			{
				$userInfo = $result->fetch_assoc();
				$_SESSION['orgId'] = $userInfo['orgId'];
				$_SESSION["orgName"] = $orgName;
				$_SESSION["orgDesc"] = $orgDesc;
				$_SESSION['orgWebsite'] = $orgWebsite;
				$_SESSION['orgEmail'] = $orgEmail;
				$_SESSION['orgPassword'] = $orgPassword;
			}
		}
		if(strcmp($type,'stuCreate')==0)//Creating organiaztion
		{
			$loginAttempted = true;
			$firstName = cleanInput($_POST['firstName'],$conn);
			$lastName = cleanInput($_POST['lastName'],$conn);
			$stuPassword = cleanInput($_POST['stuCreatePassword'],$conn);
			$stuEmail = cleanInput($_POST['stuEmail'],$conn);
			$sql = "INSERT INTO user_account (first_name,last_name,login_password,is_admin,user_email,receive_emails)
			values ('{$firstName}','{$lastName}','{$stuPassword}',0,'{$stuEmail}',1);";
	
			if ($conn->query($sql) === TRUE) {
				//echo "New record created successfully";
			} else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
			$sql = "SELECT userId FROM user_account WHERE user_email='{$email}' AND login_password='{$password}';";
			$result = $conn->query($sql);
			if($result->num_rows==0){$loginMessage="Login Failed";}
			else
			{
				$userInfo = $result->fetch_assoc();
				$_SESSION['userId'] = $userInfo['userId'];
				$_SESSION["firstName"] = $firstName;
				$_SESSION["lastName"] = $lastName;
				$_SESSION['isAdmin'] = 0;
				$_SESSION['userEmail'] = $email;
				$_SESSION['userPassword'] = $stuPassword;
				$_SESSION['receiveEmails'] = 1;
			}
		}
	}
	
	//check session to see if logged in and user and get info if true
	$loggedInAsUser = false;
	$loggedInAsOrg = false;
	if (isset($_SESSION['userId'])){
		$userInfo['userId'] = $_SESSION['userId'];
		$userInfo['firstName'] = $_SESSION["firstName"];
		$loggedInAsUser = true;
		$message = "You need to be logged in as an organization to create an event.";
	} elseif (isset($_SESSION['orgId'])) {
		$orgId = $_SESSION['orgId'];
		$orgInfo['name']=$_SESSION['orgName'];
		$loggedInAsOrg = true;
		$message = "";
	} else {
		$message = "You need to be logged in to create an event.";
	}
	$loggedIn = $loggedInAsOrg || $loggedInAsUser;
	
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<link href="stylesheet.css" rel="stylesheet" type="text/css" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="bootstrap.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="jquery-2.2.2.min.js"></script>
		<script type="text/javascript" src="bootstrap.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
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
		<title>Create Events Page</title>
		</head>
		<body>
	<?php require 'requiredHeader.php'?>
		<br />
		<?php if($loggedInAsOrg): ?>
		<?php echo "<h1 align='center'>{$message}</h1>";?>
		<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" role="form" id="eventForm">
			<!-- Type Row -->
			<div class="form-group row">
				<label class="col-sm-1" align="right">Type:</label>
				<div class="col-sm-5">
					<div class="radio-inline">
						<label>
							<input type="radio" name="evtPrivate" id="public" value="1" checked />
				Public
						</label>
					</div>
					<div class="radio-inline">
						<label>
							<input type="radio" name="evtPrivate" id="private" value="0" />
			  Private
						</label>
					</div>
				</div>
			</div>
			<!-- Event Name -->
			<div class="form-group row">
				<label for="evtName" class="col-sm-1 form-control-label" align="right">Name:</label>
				<div class="col-sm-2">
					<input required type="text" class="form-control" id="evtName" name="evtName" placeholder="Event Name" <?php echo "value='{$name}'";?>/>
				</div>
				<label for="evtUrl" class="col-sm-1 form-control-label" align="right">URL:</label>
				<div class="col-sm-2">
					<input required type="url" class="form-control" id="evtUrl" name="evtUrl" placeholder="External Page Link" <?php echo "value='{$url}'";?>/>
				</div>
			</div>
			<!-- Room row -->
			<div class="form-group row">
				<label for="evtBuildingRoom" class="col-sm-1 form-control-label" align="right">Building & Room:</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="evtBuildingRoom" name="evtBuildingRoom" <?php echo "value ='{$room}'";?> placeholder="Building: Room" />
				</div>
			</div>
			<!-- Event Category -->
			<div class="form-group row">
				<label for="evtCategory" class="col-sm-1 form-control-label" align="right">Category:</label>
				<div class="col-sm-2">
					<select required type="multiple" class="form-control" id="evtCategory" name="evtCategory">
						<option>MCS</option>
						<option>MATH</option>
						<option>ENG</option></select>
				</div>
			</div>
			<!-- Date row -->
			<div class="form-group row">
				<label for="evtStartDate" class="col-sm-1 form-control-label" align="right">Start Date:</label>
				<div class="col-sm-2">
					<input required type="date" class="form-control" id="evtStartDate" name="evtStartDate" />
				</div>
				<label for="evtEndDate" class="col-sm-1 form-control-label" align="right">End Date:</label>
				<div class="col-sm-2">
					<input required type="date" class="form-control" id="evtEndDate" name="evtEndDate" />
				</div>
			</div>
			<?php if($endDateEarly): ?>
			<div class="form-group row">
				<span class="col-sm-5 error" align="right">End date must be after start date</span>
			</div>
			<?php endif; ?>
			<!-- Time row -->
			<div class="form-group row">
				<label for="evtStartTime" class="col-sm-1 form-control-label" align="right">Start Time:</label>
				<div class="col-sm-2">
					<input required type="time" class="form-control" id="evtStartTime" name="evtStartTime" />
				</div>
				<label for="evtEndTime" class="col-sm-1 form-control-label" align="right">End Time:</label>
				<div class="col-sm-2">
					<input required type="time" class="form-control" id="evtEndTime" name="evtEndTime" min ="0" />
				</div>
			</div>
			<?php if($endTimeEarly): ?>
			<div class="form-group row">
				<span class="col-sm-5 error" align="right">End time must be after time date</span>
			</div>
			<?php endif; ?>
			<!-- Description row -->
			<div class="form-group row">
				<label for="evtDesc" class="col-sm-1 form-control-label" align="right">Description:</label>
				<div class="col-sm-3">
					<textarea required class="form-control" id="evtDesc" name="evtDesc" rows="3" placeholder="Detailed description of event." ><?php echo "{$desc}";?></textarea>
				</div>
			</div>
			<!-- hidden value for org id -->
			<div class="col-sm-1">
				<input required type="hidden" class="form-control" id="evtOrgId" name="evtOrgId" value=<?php echo $orgId;?>></input>
			</div>
			<!-- Submit button -->
			<div class="form-group row">
				<label for="submit" class="col-sm-1 form-control-label" align="right"></label>
				<div class="col-sm-1" align="left">
					<button required type = "submit" class ="btn btn-primary" id = "submit" name="type" value="createEvt">Submit</button>
				</div>
			</div>
		</form>
		<?php else: echo "<h1 align='center'>{$message}</h1>";?>
		<?php endif ?>

		<footer>
			Created By: Matthew Castaldini, Hanan Jalnko, Kathleen Napier, Ian Tammis
		</footer>
	</body>
</html>
	
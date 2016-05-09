<?php
	session_start();//session control
	$thisPage = "organizations.php";//page for logout redirection
	
	//Establish connection with the database
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
	
	//get org id to show info
	if (!empty($_GET['orgId'])) {
		$orgInfo['id'] = $_GET['orgId'];
	}
	
	//variables used in validation
	$email = $password = $type = "";
	$emailErr = $passwordErr = $loginMessage = "";
	$loginAttempted = $loginSuccess = $loggedInAsUser = $loggedInAsOrg = $loggedInAsAdmin = false;
	//data validation for logging in
	$endDateEarly = $endTimeEarly = false;
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		/*get the type of post request. Option: Request
			stu: 				for logging in as student
			org: 				for logging in as organization
			orgCreate: 			for creating organization account
			stuCreate:			for creating student account
			changeOrgEmail: 	for changing organization email
			changeOrgPassword: 	for changing organization password
		*/
		if(!empty($_POST['type'])){$type = $_POST['type'];}
		
		//If cases for checking the type of request
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
					$_SESSION['isAccepted'] = $orgInfo['org_accepted'];
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
			} else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
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
			} else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
			
		}
		if(strcmp($type,'changeOrgPassword')==0)
		{
			$newPassword = cleanInput($_POST['changeOrgPassword'],$conn);
			$sql = "UPDATE ltuorganization SET login_password = '{$newPassword}' WHERE org_email='{$_SESSION['orgEmail']}' AND login_password='{$_SESSION['orgPassword']}';";
			if ($conn->query($sql) === TRUE) {
				$orgInfo['password'] = $newPassword;
				$_SESSION['orgPassword'] = $newPassword;
			} else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		if(strcmp($type,'changeOrgDesc')==0)
		{
			$newDesc = cleanInput($_POST['changeOrgDesc'],$conn);
			$sql = "UPDATE ltuorganization SET login_password = '{$newDesc}' WHERE org_email='{$_SESSION['orgEmail']}' AND login_password='{$_SESSION['orgPassword']}';";
			if ($conn->query($sql) === TRUE) {
				$orgInfo['desc'] = $newDesc;
				$_SESSION['orgDesc'] = $newDesc;
			} else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		if(strcmp($type,'deleteEvent')==0)
		{
			$evtId = cleanInput($_POST['deleteEvtId'],$conn);
			$sql = "DELETE FROM ltuevents where eventId={$evtId};";
			if ($conn->query($sql) === TRUE) {} 
			else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
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
	
	//check session to see if logged in and user and get info if true
	if (isset($_SESSION['userId'])){
		$userInfo['userId'] = $_SESSION['userId'];
		$userInfo['firstName'] = $_SESSION["firstName"];
		$userInfo['lastName'] = $_SESSION["lastName"];
		$loggedInAsAdmin = $_SESSION['isAdmin'];
		$userId = $userInfo['userId'];
		$message  = $userInfo['firstName'] . " " . $userInfo['lastName'];
		$loggedInAsUser = true;
		
	} elseif (isset($_SESSION['orgId'])) {
		$orgInfo['id'] = $_SESSION['orgId'];
		$orgInfo['name'] = $_SESSION['orgName'];
		$orgInfo['desc'] = $_SESSION['orgDesc'];
		$orgInfo['website'] = $_SESSION['orgWebsite'];
		$orgInfo['password'] = $_SESSION['orgPassword'];
		$orgInfo['email'] = $_SESSION['orgEmail'];
		$orgInfo['orgAccepted'] = $_SESSION['isAccepted'];
		$loggedInAsOrg = true;
		$message = $orgInfo['name'];
		
		
	} else {
		$message = "No One";
	}	
	$loggedIn = $loggedInAsOrg || $loggedInAsUser;
	
	//get events hosted by either org that is logged in, or one the user wants to see
	$numEvents = 0;
	if(!empty($orgInfo['id']))
	{
		$sql = "SELECT * FROM ltuevents WHERE org_id = {$orgInfo['id']} AND evt_start_date > CURDATE() ORDER BY evt_start_date;";
		$result = $conn->query($sql);
		$numEvents  =$result->num_rows;
		if($numEvents==0){}
		else
		{
			$orgInfo['eventArray']= array();
			while($row=$result->fetch_assoc())
			{
				array_push($orgInfo['eventArray'],$row);//put event info into array
			}
		}
	}
	else{$orgInfo['id']=0;}
	
	//get all organizations
	$numOrgs = 0;
	$sql = "SELECT orgId, org_name, org_description, org_website, org_email, org_accepted FROM ltuorganization;";
	$result = $conn->query($sql);
	$numOrgs = $result->num_rows;
	if($numOrgs==0){}
	else
	{
		$orgsArray= array();
		while($row=$result->fetch_assoc())
		{
			array_push($orgsArray,$row);//put event info into array
			if(empty($orgInfo['name'])){
				if($row['orgId']==$orgInfo['id'])
					$orgInfo['name'] = $row['org_name'];
			}
		}
		if(empty($orgInfo['name']))
			$orgInfo['name'] = "No organization selected";
	}
	$conn->close();
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
				
				//used for choosing org
				$("#chooseOrgId").on( "change", function(){
					$("#selectOrgForm").submit();
				});
				
				$("#orgAct").hide();
				$("input[name=actType]").on( "change", function() {
					var target = $(this).val();
					$(".chooseActType").hide();
					$("#"+target).show();
				});
				
				$("#passwordForm").validate({
					"rules" : {
						"confirmChangeOrgPassword" : {
							"equalTo" : "#changeOrgPassword"}
					}
				});
				
				$("#receiveEmails").on( "change", function(){
					$("#receiveEmailsForm").submit();
				});
				
				//validation for account creation and logging in
				$("#createStuAct").validate({
					"rules" : {
						"confirmStuPassword" : {
							"equalTo" : "#stuCreatePassword"}
					}
				});
				$("#createOrgAct").validate({
					rules : {
						confirmOrgPassword : {
							equalTo : "#orgCreatePassword"}
					}
				});
				$("#studentForm").validate({});
				$("#orgForm").validate({});
				
				//directly open the create account tab
				$("#createAccountLink").on("click", function(){
					$('#loginModal').modal('show');
					$('#loginTabs a:last').tab('show');
				});
				
				//open the login modal and show error if login fails
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
						//login org fail
						$('#loginModal').modal('show');
						$('#loginTabs a[href="#loginAsOrg"]').tab('show')
						$('#orgLoginMessage').html("Login Failed");
						$('#orgLoginMessage').toggleClass('error');
						<?php endif;?>
					<?php endif;?>
				<?php endif?>
			});
		</script>
		<title>Account Settings</title>
	</head>
	<body>
		<?php require 'requiredHeader.php'?>
		
		<?php if($loggedInAsOrg)://section for if logged in as organization. Has options to change account info and delete events.?>
		<div class="form-group row pageMessage">
			<div class="col-sm-5" align="center">
				You're logged in as: <?php echo $message?>,<br />Below you can change your organization's information.<br/>
				<?php echo $orgInfo['orgAccepted'] ? "Your organization have been approved by administration" : "Your organization is still waiting approval.";?>
			</div>
		</div>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method ="post" role="form" id="emailForm">
			<!-- Username row -->
			<div class="form-group row">
				<label for="changeOrgEmail" class="col-sm-1 form-control-label" align="right">Email:</label>
				<div class="col-sm-2">
					<input required type="email" class="form-control" id="changeOrgEmail" name="changeOrgEmail" value="<?php echo $orgInfo['email']?>" />
				</div>
				<div class="col-sm-1">
					<button type = "submit" class ="btn btn-primary" id = "submit" name="type" value="changeOrgEmail">Change</button>
				</div>
			</div>
		</form>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method ="post" role="form" id="passwordForm">
			<!-- Password row -->
			<div class="form-group row">
				<label for="changeOrgPassword" class="col-sm-1 form-control-label" align="right">Password:</label>
				<div class="col-sm-2">
					<input required type="password" class="form-control" id="changeOrgPassword" name="changeOrgPassword" value="<?php echo $orgInfo['password']?>" />
				</div>
				<div class="col-sm-2">
					<input required type="password" class="form-control" id="confirmChangeOrgPassword" name="confirmChangeOrgPassword" placeholder="Repeat new password" />
				</div>
				<div class="col-sm-1">
					<button type = "submit" class ="btn btn-primary" id = "submit" name="type" value="changeOrgPassword">Change</button>
				</div>
			</div>
		</form>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method ="post" role="form" id="orgDescForm">
			<div class="form-group row">
				<label for="changeOrgDesc" class="col-sm-1 form-control-label" align="right">Description:</label>
				<div class="col-sm-4">
					<textarea required type="email" class="form-control" id="changeOrgEmail" name="changeOrgEmail"><?php echo $orgInfo['desc'];?></textarea>
				</div>
				<div class="col-sm-1">
					<button type = "submit" class ="btn btn-primary" id = "submit" name="type" value="changeOrgDesc">Change</button>
				</div>
			</div>
		</form>
		<?php else: //section for if logged in as user or not logged in. Shows dropdown to select an org to view information, or shows information of the org chosen.?>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get" role="form" id="selectOrgForm">
			<div class="form-group row pageMessage">
				<div class="col-sm-5" align="center">
				Pick an Organization: 
					<select id="chooseOrgId" name="orgId">
					<option value="0">Pick One</option>
					<?php foreach($orgsArray as $org):?>
						<option value="<?php echo $org['orgId']?>" <?php echo $org['orgId']==$orgInfo['id'] ? "selected" : ""?>><?php echo $org['org_name']?></option>
						<?php if($org['orgId']==$orgInfo['id']){$orgInfo['name']=$org['org_name'];}?>
						
					<?php endforeach;?>
					</select>
				</div>
			</div>
		</form>
		<?php endif;?>
		
		<div class="form-group row pageMessage">
			<div class="col-sm-5" align="center">
				Below are events hosted by: <?php echo $orgInfo['name'];?>.
			</div>
		</div>
		<?php if($numEvents>0):?>
			<?php foreach($orgInfo['eventArray'] as $eventInfo)://loops through each org followed?>
			<div class="form-group row">
				<div class="col-sm-2" align="right">
					Name: <?php echo $eventInfo['evt_name'];?>
				</div>
				<div class="col-sm-2" align="right">
					Start Date: <?php echo $eventInfo['evt_start_date'];?>
				</div>
				<div class="col-sm-2" align="left">
					<a href='index.php'><button class="button" id='infoButton'>More Info</button></a>
				</div>
		
				<?php if($loggedInAsOrg):?>
				<div class="col-sm-1" align="left">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method ="post" role="form" id="deleteEvent">
						<input type="hidden" name="deleteEvtId" value="<?php echo $eventInfo['eventId'];?>" />
						<button class="button" id='deleteButton' type="submit" name="type" value="deleteEvent">Delete</button>
					</form>
				</div>
				<?php endif;?>
			</div>
			<?php endforeach;?>
		<?php else:?>
		<div class="form-group row pageMessage">
			<div class="col-sm-5" align="center">
				This organization doesn't host any events
			</div>
		</div>
		<?php endif;?>
		<!--<pre>
		<?php //print_r($orgInfo['eventArray']);?>
		</pre>-->
		<div id="bottomWrapper">
			<footer>
			  Created By: Matthew Castaldini, Hanan Jalnko, Kathleen Napier, Ian Timmis
			</footer>
		</div>
	</body>
</html>
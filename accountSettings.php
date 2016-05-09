<?php
	session_start();//session control
	$thisPage = "index.php";//page for logout redirection
	
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
	
	//variables used in validation
	$email = $password = $type = "";
	$emailErr = $passwordErr = $loginMessage = "";
	$loginAttempted = $loginSuccess = $loggedInAsUser = $loggedInAsOrg = false;
	//data validation for logging in
	$endDateEarly = $endTimeEarly = false;
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		/*get the type of post request. Option: Request
			stu: 				for logging in as student
			org: 				for logging in as organization
			orgCreate: 			for creating organization account
			stuCreate:			for creating student account
			changeStuEmail: 	for changing student email
			changeStuPassword: 	for changing student password
			unfollowOrg:		for unfollowing an organization's events
			'empty':			if there isn't a type, it's for the checkbox used to stop receiving any emails
		*/
		if(!empty($_POST['type'])){$type = $_POST['type'];}
		
		//If cases for checking the type of request
		if(strcmp($type,'stu')==0)//logging in as student
		{
			$loginAttempted = true;//user tried to log in
			if(empty($_POST['studentEmail']))//email is required
				$emailErr = "Email is Required";
			elseif(empty($_POST['studentPassword']))//password is required
				$passwordErr = "Password is Required";
			else//if information is filled out
			{
				//clean inputs using function
				$email = cleanInput($_POST['studentEmail'],$conn);
				$password = cleanInput($_POST['studentPassword'],$conn);
				
				//query database to get information, then store it in the session
				$sql = "SELECT * FROM user_account WHERE user_email='{$email}' AND login_password='{$password}';";
				$result = $conn->query($sql);
				if($result->num_rows==0){$loginMessage="Login Failed";}//not found in database
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
			if ($conn->query($sql) === TRUE) {//if successfully created account, log them in
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
					$_SESSION['isAccepted'] = 0;
				}
			} //success
			else 
			{//for error checking while developing
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}//fail
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
			} else 
			{
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		if(strcmp($type,'changeStuEmail')==0)
		{
			$newEmail = cleanInput($_POST['changeStudentEmail'],$conn);
			$sql = "UPDATE user_account SET user_email = '{$newEmail}' WHERE user_email='{$_SESSION['userEmail']}' AND login_password='{$_SESSION['userPassword']}';";
			if ($conn->query($sql) === TRUE) {//if successfull, change email in session and for this page
				$userInfo['userEmail'] = $newEmail;
				$_SESSION['userEmail'] = $newEmail;
			} else 
			{
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		if(strcmp($type,'changeStuPassword')==0)
		{
			$newPassword = cleanInput($_POST['changeStudentPassword'],$conn);
			$sql = "UPDATE user_account SET login_password = '{$newPassword}' WHERE user_email='{$_SESSION['userEmail']}' AND login_password='{$_SESSION['userPassword']}';";
			if ($conn->query($sql) === TRUE) {//if successfull, change password in session and for this page
				$userInfo['userPassword'] = $newPassword;
				$_SESSION['userPassword'] = $newPassword;
			} else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		if(strcmp($type,'unfollowOrg')==0)
		{
			$unfollowOrgId = $_POST['unfollowOrgId'];
			$sql = "DELETE FROM user_org_join WHERE userId={$_SESSION['userId']} AND orgId = {$unfollowOrgId};";
			if ($conn->query($sql) === TRUE) {}
			else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		if(empty($_POST['type']))
		{
			//the form is empty if the checkbox is unchecked, and not if it is checked, so check if it's empty or not
			$emailBool = empty($_POST['receiveEmails']) ? 0 : 1;
			$sql = "UPDATE user_account SET receive_emails = {$emailBool} WHERE user_email='{$_SESSION['userEmail']}' AND login_password='{$_SESSION['userPassword']}';";
			if ($conn->query($sql) === TRUE) {//update session and page as well
				$userInfo['receiveEmails'] = $emailBool;
				$_SESSION['receiveEmails'] = $emailBool;
			} else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
	}
	
	//check session to see if logged in and user and get info if true
	require 'getOrgsScript.php';//function for getting organizations the user follows
	if (isset($_SESSION['userId'])){//get info from session and store on page
		$userInfo['userId'] = $_SESSION['userId'];
		$userInfo['firstName'] = $_SESSION["firstName"];
		$userInfo['lastName'] = $_SESSION["lastName"];
		$userInfo['isAdmin'] = $_SESSION['isAdmin'];
		$userInfo['userEmail'] = $_SESSION['userEmail'];
		$userInfo['userPassword'] = $_SESSION['userPassword'];
		$userInfo['receiveEmails'] = $_SESSION['receiveEmails'];
		$userId = $userInfo['userId'];
		$message  = $userInfo['firstName'] . " " . $userInfo['lastName'];
		$loggedInAsUser = true;
		
		$followedOrgs = getOrgs($userInfo['userId'],$conn);//get followed org information
		$numFollowedOrgs = 0;
		if(!empty($followedOrgs))//get how many orgs the user follows
			$numFollowedOrgs = count($followedOrgs);
	} elseif (isset($_SESSION['orgId'])) {//orgs can view this page
		$loggedInAsOrg = true;
		$message = $orgInfo['name'];
	} else {
		$message = "No One";
	}
	$loggedIn = $loggedInAsOrg || $loggedInAsUser;
	
	function cleanInput($input,$conn){//function for cleaning input
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		$input = mysqli_real_escape_string($conn,$input);
      	return $input;
	}
	$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- included scripts and styles -->
		<link href="stylesheet.css" rel="stylesheet" type="text/css" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="bootstrap.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="jquery-2.2.2.min.js"></script>
		<script type="text/javascript" src="bootstrap.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {//doc.ready
				//used for the account creation tab, determining what type to show
				$("#orgAct").hide();
				$("input[name=actType]").on( "change", function() {
					var target = $(this).val();
					$(".chooseActType").hide();
					$("#"+target).show();
				});
				
				//validate the change password form by ensuring passwords are equal
				$("#changePasswordForm").validate({
					"rules" : {
						"confirmChangeStudentPassword" : {
							"equalTo" : ".changeStudentPassword"}
					}
				});
				
				//submit the checkbox form on change
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
			});//end of doc.ready
		</script>
		<style>
		.orgPage{
			color: #333;
			text-decoration: underline;
		}
		</style>
		<title>Account Settings</title>
	</head>
	<body>
		<?php require 'requiredHeader.php';//header html?>
		<?php if($loggedInAsUser): ?>
		<!-- forms for changing user information -->
		<div class="form-group row pageMessage">
			<div class="col-sm-5" align="center">
				You're logged in as: <?php echo $message?>,<br />Below you can change your email and password.<br/>
			</div>
		</div>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method ="post" role="form" id="emailForm">
			<!-- Username row -->
			<div class="form-group row">
				<label for="changeStudentEmail" class="col-sm-1 form-control-label" align="right">Email:</label>
				<div class="col-sm-2">
					<input required type="email" class="form-control" id="changeStudentEmail" name="changeStudentEmail" value="<?php echo $userInfo['userEmail']?>" />
				</div>
				<div class="col-sm-1">
					<button type = "submit" class ="btn btn-primary" id = "submit" name="type" value="changeStuEmail">Change</button>
				</div>
			</div>
		</form>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method ="post" role="form" id="changePasswordForm">
			<!-- Password row -->
			<div class="form-group row">
				<label for="changeStudentPassword" class="col-sm-1 form-control-label" align="right">Password:</label>
				<div class="col-sm-2">
					<input required type="password" class="form-control" id="changeStudentPassword" name="changeStudentPassword" value="<?php echo $userInfo['userPassword']?>" />
				</div>
				<div class="col-sm-2">
					<input required type="password" class="form-control" id="confirmChangeStudentPassword" name="confirmChangeStudentPassword" placeholder="Repeat new password" />
				</div>
				<div class="col-sm-1">
					<button type = "submit" class ="btn btn-primary" id = "submit" name="type" value="changeStuPassword">Change</button>
				</div>
			</div>
		</form>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method ="post" role="form" id="receiveEmailsForm">
			<!-- emails checkbox -->
			<div class="form-group row">
				<label for="receiveEmails" class="col-sm-1 form-control-label" align="right"> Recieve Emails:</label>
				<div class="col-sm-2">
					<input type="checkbox" name="receiveEmails" id="receiveEmails" <?php if($userInfo['receiveEmails']==1){echo "checked";}?>/>
				</div>
			</div>
		</form>
		
		<!-- section for showing followed organizations -->
		<div class="form-group row pageMessage">
			<div class="col-sm-5" align="center">
				You follow <?php echo $numFollowedOrgs?> organizations at Lawrence Tech.<br /> <a class="orgPage" href="organizations.php">Click here</a> to find more, or look at the organizations you follow below.
			</div>
		</div>
			<?php if($numFollowedOrgs>0):?>
				<?php foreach($followedOrgs as $followedOrgInfo)://loops through each org followed?>
				<div class="form-group row">
					<div class="col-sm-2" align="right">
						Name: <?php echo $followedOrgInfo['org_name'];?>
					</div>
					<div class="col-sm-2" align="center">
						<a href='organizations.php?orgId=<?php echo $followedOrgInfo['orgId'];?>'><button class="button" id='infoButton'>More Info</button></a>
					</div>
					<div class="col-sm-2" align="center">
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method ="post" role="form" id="unfollowOrg">
							<input type="hidden" name="unfollowOrgId" value="<?php echo $followedOrgInfo['orgId'];?>" />
							<button class="button" id='unfollowButton' type="submit" name="type" value="unfollowOrg">Unfollow</button>
							</form>
					</div>
				</div>
				<?php endforeach;?>
			<?php endif;?>
		<?php else:?>
		<h1>Must be logged in as a user account to view this page.</h1>
		<?php endif;?>
		<div id="bottomWrapper">
			<footer>
			  Created By: Matthew Castaldini, Hanan Jalnko, Kathleen Napier, Ian Timmis
			</footer>
		</div>
	</body>
</html>
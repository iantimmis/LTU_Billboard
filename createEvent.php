<?php
	session_start();
	
	//check session to see if logged in and user and get info if true
	$loggedInAsUser = false;
	$loggedInAsOrg = false;
	if (isset($_SESSION['userId'])){
		$userInfo['userId'] = $_SESSION['userId'];
		$loggedInAsUser = true;
		$message = "You need to be logged in as an organization to create an event.";
	} elseif (isset($_SESSION['orgId'])) {
		$orgId = $_SESSION['orgId'];
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
		<style type="text/css">
		footer{
			position: fixed;
			bottom: 0px;
			text-align: center;
		}
		</style>
	</head>
	<body>
		<header>
			<?php if($loggedInAsOrg) echo "<a href='createEvent.php'><button class='event'>Create Event</button></a>";?>
			<a href="fcIndex.php" id=logo>LTU Billboard</a>
			<?php if(!$loggedIn): ?>
			<span class="log-in">
				<button id="loginButton" data-toggle="modal" data-target="#loginModal">Log In</button>
				<br />
				Not a user? <a href="">Join Now</a>
			</span>
			<?php else: ?>
			<span class="log-in">
				<form action="logout.php" method="post" role="form">
					<button id="logoutButton" type="submit">Log Out</button>
				</form>
			</span>
			<?php endif ?>
			<br />
			<br />
			<br />

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
							<li role="presentation"><a href="#createAccount" aria-controls="createAccount" role="tab" data-toggle="tab">Create Account</a></li>
						</ul>

						<!-- Div for tab content -->
						<div class="tab-content">
							<!-- First tab for account creation form -->



							<!-- Second tab for student log-in form -->
							<div role="tabpanel" class="tab-pane active" id="loginAsStudent"><br />
								<!--Form for logging in. Just takes username, password -->
								<form action="loginAsStudent.php" method ="post" role="form">
									<!-- Username row -->
									<div class="form-group row">
										<label for="studentEmail" class="col-sm-4 form-control-label" align="right">Email</label>
										<div class="col-sm-7">
											<input type="email" class="form-control" id="studentEmail" name="studentEmail" placeholder="user@example.com" />
										</div>
									</div>
									<!-- Password row -->
									<div class="form-group row">
										<label for="studentPassword" class="col-sm-4 form-control-label" align="right">Password</label>
										<div class="col-sm-5">
											<input type="password" class="form-control" id="studentPassword" name="studentPassword" placeholder="Password" />
										</div>
									</div>
									<!-- Submit button -->
									<hr />
									<div class="form-group row">
										<div class="col-sm-12" align="right">
											<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="createEvent.php">Submit</button>
											<button type = "submit" class ="btn btn-default" data-dismiss="modal">Cancel</button>
										</div>
									</div>
								</form>
								<!-- End of log-in form and 2nd tab -->
							</div>

							<!-- Third tab for Organization log-in form -->
							<div role="tabpanel" class="tab-pane" id="loginAsOrg"><br />
								<!--Form for logging in. Just takes username, password -->
								<form action="loginAsOrg.php" method ="post" role="form">
									<!-- Username row -->
									<div class="form-group row">
										<label for="orgEmail" class="col-sm-4 form-control-label" align="right">Email</label>
										<div class="col-sm-7">
											<input type="email" class="form-control" id="orgEmail" name ="orgEmail" placeholder="org@example.com" />
										</div>
									</div>
									<!-- Password row -->
									<div class="form-group row">
										<label for="orgPassword" class="col-sm-4 form-control-label" align="right">Password</label>
										<div class="col-sm-5">
											<input type="password" class="form-control" id="orgPassword" name="orgPassword" placeholder="Password" />
										</div>
									</div>
									<!-- Submit button -->
									<hr />
									<div class="form-group row">
										<div class="col-sm-12" align="right">
											<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="createEvent.php">Submit</button>
											<button type = "submit" class ="btn btn-default" data-dismiss="modal">Cancel</button>
										</div>
									</div>
								</form>
								<!-- End of log-in form and 3rd tab -->
							</div>
							<!-- First tab for account creation form -->
							<div role="tabpanel" class="tab-pane" id="createAccount"><br />
								<!--Form for account creation. Just takes username, password twice, and account type -->
								<form action = "createAccount.php" method ="post" role="form">
									<!-- Radio button row. -->
									<div class="form-group row">
										<label class="col-sm-3" align="right">Account Type</label>
										<div class="col-sm-5">
											<div class="radio-inline">
												<label><input type="radio" name="actType" value="studentAct" checked />Student</label>
											</div>
											<div class="radio-inline">
												<label><input type="radio" name="actType" value="orgAct" />Organization</label>
											</div>
										</div>
									</div>
									<div id="studentAct" class="chooseActType">
										<!-- First Name Row -->
										<div class="form-group row">
											<label for="firstName" class="col-sm-3 form-control-label" align="right">First Name</label>
											<div class="col-sm-3">
												<input type="name" class="form-control" id="firstName" name="firstName" placeholder="John" />
											</div>
										</div>
										<div class="form-group row">
											<!-- Last Name Row -->
											<label for="lastName" class="col-sm-3 form-control-label" align="right">Last Name</label>
											<div class="col-sm-4">
												<input type="name" class="form-control" id="lastName" name="lastName" placeholder="Doe" />
											</div>
										</div>
										<div class="form-group row">
											<!-- Email row -->
											<label for="stuEmail" class="col-sm-3 form-control-label" align="right">Email</label>
											<div class="col-sm-4">
												<input type="email" class="form-control" id="stuEmail" name="stuEmail" placeholder="name@example.com" />
											</div>
										</div>
										<!-- Password row -->
										<div class="form-group row">
											<label for="stuPassword" class="col-sm-3 form-control-label" align="right">Password</label>
											<div class="col-sm-3">
												<input type="password" class="form-control" id="stuPassword" name="stuPassword" placeholder="Password" />
											</div>
										</div>
										<div class="form-group row">
											<!-- Confirm password row -->
											<label for="confirmStuPassword" class="col-sm-3 form-control-label" align="right">Repeat Password</label>
											<div class="col-sm-3">
												<input type="password" class="form-control" id="confirmStuPassword" name="confirmStuPassword" placeholder="Password" />
											</div>
										</div>
										<hr />
										<div class="form-group row">
											<!-- Submit Row -->
											<div class="col-sm-12" align="right">
												<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="createEvent.php">Submit</button>
												<button type = "submit" class ="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</div>
									</div>
									<div id="orgAct" class="chooseActType">
										<!-- Email row -->
										<div class="form-group row">
											<label for="orgName" class="col-sm-3 form-control-label" align="right">Name</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="orgName" name="orgName" placeholder="Example Name" />
											</div>
										</div>
										<div class="form-group row">
											<label for="orgEmail" class="col-sm-3 form-control-label" align="right">Email</label>
											<div class="col-sm-4">
												<input type="email" class="form-control" id="orgEmail" name="orgEmail" placeholder="org@example.com" />
											</div>
										</div>
										<!-- Password row -->
										<div class="form-group row">
											<label for="orgPassword" class="col-sm-3 form-control-label" align="right">Password</label>
											<div class="col-sm-3">
												<input type="password" class="form-control" id="orgPassword" name="orgPassword" placeholder="Password" />
											</div>
										</div>
										<!-- Confirm password row -->
										<div class="form-group row">
											<label for="confirmOrgPassword" class="col-sm-3 form-control-label" align="right">Repeat Password</label>
											<div class="col-sm-3">
												<input type="password" class="form-control" id="confirmOrgPassword" name="confirmOrgPassword" placeholder="Password" />
											</div>
										</div>
										<div class="form-group row">
											<label for="orgDesc" class="col-sm-3 form-control-label" align="right">Description</label>
											<div class="col-sm-6">
												<textarea class="form-control" id="orgDesc" name="orgDesc" placeholder="Detailed description of your organization"></textarea>
											</div>
										</div>
										<div class="form-group row">
											<label for="orgUrl" class="col-sm-3 form-control-label" align="right">Website</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="orgUrl" name="orgUrl" placeholder="Example.com" />
											</div>
										</div>
										<hr />
										<div class="form-group row">
											<div class="col-sm-12" align="right">
												<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="createEvent.php">Submit</button>
												<button type = "submit" class ="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</div>
									</div>
								</form>
								<!-- End of account creation form and first tab -->
							</div>



						</div>
					</div>

				</div>
			</div>
		</div>
		<?php endif ?>

		<br />
		<br />
		<?php if($loggedInAsOrg): ?>
		<form class="form-horizontal" action="submitEvent.php" method="post" role="form">
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
					<input type="text" class="form-control" id="evtName" name="evtName" placeholder="Event Name" />
				</div>
				<label for="evtUrl" class="col-sm-1 form-control-label" align="right">URL:</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="evtUrl" name="evtUrl" placeholder="ltu.edu" />
				</div>
			</div>
			<!-- Room row -->
			<div class="form-group row">
				<label for="evtBuildingRoom" class="col-sm-1 form-control-label" align="right">Building & Room:</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="evtBuildingRoom" name="evtBuildingRoom" placeholder="Building: Room" />
				</div>
			</div>
			<!-- Event Category -->
			<div class="form-group row">
				<label for="evtCategory" class="col-sm-1 form-control-label" align="right">Category:</label>
				<div class="col-sm-2">
					<select type="multiple" class="form-control" id="evtCategory" name="evtCategory">
						<option>MCS</option>
						<option>MATH</option>
						<option>ENG</option></select>
				</div>
			</div>
			<!-- Date row -->
			<div class="form-group row">
				<label for="evtStartDate" class="col-sm-1 form-control-label" align="right">Start Date:</label>
				<div class="col-sm-2">
					<input type="date" class="form-control" id="evtStartDate" name="evtStartDate" />
				</div>
				<label for="evtEndDate" class="col-sm-1 form-control-label" align="right">End Date:</label>
				<div class="col-sm-2">
					<input type="date" class="form-control" id="evtEndDate" name="evtEndDate" />
				</div>
			</div>
			<!-- Time row -->
			<div class="form-group row">
				<label for="evtStartTime" class="col-sm-1 form-control-label" align="right">Start Time:</label>
				<div class="col-sm-2">
					<input type="time" class="form-control" id="evtStartTime" name="evtStartTime" />
				</div>
				<label for="evtEndTime" class="col-sm-1 form-control-label" align="right">End Time:</label>
				<div class="col-sm-2">
					<input type="time" class="form-control" id="evtEndTime" name="evtEndTime" min ="0" />
				</div>
			</div>
			<!-- Description row -->
			<div class="form-group row">
				<label for="evtDesc" class="col-sm-1 form-control-label" align="right">Description:</label>
				<div class="col-sm-3">
					<textarea class="form-control" id="evtDesc" name="evtDesc" rows="3" placeholder="Detailed description of event."></textarea>
				</div>
			</div>
			<!-- hidden value for org id -->
			<div class="col-sm-1">
				<input type="hidden" class="form-control" id="evtOrgId" name="evtOrgId" value=<?php echo $orgId;?>></input>
			</div>
			<!-- Submit button -->
			<div class="form-group row">
				<label for="submit" class="col-sm-1 form-control-label" align="right"></label>
				<div class="col-sm-1" align="left">
					<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="createEvent.php">Submit</button>
				</div>
			</div>
		</form>
		<?php else: echo "<h1>{$message}</h1>";?>
		<?php endif ?>

		<footer>
			Created By: Matthew Castaldini, Hanan Jalnko, Kathleen Napier, Ian Tammis
		</footer>
	</body>
</html>
	
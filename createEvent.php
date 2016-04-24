<?php
	session_start();
	$thisPage = 'createEvent.php';
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
	<?php require 'requiredHeader.php'?>

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
	
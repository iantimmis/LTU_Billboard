<?php
	session_start();
	
	if(isset($_SESSION['orgId'])){
		$loggedIn = 1;
		$orgId = $_SESSION['orgId'];
	} else{
		$loggedIn = 0;
	}
?>
<!doctype html>
<html>
<head>
  <link href="stylesheet.css" rel="stylesheet" type="text/css" />
  <link href="bootstrap.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="jquery-2.2.2.min.js"></script>
	<script type="text/javascript" src="bootstrap.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8">
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
    <a href="create_events_page.htm"><button class="event">Create Event</button></a>
    <a href="index.html" id=logo>LTU Billboard</a>
    <span class="log-in">
		<a href=""><button id="loginButton">Log In</button></a>
		<br/>
		Not a user? <a href="">Join Now</a>
    </span>
    <br/>
    <br/>
    <br/>
	</header>

    <br />
	<br />
	<?php if($loggedIn): ?>
	<form class="form-horizontal" action="submitEvent.php" method="post" role="form">
	  <!-- Type Row -->
	  <div class="form-group row">
		<label class="col-sm-1" align="right">Event Type:&nbsp;</label>
		<div class="col-sm-5">
		  <div class="radio-inline">
			<label>
			  <input type="radio" name="evtPrivate" id="private" value="1" checked>
			  Private
			</label>
		  </div>
		  <div class="radio-inline">
			<label>
			  <input type="radio" name="evtPrivate" id="public" value="0">
			  Public
			</label>
		  </div>
		</div>
	  </div>
	  <!-- Event Name -->
	  <div class="form-group row">
		<label for="evtName" class="col-sm-1 form-control-label" align="right">Event Name:&nbsp;</label>
		<div class="col-sm-2">
		  <input type="text" class="form-control" id="evtName" name="evtName" placeholder="Event Name">
		</div>
	  </div>
	  <!-- Room row -->
	  <div class="form-group row">
		<label for="evtBuildingRoom" class="col-sm-1 form-control-label" align="right">Building & Room:&nbsp;</label>
		<div class="col-sm-1">
		  <input type="text" class="form-control" id="evtBuildingRoom" name="evtBuildingRoom" placeholder="Room">
		</div>
	  </div>
	  <!-- Event Category -->
	  <div class="form-group row">
		<label for="evtCategory" class="col-sm-1 form-control-label" align="right">Event Category:&nbsp;</label>
		<div class="col-sm-1">
		  <select type="multiple" class="form-control" id="evtCategory" name="evtCategory">
		  <option>MCS</option>
		  <option>MATH</option>
		  <option>ENG</option></select>
		</div>
	  </div>
	  <!-- Date row -->
	  <div class="form-group row">
		<label for="evtDesc" class="col-sm-1 form-control-label" align="right">Date:&nbsp;</label>
		<div class="col-sm-1">
		  <input type="date" class="form-control" id="evtDesc" name="evtDate">
		</div>
	  </div>
	  <!-- Time row -->
	  <div class="form-group row">
		<label for="evtStartTime" class="col-sm-1 form-control-label" align="right">Start Time:&nbsp;</label>
		<div class="col-sm-1">
		  <input type="time" class="form-control" id="evtStartTime" name="evtStartTime">
		</div>
		<label for="evtEndTime" class="col-sm-1 form-control-label" align="right">End Time:&nbsp;</label>
		<div class="col-sm-1">
		  <input type="time" class="form-control" id="evtEndTime" name="evtEndTime" min ="0">
		</div>
	  </div>
	  <!-- Description row -->
	  <div class="form-group row">
		<label for="evtDesc" class="col-sm-1 form-control-label" align="right">Description:&nbsp;</label>
		<div class="col-sm-3">
		  <textarea class="form-control" id="evtDesc" name="evtDesc" rows="3" placeholder="Detailed description of event."></textarea>
		</div>
	  </div>
	  <!-- hidden value for org id -->
		<div class="col-sm-1">
		  <input type="hidden" class="form-control" id="evtOrgId" name="evtOrgId" value=<?php echo $orgId;?>>
		</div>
	  <!-- Submit button -->
	  <div class="form-group row">
	  <label for="submit" class="col-sm-1 form-control-label" align="right">&nbsp;</label>
	  <div class="col-sm-1" align="left">
		<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="submit">Submit</button>
		</div>
	  </div>
	</form>
	<?php else: ?>
	<h1>You are not Logged in</h1>
	<?php endif ?>
	
	<footer>
      Created By: Matthew Castaldini, Hanan Jalnko, Kathleen Napier, Ian Tammis
    </footer>
</body>
</html>

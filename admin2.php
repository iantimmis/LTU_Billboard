<?php
	session_start();
    include 'upcomingEvents.php';
	$thisPage = "admin.php";
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
?>

<?php require 'adminModalFunctions.php';?>

<!DOCTYPE html>
<html>
<head>
  <link href="fcStylesheet.css" rel="stylesheet" type="text/css" />
  <link href="adminStylesheet.css" rel="stylesheet" type="text/css" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link href="bootstrap.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="jquery-2.2.2.min.js"></script>
	<script src='jquery.min.js'></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
	<script src='moment.min.js'></script>
	<script src='fullcalendar.js'></script>
	<script type="text/javascript" src="bootstrap.min.js"></script>
  <script>
		$(document).ready(function() {
			//used for create account panel radio buttons
			$("#orgAct").hide();
			$("input[name=actType]").on( "change", function() {
				var target = $(this).val();
				$(".chooseActType").hide();
				$("#"+target).show();
		});});
  </script>
  <script src='adminAjax.js'></script>
</head>
<body onload="onLoad()">
  <?php require 'requiredHeader.php';?>
  <div id="mainWrap">
    <div id="title">Administrator</div>
    <div id="acceptanceResponse"></div>
    <div id="type">Current Requests</div>
    <div id="requests">
      <div id="requestHeader">
          <div id="decision">
          <button class="choose" onclick="accept(1)">Accept</button>
          or 
          <button class="choose" onclick="accept(0)">Decline</button>
          requests.
        </div>
        <div id="sorting">
          <span id="sortType">Sort By:</span>
          <button class="sort" id="sortRecent" onclick="sortByRequestDate()">Recently Requested</button>
          <button class="sort" id="sortDate" onclick="sortByEventDate()">Event Date</button> 
        </div> 
        <div id-"type">
          <button class="requestType" id="eventReq" onclick="showEventRequests()">Events</button>
          <button class="requestType" id="orgReq" onclick="showOrgRequests()">Organizations</button>
          <span id="checkText">Select/Clear All: <input id="masterCheck" type="checkbox" onchange="changeAll()" /></span>
        </div>
      </div>
      <div id="requestTable">
      </div>
      <div id="navbar">
       <button class="navButton" id="prev" onclick="prev()">Prev</button>
       <button class="navButton" id="next" onclick="next()">Next</button>
      </div>
    </div>
    <div id="sideBar">
      <div id="searchBox">
        <p><h3>Search for an event:</h3></p>
        <form action="searchEvents.php" method="POST">
          <input type="search" name="keywords" />
          <input type="submit" value="Search" />
        </form>
        <br/><br/>
      </div>
      <div id="links">
        <a href="index.php" class="link">View Calendar</a><br/>
        <!-- Links to trigger modals -->
        <a data-toggle="modal" href="#announceModal" class="link">Add/Edit Announcements</a><br/>
        <a data-toggle="modal" href="#orgListModal" class="link">View Organizations</a>
      </div>
      <br/>
      <hr/>
      <div id="eventsThisMonth">
        <h3>Upcoming Events</h3>
        <ul id="theList"><?php upcomingEvents() ?></ul>
      </div>
    </div>
  </div> 
  <div id="bottomWrapper">
    <footer>
      Created By: Matthew Castaldini, Hanan Jalnko, Kathleen Napier, Ian Timmis
    </footer> 
  </div>
  
<!-- announceModal -->
<div id="announceModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- modal content -->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">LTU Announcements</h4>
      </div>
      <div class="modal-body">
        <!-- modal panels -->
	<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class ="active"><a href="#add" aria-controls="add" role="tab" data-toggle="tab">Add Announcement</a></li>
	<li role="presentation"><a href="#edit" aria-controls="edit" role="tab" data-toggle="tab">Edit Announcement</a></li>
	</ul>
        <div class="tab-content">
          <!-- create new announcement -->
          <div role="tabpanel" class="tab-pane active" id="add"><br />
            <form action="announcements.php" method="POST" role="form">
              <div class="form-group">
                <textarea class="form-control" rows="6" id="addAnn" name="addAnn">Enter new announcement...</textarea>
              </div>
              <button type="submit" class="btn btn-default" name="source" value="admin.php">Add</button>
            </form>
          </div>
          <!-- edit/delete current announcements -->
          <div role="tabpanel" class="tab-pane" id="edit"><br />
            <div id="modifyTable">
              <?php modalTable(); ?>
            </div> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end of announceModal -->

<!-- orgListModal -->
<div id="orgListModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- modal content -->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Registered Organizations</h4>
      </div>
      <div class="modal-body">
        <div id="allOrgs">
          <?php buildOrgList(); ?> 
        </div> 
      </div>
    </div>
  </div>
</div>
<!-- end of orgListModal -->

</body>
</html>

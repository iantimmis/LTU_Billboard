<?php
	session_start();
    include 'upcomingEvents.php';
	$thisPage = "admin.php";
	
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
	$loginAttempted=false;
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
	}
	//check session to see if logged in and user and get info if true
	$loggedInAsUser = $loggedInAsOrg = $loggedInAsAdmin = false;
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

<?php require 'adminModalFunctions.php';?>

<!DOCTYPE html>
<html>
<head>
  <link rel="icon" href="favicon.ico"/>
  <link href="stylesheet.css" rel="stylesheet" type="text/css" />
  <link href="adminStylesheet.css" rel="stylesheet" type="text/css" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link href="bootstrap.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="jquery-2.2.2.min.js"></script>
	<script src='jquery.min.js'></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
	<script src='moment.min.js'></script>
	<script src='fullcalendar.js'></script>
	<script type="text/javascript" src="bootstrap.min.js"></script>
        <link rel="icon" href="favicon.ico"/>

  <script>
		$(document).ready(function() {
			$('#loginTabs a:last').hide();
			$('#loginTabs a[href="#loginAsOrg"]').hide();
			$('#createAccountLink').hide();
			<?php if($loginAttempted):?>
				<?php if(strcmp($type,'stu')==0):?>
					<?php if(!$loginSuccess):?>
					//login student fail
					$('#loginModal').modal('show');
					$('#stuLoginMessage').html("Login Failed");
					$('#stuLoginMessage').toggleClass('error');
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
			});
  </script>
  <?php if($loggedInAsAdmin):?>
  <script src='adminAjax.js'></script>
  <?php endif;?>
</head>
<body <?php if($loggedInAsAdmin){echo "onload='onLoad()'";}?>>
  <?php require 'requiredHeader.php';?>
  <div id="mainWrap">
  <?php if($loggedInAsAdmin):?>
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
	<?php else: ?>
	<h1>Need to be logged in as an administrator to view this page</h1>
<?php endif;?>
  <div id="bottomWrapper">
    <footer>
      Created By: Matthew Castaldini, Hanan Jalnko, Kathleen Napier, Ian Timmis
    </footer> 
  </div>
</body>
</html>

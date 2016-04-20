<!DOCTYPE html>
<html lang="en">
<head>
	<link href="stylesheet.css" rel="stylesheet" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link href="bootstrap.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="jquery-2.2.2.min.js"></script>
	<script type="text/javascript" src="bootstrap.min.js"></script>
</head>
<body>
  <header>
    <a href="Create Events Page.htm"><button class="event">Create Event</button></a>
    <a href="index.html" id=logo>LTU Billboard</a>
    <span class="log-in">
      <button id="loginButton" type="button" data-toggle="modal" data-target="#loginModal">Log In</button>
      <br/>
      Not a user? <a href="">Join Now</a>
    </span>
    <br/>
    <br/>
    <br/>
	
  </header>
 <div class="main">
  <!-- Modal -->
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
			<li role="presentation" class="active"><a href="#loginAsStudent" aria-controls="loginAsStudent" role="tab" data-toggle="tab">Log-In As Student</a></li>
			<li role="presentation"><a href="#loginAsOrg" aria-controls="loginAsOrg" role="tab" data-toggle="tab">Log-In As Organization</a></li>
		</ul>
		
		<!-- Div for tab content -->
		<div class="tab-content">
		<!-- First tab for account creation form -->
			
			
			
			<!-- Second tab for student log-in form -->
			<div role="tabpanel" class="tab-pane active" id="loginAsStudent"><br>
			<!--Form for logging in. Just takes username, password -->
			<form action="loginAsStudent.php" method ="post" role="form">
			  <!-- Username row -->
			  <div class="form-group row">
				<label for="studentUsername" class="col-sm-4 form-control-label" align="right">Username</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="studentUsername" name="studentUsername" placeholder="Username">
				</div>
			  </div>
			  <!-- Password row -->
			  <div class="form-group row">
				<label for="studentPassword" class="col-sm-4 form-control-label" align="right">Password</label>
				<div class="col-sm-5">
				  <input type="password" class="form-control" id="studentPassword" name="studentPassword" placeholder="Password">
				</div>
			  </div>
			  <!-- Submit button -->
				<hr />
			  <div class="form-group row">
				<div class="col-sm-12" align="right">
					<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="submit">Submit</button>
					<button type = "submit" class ="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
			</form>
			<!-- End of log-in form and 2nd tab -->
			</div>
			
			<!-- Third tab for Organization log-in form -->
			<div role="tabpanel" class="tab-pane" id="loginAsOrg"><br>
			<!--Form for logging in. Just takes username, password -->
			<form action="loginAsOrg.php" method ="post" role="form">
			  <!-- Username row -->
			  <div class="form-group row">
				<label for="orgUsername" class="col-sm-4 form-control-label" align="right">Username</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="orgUsername" name ="orgUsername"placeholder="Username">
				</div>
			  </div>
			  <!-- Password row -->
			  <div class="form-group row">
				<label for="orgPassword" class="col-sm-4 form-control-label" align="right">Password</label>
				<div class="col-sm-5">
				  <input type="password" class="form-control" id="orgPassword" name="orgPassword" placeholder="Password">
				</div>
			  </div>
			<!-- Submit button -->
				<hr />
			  <div class="form-group row">
				<div class="col-sm-12" align="right">
					<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="submit">Submit</button>
					<button type = "submit" class ="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
			</form>
			<!-- End of log-in form and 3rd tab -->
			</div>
			
			
			
		</div>
      </div>
	  
    </div>
  </div>
</div>
</div>
</body>
</html>

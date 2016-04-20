<?php 
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login Page</title>
	
	<link href="stylesheet.css" rel="stylesheet" type="text/css">
	
	<link href="bootstrap.min.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="jquery-2.2.2.min.js"></script>
	<script type="text/javascript" src="bootstrap.min.js"></script>
    <script type="text/javascript">
		$(function() {
			$("#loginId").on("click",toggleLoginModal);
		});
		function toggleLoginModal(evt){
			$('#loginModal').modal('toggle');
		}
		$(function() {
			$("#logoutId").on("click",logOutFunction);
		});
		function logOutFunction(evt){
			window.location.href = "logout.php"
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
<div class="main">
<button id="loginId" type="button" class="btn btn-default"><br>Log In</button><br /> <br />
<button id="logoutId" type="button" class="btn btn-default"><br>Log Out</button>

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
			<li role="presentation" class ="active"><a href="#createAccount" aria-controls="createAccount" role="tab" data-toggle="tab">Create Account</a></li>
			<li role="presentation"><a href="#loginAsStudent" aria-controls="loginAsStudent" role="tab" data-toggle="tab">Log-In As Student</a></li>
			<li role="presentation"><a href="#loginAsOrg" aria-controls="loginAsOrg" role="tab" data-toggle="tab">Log-In As Organization</a></li>
		</ul>
		
		<!-- Div for tab content -->
		<div class="tab-content">
		<!-- First tab for account creation form -->
			<div role="tabpanel" class="tab-pane" id="createAccount"><br>
			<!--Form for account creation. Just takes username, password twice, and account type -->
			<form action = "createAccount.php" method ="post" role="form">
			  <!-- Radio button row. -->
			  <div class="form-group row">
				<label class="col-sm-4" align="right">Account Type</label>
				<div class="col-sm-5">
				  <div class="radio-inline">
					<label>
					  <input type="radio" name="actType" id="studentAct" value="studentAct" checked>
					  Student
					</label>
				  </div>
				  <div class="radio-inline">
					<label>
					  <input type="radio" name="actType" id="orgAct" value="orgAct">
					  Organization
					</label>
				  </div>
				</div>
			  </div>
			  <!-- Email row -->
			  <div class="form-group row">
				<label for="createUsername" class="col-sm-4 form-control-label" align="right">Username</label>
				<div class="col-sm-7">
				  <input type="email" class="form-control" id="createUsername" name="createUsername" placeholder="Username">
				</div>
			  </div>
			  <!-- Password row -->
			  <div class="form-group row">
				<label for="createPassword" class="col-sm-4 form-control-label" align="right">Password</label>
				<div class="col-sm-5">
				  <input type="password" class="form-control" id="createPassword" name="createPassword" placeholder="Password">
				</div>
			  </div>
			  <!-- Confirm password row -->
			  <div class="form-group row">
				<label for="confirmPassword" class="col-sm-4 form-control-label" align="right">Confirm Password</label>
				<div class="col-sm-5">
				  <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Password">
				</div>
			  </div>
			  <div class="form-group row">
				<label for="submit" class="col-sm-1 form-control-label" align="right">&nbsp;</label>
				<div class="col-sm-1" align="left">
					<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="submit">Submit</button>
				</div>
			</div>
			</form>
			<!-- End of account creation form and first tab -->
			</div>
			
			
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
			  <div class="form-group row">
				<label for="submit" class="col-sm-1 form-control-label" align="right">&nbsp;</label>
				<div class="col-sm-1" align="left">
					<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="submit">Submit</button>
				</div>
			</div>
			</form>
			<!-- End of log-in form and 2nd tab -->
			</div>
			
			<!-- Third tab for Organization log-in form -->
			<div role="tabpanel" class="tab-pane active" id="loginAsOrg"><br>
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
			<div class="form-group row">
				<label for="submit" class="col-sm-1 form-control-label" align="right">&nbsp;</label>
				<div class="col-sm-1" align="left">
					<button type = "submit" class ="btn btn-primary" id = "submit" name="submit" value="submit">Submit</button>
				</div>
			</div>
			</form>
			<!-- End of log-in form and 3rd tab -->
			</div>
			
			
			
		</div>
      </div>
	  
	  <!-- Modal bottom buttons-->
      <div class="modal-footer">
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
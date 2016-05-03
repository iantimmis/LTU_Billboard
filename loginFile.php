<?php
	function login(){
		$email = $password = $type = "";
		$emailErr = $passwordErr = $loginMessage = "";
		$loginAttempted = $loginSuccess = false;
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$loginAttempted = true;
			if(!empty($_POST['type'])){$type = $_POST['type'];}
			if(strcmp($type,'stu')==0)//logging in as student
			{
				if(empty($_POST['studentEmail']))
					$emailErr = "Email is Required";
				elseif(empty($_POST['studentPassword']))
					$passwordErr = "Password is Required";
				else
				{
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
					$email = cleanInput($_POST['studentEmail']);
					$password = cleanInput($_POST['studentPassword']);
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
						$loginMessage = "Login Successful";
					}
				}
			}
			if(strcmp($type,'org')==0)//logging in as an organization
			{
				if(empty($_POST['orgEmail']))
					$emailErr = "Email is Required";
				elseif(empty($_POST['orgPassword']))
					$passwordErr = "Password is Required";
				else
				{
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
					$email = cleanInput($_POST['orgEmail']);
					$password = cleanInput($_POST['orgPassword']);
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
						$loginMessage = "Login Successful";
					}
				}
			}
		}
	}
	function cleanInput($input){
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
      	return $input;
	}
?>
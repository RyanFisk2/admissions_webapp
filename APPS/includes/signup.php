<!DOCTYPE HTML>
<?php	
	require_once('utils.php');	
	
	// Connect to the database	
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	

	// Clear the error message	
	$error_msg = "";	
	$check = True;
	if (isset($_POST["sign_up"])) {	
		$user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));	
		$pass = mysqli_real_escape_string($dbc, trim($_POST['password']));	
		$cpass = mysqli_real_escape_string($dbc, trim($_POST['Cpassword']));	
		if ($pass != $cpass){ //check password
			$error_msg = "You must enter the same password to sign up!"; 
			$check = False;
		}

		$fname = test_input($_POST["Fname"]); //check name
	 	if (!preg_match("/^[a-zA-Z ]*$/",$fname)) {
  			$error_msg = "Name should be only letters!"; 
			$check = False;
		}
		$mname = test_input($_POST["Mname"]);
	 	if (!preg_match("/^[a-zA-Z ]*$/",$mname)) {
  			$error_msg = "Name should be only letters!"; 
			$check = False;
		}
		$lname = test_input($_POST["Lname"]);
	 	if (!preg_match("/^[a-zA-Z ]*$/",$lname)) {
  			$error_msg = "Name should be only letters!"; 
			$check = False;
		}
		$email = test_input($_POST["Email"]); //check email
	    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
  			$error_msg = "invaild email address!"; 
			$check = False;
		}

		// add data to database and go to index page		
		if ($check){
			//fencepost loop to check for duplicate entries
			$userID = rand(0000, 99999999);
			$checkDupQuery = "SELECT * FROM users WHERE id = '$userID'";
			$dupResult = mysqli_query($dbc, $checkDupQuery);

			while(mysqli_num_rows($dupResult) > 0){
				//randomly generated ID already in database
				echo"regenerating random id";
				$userID = rand(0, 99999999);
				$dupResult = mysqli_query($dbc, $checkDupQuery);
			}

			//insert value into database
			$query = "INSERT INTO users (id, p_level, password) VALUES ('$userID', 7, '$pass')";
			try_insert($dbc, $query, 'create account');

			//add user info to applicants table
			$ssn = rand(0, 999999999);
			$appQuery = "INSERT INTO applicant VALUES ('$userID', '$fname', '$lname', 'n/a', '$email', '$ssn', 0)";
			try_insert($dbc, $appQuery, 'add applicant');

			//set session
			$query = "SELECT id, p_level FROM users WHERE id = '$userID' AND password = '$pass'"; 	
			// echo $query;	
			$data = mysqli_query($dbc, $query);	

			// If The sign-up is OK 	
			if (mysqli_num_rows($data) == 1) {	
				$row = mysqli_fetch_array($data);	

				$_SESSION['id'] = $row["id"];	
				$_SESSION['acc_type'] = $row["p_level"];

				//email user their login info
				$msg = "Thank you for signing up!  
					Your login id is " .$_SESSION['id'] ."  
					Your password is " .$pass;

				$msg = wordwrap($msg, 70);

				mail($email, "Your Account Info", $msg);	
				
			}
			header('Location: ' . '../index.php');	
		}
	}


	$page_title = 'Sign Up';
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
 	}
?>
<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.css">

<div class="container">
<div class="row">
  <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
    <div class="card card-signin my-5">
      <div class="card-body">
        <h5 class="card-title text-center">Sign Up</h5>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="login">
          <div class="form-label-group">
            <input id="username" type="text" name="username" value="<?php if (!empty($user_username)) echo$user_username; ?>" class="form-control" placeholder="Username" required autofocus>
            <label for="username">Username</label>
          </div>

          <div class="form-label-group">
            <input id="password" type="text" name="password" class="form-control" placeholder="Password" required>
            <label for="password">Password</label>
          </div>
          <div class="form-label-group">
            <input id="Cpassword" type="text" name="Cpassword" class="form-control" placeholder="Confirm Password" required>
            <label for="Cpassword">Confirm Password</label>
          </div>

          <div class="form-label-group">
            <input id="Fname" type="text" name="Fname" class="form-control" placeholder="First Name" required>
            <label for="Fname">First Name</label>
          </div>
          <div class="form-label-group">
            <input id="Mname" type="text" name="Mname" class="form-control" placeholder="Middle Name">
            <label for="Mname">Middle Name</label>
          </div>
          <div class="form-label-group">
            <input id="Lname" type="text" name="Lname" class="form-control" placeholder="Last Name" required>
            <label for="Lname">Last Name</label>
          </div>

          <div class="form-label-group">
            <input id="Email" type="text" name="Email" class="form-control" placeholder="Email" required>
            <label for="Email">Email</label>
          </div>
          <button class="btn btn-lg btn-primary btn-block text-uppercase" name="sign_up" type="sign_up">Sign up</button>
          <?php
			// if (empty($_SESSION['userID'])) {
				echo '<p style="color: red" class="error">' . $error_msg . '</p>';
          	// }
          ?>
        </form>
      </div>
    </div>
  </div>
</div>
</div>

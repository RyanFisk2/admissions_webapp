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
			$title = $_POST["Title"];
			$role = 0;
			if (empty($mname)){
				$fullname = $fname . " " . $lname ;
			}
			else{
				$fullname = $fname . " " . $mname. " " . $lname ;
			}
			if ($title == "SA"){
				$role = -1;
			}
			if ($title == "applicant"){
				$role = 1;
			}
			if ($title == "CAC"){
				$role = 2;
			}
			if ($title == "GS"){
				$role = 3;
			}
			if ($title == "FR"){
				$role = 4;
			}
			//insert value into database
			$query = "INSERT INTO user (username, password, name, email, roleID) VALUES ('" . $user_username . "', '" . $pass ."', '" . $fullname ."', '" . $email ."', '" . $role ."')";
			try_query($dbc, $query, 'create account');
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

          <div class="form-label-group">
            <input id="Title" type="text" name="Title" class="form-control" placeholder="Title: GS, CAC, etc" required>
            <label for="Email">Title</label>
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

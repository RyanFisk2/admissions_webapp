<!DOCTYPE HTML>
<?php	
	require_once('utils.php');	

	// Clear the error message	
	$error_msg = "";	

	if (isset($_POST["sign_up"])) {	
		// go to sign up page		
		header('Location: ' . 'signup.php');	
	}	
	// if the user isn't logged in, try to log them in	
	if (!isset($_SESSION['userID'])) {	
		if (isset($_POST['submit'])) {	
			//echo 'running login logic';	
			// Connect to the database	
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
			// Grab the user-entered log-in data	
			$user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));	
			$user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));	
	    	

			if (!empty($user_username) && !empty($user_password)) {	
				// TODO: Look up the username and password in the database	
				$query = "SELECT userID, username, name, roleID FROM user WHERE username LIKE '" . $user_username . "' AND password LIKE '" . $user_password . "'";  	
				// echo $query;	
				$data = mysqli_query($dbc, $query);	

					// If The log-in is OK 	
				if (mysqli_num_rows($data) == 1) {	
					$row = mysqli_fetch_array($data);	
					echo "valid login";
					$_SESSION['userID'] = $row["userID"];	
					$_SESSION['username'] = $row["username"];
					$_SESSION['name'] = $row["name"];
					$_SESSION['role'] = $row["roleID"];
					//TODO: redirect to index.php 	
					header('Location: ' . '../index.php');	
				}	
				else {	
					// The username/password are incorrect so set an error message	
					$error_msg = 'Enter a valid username and password to log in.';	
				}	
			}	
			else {	
				// The username/password weren't entered so set an error message	
				$error_msg = 'Enter your username and password to log in.';	
			}	
		}	
	}

// Insert the page header
$page_title = 'Log In';
?>
<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.css">

<div class="container">
<div class="row">
  <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
    <div class="card card-signin my-5">
      <div class="card-body">
        <h5 class="card-title text-center">Sign In</h5>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="login">
          <div class="form-label-group">
            <input id="username" type="text" name="username" value="<?php if (!empty($user_username)) echo$user_username; ?>" class="form-control" placeholder="Username" required autofocus>
            <label for="username">Username</label>
          </div>

          <div class="form-label-group">
            <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
            <label for="password">Password</label>
          </div>
          <button class="btn btn-lg btn-primary btn-block text-uppercase" name="submit" type="submit">Sign in</button>
          <?php
			if (empty($_SESSION['userID'])) {
				echo '<p style="color: red" class="error">' . $error_msg . '</p>';
          	}
          ?>
          <hr class="my-4">
        </form>	
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="signup">
        <p> Don't have an account?</p>
          <button class="btn btn-lg btn-primary btn-block text-uppercase" name="sign_up" type="sign_up">Sign up</button>
        </form>	
      </div>
    </div>
  </div>
</div>
</div>


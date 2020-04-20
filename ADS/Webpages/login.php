<html>
<?php
	
	session_start(); //Starts the session

	$error_msg = ""; //Start with no error msg
	
	$pg_title = "Login";
	//require_once('header.php');
	echo '<div class="login">';
	echo '<link href="login_stylesheet.css" rel="stylesheet" type="text/css">';
	echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
	//Stylesheet tips from codeshack

	//This php block will handle login validation
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		// Connect to the database
		require_once('connectvars.php');
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		// Grab the user-entered log-in data TODO update w/ pyterhub
		$user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
		$user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

		//For temp testing, we will not use dbc connection
		$user_username = trim($_POST['username']);
		$user_password = trim($_POST['password']);

		if (!empty($user_username) && !empty($user_password)) {
			$query = "SELECT * FROM user WHERE uid='$user_username' AND password='$user_password'";
          	$data = mysqli_query($dbc, $query);
			
			if (!$data){
				$error_msg = 'Please enter valid username and password';
			}
          	else if (mysqli_num_rows($data) == 1) {
				$row = mysqli_fetch_array($data);

            	$_SESSION['user_id'] = $row['uid'];
				$_SESSION['username'] = $row['fname'];
				$_SESSION['acc_type'] = $row['account'];

				//Go to homepage
				$_SESSION['gpacalc'] = false;
            	header('Location:index.php');
			}
			else {
				$error_msg = 'Please enter valid username and password';
			}
		}
		else{
			$error_msg = 'Please enter valid username and password';
		}
	}

	echo '<body>';

	if (empty($_SESSION['user_id'])){
		echo '<p class="error">' . $error_msg . '</p>';
	
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
			<h2><img src="CW_logo.png" alt="Logo" width="120" height="85"></h2>
      		<h1>Log In</h1>
      		<label for="username"><i class="glyphicon glyphicon-user"></i></label>
      		<input type="text" name="username" placeholder="Username" /><br />
      		<label for="password"><i class="glyphicon glyphicon-lock"></i></label>
      		<input type="password" name="password" placeholder="Password"/>
    	</fieldset>
    	<input type="submit" value="Log In" name="submit" />
	</form>
</div>



<?php
	}

	else{
		echo '<p class="login">You are logged in as ' . $_SESSION['user_id'] . '. Please wait up to 5 seconds to be redirected to homepage.</p>';
		echo '<meta http-equiv = "refresh" content = "4; url=index.php" />';
	}

	//require_once('footer.php');
?>
</body>
 </html>



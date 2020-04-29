  
<!DOCTYPE HTML>

<!-- Masthead -->
<?php

	if(!(isset($_SESSION))){
		session_start();
	}

	if(!(isset($session['role']))){
		echo"<body>
			<h2 align='center'>Sign in to Continue or Application or Create an Account to Apply</h2>
			</body>";
	}else{
		//use role number to get name and information about logged in user
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$userID = $session['id'];

		if($session['role'] == 7){
			$userQuery = "SELECT fname, lname
					FROM applicant
					WHERE app_id = '$userID'";
		}else if($session['role'] < 5){
			$userQuery = "SELECT fname, lname, reviewer
					FROM faculty
					WHERE f_id = '$userID'";
		}

		$result = mysqli_query($dbc, $userQuery);

		$userInfo = mysqli_fetch_array($result);

		if($userInfo["reviewer"]){
			$session['reviewer'] = $reviewer = $userInfo["reviewer"];
		}

		$fname = $userInfo['fname'];
		$lname = $userInfo['lname'];

		echo"<body>
			<h2 align='center'>Welcome $fname $lname </h2><br/>
			</body>";

	}

?>



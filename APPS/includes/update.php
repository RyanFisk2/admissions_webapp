<!DOCTYPE HTML>
<?php	
	require_once('utils.php');
	
	$mess = "";
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT * FROM user";  //get all info from database
	$data = mysqli_query($dbc, $query);
	if (isset($_POST['submit'])){
		$userid = $_POST['userid'];
		$username = $_POST['username'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['pass'];
		if ($_POST['role'] == "SA"){
			$role = -1;
		}
		else if ($_POST['role'] == "Applicant"){
			$role = 1;
		}
		else if ($_POST['role'] == "CAC"){
			$role = 2;
		}
		else if ($_POST['role'] == "GS"){
			$role = 3;
		}
		else if ($_POST['role'] == "FR"){
			$role = 4;
		}
		$ssn = $_POST['ssn'];
		$addr = $_POST['addr'];
		if (empty($ssn)){
			$query = "UPDATE user U SET userID = '$userid', username = '$username', name = '$name', email = '$email', password = '$password', address = '$addr', ssn = NULL, roleID = '$role' WHERE U.userID = $userid or  U.username = '$username' or U.name = '$name' or U.email = '$email'";
		}
		else{
			$query = "UPDATE user U SET userID = '$userid', username = '$username', name = '$name', email = '$email', password = '$password', address = '$addr', ssn = '$ssn', roleID = '$role' WHERE U.userID = $userid or  U.username = '$username' or U.name = '$name' or U.email = '$email'";
		}
		
		try_query($dbc, $query, 'Update');
		$_SESSION['name'] = $name;
		$_SESSION['userID'] = $userid;
		header('Location: ' . '../index.php');
	}
?>

<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.css">
<?php
	while ($row = mysqli_fetch_array($data)) {
	if ($row['roleID'] == -1){
		$pos = "SA";
	}
	else if ($row['roleID'] == 1){
		$pos = "Applicant";
	}
	else if ($row['roleID'] == 2){
		$pos = "CAC";
	}
	else if ($row['roleID'] == 3){
		$pos = "GS";
	}
	else if ($row['roleID'] == 4){
		$pos = "FR";
	}
?>	
	<div class="container">
		<h5 class="card-title text-center"><?php echo $row['name'];?></h5>
		<form class="form-inline" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		  <div class="form-group mx-sm-3 mb-2">
		  	<div class="container">
			    <label for="userid" >Userid</label>
			    <input type="text" readonly class="form-control" id="userid" name="userid" value="<?php echo $row['userID'];?>">
			</div>
		  </div>
		  <div class="form-group mx-sm-3 mb-2">
		  	<div class="container">
			  	<label for="username" >Username</label>
			    <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username'];?>">
			</div>
		  </div>
		  <div class="form-group mx-sm-3 mb-2">
		  	<div class="container">
			  	<label for="name" >Name</label>
			    <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name'];?>">
			</div>
		  </div>
		  <div class="form-group mx-sm-3 mb-2">
		  	<div class="container">
			  	<label for="email" >Email</label>
			    <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email'];?>">
			</div>
		  </div>
		  <div class="form-group mx-sm-3 mb-2">
		  	<div class="container">
			  	<label for="email" >Password</label>
			    <input type="text" class="form-control" id="pass" name="pass" value="<?php echo $row['password'];?>">
			</div>
		  </div>
		  <div class="form-group mx-sm-3 mb-2">
		  	<div class="container">
			  	<label for="addr" >Address</label>
			    <input type="text" class="form-control" id="addr" name="addr" value="<?php echo $row['address'];?>">
			</div>
		  </div>
		  <div class="form-group mx-sm-3 mb-2">
		  	<div class="container">
			  	<label for="ssn" >Ssn</label>
			    <input type="text" class="form-control" id="ssn" name="ssn" value="<?php echo $row['ssn'];?>">
			</div>
		  </div>
		  <div class="form-group mx-sm-3 mb-2">
		  	<div class="container">
			  	<label for="role" >Role</label>
			    <input type="text" class="form-control" id="role" name="role" value="<?php echo $pos;?>">
			</div>
		  </div>
		  <div class="form-group mx-sm-3 mb-2">
		  	<div class="container">
		  	<button type="submit" name="submit" class="btn btn-lg btn-primary btn-block">Update</button>
		  	</div>
		  </div>
		</form>
	</div>
	<br>

<?php
	}
?>	

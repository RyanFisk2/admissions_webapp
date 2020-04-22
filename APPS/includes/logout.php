<!DOCTYPE HTML>
<?php
	// Logout logic from Steed's HW4. Needs updating.
	session_start();
	if (isset($_SESSION["userID"])) {
		$_SESSION = array();
		session_destroy();
		echo "<script>alert('Log Out');document.location.reload()</script>";
	}
	if(isset($_POST["back"])) {
	 // Redirect them to the login page
		header('Location: ' . '../index.php');
		header('Refresh:0');
	}
	session_destroy();
?>

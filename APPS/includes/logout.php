<!DOCTYPE HTML>
<?php
	// Logout logic from Steed's HW4. Needs updating.
	session_start();
	if (isset($_SESSION["id"])) {
		$_SESSION = array();
		session_destroy();
		header('Location: ' . '../index.php');
	}
	if(isset($_POST["back"])) {
	 // Redirect them to the login page
		header('Location: ' . '../index.php');
		header('Refresh:0');
	}
	session_destroy();
?>

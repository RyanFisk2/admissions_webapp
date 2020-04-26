<?php
	require_once('../includes/connectvars.php');	
	require_once('../includes/utils.php');
	ob_start();
	require_once('createApplication.php');
	ob_end_clean();

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET)) {
		$query = "SELECT submitted FROM application_form WHERE applicationID = " . $_SESSION["applicationID"];
		$data = try_query($dbc, $query, NULL);
		$row = mysqli_fetch_row($data);
		echo json_encode($row);
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
		$query = "UPDATE application_form SET submitted = 1, decision = 1 WHERE applicationID = " . $_SESSION["applicationID"];
		try_insert($dbc, $query, 'Submitted application with ID ' . $_SESSION["applicationID"]);
		$insertApplicantQuery = "UPDATE applicant SET app_status = 1 WHERE userID = ". $_SESSION['id'];
		try_insert($dbc, $query, NULL);
	}
?>

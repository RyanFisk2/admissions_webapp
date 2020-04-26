<?php
	require_once('../includes/connectvars.php');	
	require_once('../includes/utils.php');

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$query = "SELECT address1, address2, city, state, zip, degree, interest, term FROM application_form ";
		$query .= "WHERE userID = " . $_SESSION["userID"];
		$data = try_query($dbc, $query, NULL);
		$keys = array("address1", "address2", "city", "state", "zip", "degree", "interest", "term");
		$row = mysqli_fetch_row($data);	
		if (is_null($row)) {
			$output = array();
		}
		else {
			$output = array_combine($keys, $row);
		}
		echo json_encode($output);
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
		// create application if not already exists
		require_once('createApplication.php');
		$query = "UPDATE application_form SET ";
		if (isset($_POST["address1"])) $query .= "address1 = '" . $_POST["address1"] . "', ";
		if (isset($_POST["address2"])) $query .= "address2 = '" . $_POST["address2"] . "', ";
		if (isset($_POST["city"])) $query .= "city = '" . $_POST["city"] . "', ";
		if (isset($_POST["state"])) $query .= "state = '" . $_POST["state"] . "', ";
		if (isset($_POST["zip"])) $query .= "zip = " . $_POST["zip"] . ", ";
		if (isset($_POST["interest"])) $query .= "interest = '" . $_POST["interest"] . "', ";
		if (isset($_POST["term"])) $query .= "term = '" . $_POST["term"] . "', ";
		if (isset($_POST["degree"])) $query .= "degree = '" . $_POST["degree"] . "'";
		$query = rtrim($query, ", ");
		$query .= " WHERE userID = " . $_SESSION["id"];
		try_insert($dbc, $query, "Updated database entry with personal info.");
	}
?>

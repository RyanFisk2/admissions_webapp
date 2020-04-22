<?php
	require_once('../includes/connectvars.php');	
	require_once('../includes/utils.php');

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$keys = array("letter");

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$query = "SELECT letter FROM rec_letter ";
		$query .= "WHERE letterID = " . $_SESSION["letterID"];
		$data = try_query($dbc, $query, NULL);
		$row = mysqli_fetch_array($data);
		echo json_encode($row);
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["letter"])) {
		$query = "UPDATE rec_letter SET letter = '" . $_POST["letter"] . "', ";
		$query .= "received = now()";
		$query .= " WHERE letterID = " . $_SESSION["letterID"];
		try_insert($dbc, $query, "Updated database entry with rec letter.");
	}
?>
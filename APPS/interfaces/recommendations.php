<?php
	require_once('../includes/connectvars.php');	
	require_once('../includes/utils.php');

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$keys = array("writerName", "writerEmail", "writerTitle", "writerEmployer");

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$query = "SELECT writerName, writerEmail, writerTitle, writerEmployer FROM rec_letter ";
		$query .= "WHERE applicationID = " . $_SESSION["appID"];
		$data = try_query($dbc, $query, NULL);
		$output = array();
		while ($row = mysqli_fetch_row($data)) {
			array_push($output, array_combine($keys, $row));
		}
		echo json_encode($output);
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
		// create application if not already exists
		$query = "INSERT INTO rec_letter (writerName, writerEmail, writerTitle, writerEmployer, applicationID) VALUES ( ";
		foreach ($keys as $key) {
			$query .= "'" . $_POST[$key] . "', ";
		}
		$query .= $_SESSION["appID"] . ")";
		$letter_id = try_insert($dbc, $query, "Added letter writer to database.");

		$url = ROOT_URL . "/recommendation.php?id=" . $letter_id;
		$msg = 'An applicant has requested that you write them a letter of recommendation. Please submit it <a href="' . $url . '">here</a>. Do not share your link with anyone.<br><br>';
		
		$header = "From: no-reply@gwupyterhub.seas.gwu.edu\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		mail($_POST["writerEmail"], "Reference request - GW Graduate School", $msg, $header);
	}
?>
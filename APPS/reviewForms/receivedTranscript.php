<!DOCTYPE HTML>

	<?php
		require_once('../includes/connectvars.php');
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$applicationID = $_GET['applicationID'];
		$receivedDate = date("Y/m/d");

		$addTranscriptQuery = "INSERT INTO transcript VALUES ($applicationID, 'fake-transcript.pdf', $receivedDate)";
		
		if($addTranscriptResult = mysqli_query($dbc, $addTranscriptQuery)){
			echo "happy times";
			echo "<script> loadForm(\"./applicants.php\"); </script>";
			
		}else{
			echo"error inserting transcript";
		}
	?>

<!DOCTYPE htl PUBLIC"-//W3C//DTD XHTML Transitional 1.0//EN"
	"http://www.w3.org/TR/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/thtml" xml:lang="en" lang="en">

<head>

	<meta http-equiv="Content-Type" content="text/html cahrset=utf-8" />

	<!-- link the styling from the bootstrap -->
	<link rel="Stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="Stylesheet" href="templCss/landing-pag-min.css">

	<title>Review Applications</title>

	<h1 align="center">Pending Applications</h1><br/>

</head>

<body>

	<h2 align="center">Select an application below to being review</h1><br/>


	<p align="center">Current Applicants</p><br/>

	<!-- get applicants from DB -->
	<?php

	include_once('./includes/connectvars.php');
	session_start();
	//connect to DB and get current applicants
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if(!($dbc)){
		die('Error connecting to DB');
	}

	//query for applicants (roleID 1) that have submitted the application (submitted = 1)
	$getApplicants = "SELECT applicant.fname, applicant.lname, applicant.app_id, application_form.applicationID
				FROM applicant, users, application_form
				WHERE users.p_level = '7' AND users.id = applicant.app_id AND users.id = application_form.userID AND application_form.submitted = 1";
	$applicantResult = mysqli_query($dbc, $getApplicants);

	$role = $_SESSION['acc_type'];
	$fID = $_SESSION['user_id'];

	echo "<br/>
	<div id='content'>
		<table style='width:100%' align='center'>
			<tr>
				<th>Applicant Name</th>
				<th>Review Application</th>
			</tr>";
			//add rows to table for each applicant in DB
			while($applicant = mysqli_fetch_array($applicantResult)){
				
				$fname = $applicant['fname'];
				$lname = $applicant['lname'];
				$userID = $applicant['app_id'];
				$applicationID = $applicant['applicationID'];

				$doneQuery = "SELECT * FROM review_form WHERE facultyID = '$fID' AND applicantID = '$userID'";
				$doneResult = mysqli_query($dbc, $doneQuery);

				if(mysqli_num_rows($doneResult) == 0){
					//this faculty member has not reviewed this applicant yet
					echo "<tr>
						<td>$fname $lname</td>
						<td>
							<!-- sample review button for reviewers -->
							<!-- button id will be the corresponding applicant id -->
							<button class='small btn btn-primary px-4 py-2 rounded-0' id='$userID' onclick='reviewApplicant($userID)'>Review Applicant</button>
						</td>
					
					</tr>";

					if($role == 2){
						$transcriptQuery = "SELECT * FROM transcript WHERE applicationID = '$applicationID'";
						$transcriptResult = mysqli_query($dbc, $transcriptQuery);
						if(mysqli_num_rows($transcriptResult) == 0){
							//transcript hasnt been received
							echo "<tr>
								<td>
									<button class='smmall btn btn-primary px-4 py-2 rounded-0' onclick='loadForm(\"./reviewForms/receivedTranscript.php?applicationID=$applicationID\");'>
									Received Transcript
									</button>
								</td>
								</tr>";
						}
					}
				}
			}

			echo"</table>
		</div>";
	

	?>


	<script>
		function reviewApplicant(applicantID) {
			//load the review form page
			var xhttp = new XMLHttpRequest();
			console.log("applicantID " + applicantID);	
			xhttp.onreadystatechange = function() {
				if((this.readyState == 4) && (this.status == 200)){
					document.getElementById('content').innerHTML = this.responseText;
				}
			};

			xhttp.open("GET","./review.php?applicantID="+applicantID, true);
			xhttp.setRequestHeader("Content-Type", "application-x-www-urlendcoded");
			xhttp.send();

		}
	</script>

</body>

</html>	

<!DOCTYPE HTML>

<html>

<head>
	<?php
		session_start();
		require_once('../includes/connectvars.php');
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$applicationID = $_GET['applicationID'];
		//need to get applicant ID to connect back to main page
                $idQuery = "SELECT userID FROM application_form WHERE applicationID = '$applicationID'";
                $IDresult = mysqli_query($dbc, $idQuery);
                $info = mysqli_fetch_array($IDresult);
                $applicantID = $info['userID'];

                $degreesQuery = "SELECT * FROM prior_degrees WHERE applicationID = '$applicationID'";
                $degreeResult = mysqli_query($dbc, $degreesQuery);
		require_once('../includes/reviewHeader.php');
	?>
<body>

	<h2 align="center">PriorDegrees</h2>
	<table class="table">
		<tr>
			<th>College</th>
			<th>Graduation Year</th>
			<th>Degree</th>
			<th>Major</th>
			<th>GPA</th>
		</tr>

		<?php
			while($degree = mysqli_fetch_array($degreeResult)){
				$college = $degree['institution'];
				$year = $degree['gradYear'];
				$degreeType = $degree['degreeType'];
				$major = $degree['major'];
				$gpa = $degree['gpa'];

				echo "<tr>
						<td>$college</td>
						<td>$year</td>
						<td>$degreeType</td>
						<td>$major</td>
						<td>$gpa</td>
					</tr>";
			}

		?>
		</table>

		<?php

			echo"<button class='btn btn-primary' id='prev' onclick='loadForm(\"./reviewForms/qualifications.php?applicationID=$applicationID\")'>Back</button>";
			echo"<button class='btn btn-primary' id='next' onclick='loadForm(\"./reviewForms/letters.php?applicationID=$applicationID\")'>Next</button>";

		?>


</body>
</html>

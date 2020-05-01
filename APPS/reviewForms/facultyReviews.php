<!DOCTYPE HTML>

<html>

	<?php
		session_start();

		$applicationID = $_GET['applicationID'];
		require_once('../includes/connectvars.php');

		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$IDquery = "SELECT userID FROM application_form WHERE applicationID = '$applicationID'";
		$IDresult = mysqli_query($dbc, $IDquery);

		$info = mysqli_fetch_array($IDresult);

		$applicantID = $info['userID'];

		require_once('../includes/reviewHeader.php');

		$reviewsQuery = "SELECT * FROM review_form WHERE applicantID = '$applicantID'";
		$reviewResult = mysqli_query($dbc, $reviewsQuery);


	?>

	<h2 align="center">Applicant Reviews</h2>

	<table class="table">
		<tr>
			<th>Reviewer</th>
			<th>Letter Score</th>
			<th>Recommendation</th>
		<tr/>

		<?php

		while($review = mysqli_fetch_array($reviewResult)){
			$fID = $review['facultyID'];
			$rec = $review['suggested_decision'];

			$fNameQuery = "SELECT fname, lname FROM faculty WHERE f_id = '$fID'";
			$facultyResult = mysqli_query($dbc, $fNameQuery);
			$facultyInfo = mysqli_fetch_array($facultyResult);

			$recQuery = "SELECT description FROM recommendations WHERE recID = '$rec'";
			$recResult = mysqli_query($dbc, $recQuery);
			$decision = mysqli_fetch_array($recResult);
			$recommendation = $decision['description'];

			$scoreQuery = "SELECT AVG(score) FROM letter_rating WHERE facultyID = '$fID' AND applicationID = '$applicationID'";
			$scoreResult = mysqli_query($dbc, $scoreQuery);
			$rating = mysqli_fetch_array($scoreResult);
			$score = $rating['AVG(score)'];

			$fname = $facultyInfo['fname'];
			$lname = $facultyInfo['lname'];
			$reasons = $review['reasons'];
			$comments = $review['comments'];

			echo "<tr>
					<td>$fname $lname</td>
					<td>$score</td>
					<td>$recommendation</td>
				</tr>
				<tr>
					<th>Reasons: </th>
					<td>$reasons </td>
				</tr>
				<tr>
					<th>Comments: </th>
					<td>$comments </td>
				</tr>";
		}

		echo"<button class='small btn btn-primary' onclick='loadForm(\"./reviewForms/finalDecision.php?applicationID=$applicationID\")'>Next</button>";

		?>
	</table>

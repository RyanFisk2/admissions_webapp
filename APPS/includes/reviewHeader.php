<!DOCTYPE HTML>

<html>

<head>

	<div class="nav">
	<!-- NAV menu to load each review form -->
		<?php
			//session_start();
			//$role = $_SESSION['role'];
			$nameQuery = "SELECT name FROM user WHERE userID = '$applicantID'";
                	$result = mysqli_query($dbc, $nameQuery);
                	$applicantName = mysqli_fetch_array($result);
                	$name = $applicantName['name'];

		?>
			<h1 align="center">Reviewing <?php echo $name; ?> </h1><br/>
			<h2 align="center">Applicant ID: <?php echo $applicantID; ?> </h2><br/>

		<?php

			$info = "review.php?applicantID=";
			$qual = "reviewForms/qualifications.php?applicationID=";
			$degrees = "reviewForms/priorDegrees.php?applicationID=";
			$letters = "reviewForms/letters.php?applicationID=";
			$recDecision = "reviewForms/recDecision.php?applicationID=";
			$revs = "reviewForms/facultyReviews.php?applicationID=";
			$final = "reviewForms/finalDecision.php?applicationID=";

			echo"
				<button class='btn btn-primary' id='info' onclick='loadForm(\"$info$applicantID\");'>Applicant Info</button><br/>
				<button class='btn btn-primary' id='qual' onclick='loadForm(\"$qual$applicationID\");'>Qualifications</button><br/>
                		<button class='btn btn-primary' id='degrees' onclick='loadForm(\"$degrees$applicationID\");'>Prior Degrees</button><br/>
				<button class='btn btn-primary' id='letters' onclick='loadForm(\"$letters$applicationID\");'>Recommendations</button><br/>";

			if($_SESSION['p_level'] == 4){
				echo "<button class='btn btn-primary' id='rev' onclick='loadForm(\"$recDecision$applicationID\");'>Review</button><br/>";
			}else{
				echo"<button class='btn btn-primary' id='revs' onclick='loadForm(\"$revs$applicationID\");'>Reviews</button><br/>";
				echo"<button class='btn btn-primary' id='final' onclick='loadForm(\"$final$applicationID\");'>Final Decision</button><br/>";
			}
        	?>
	</div>

</head>
</html>

<!DOCTYPE HTML>

<html>

<head>
	<?php
		session_start();
		//retrieve applicant test scores from the database
		$applicationID = $_GET['applicationID'];
		require_once('../includes/connectvars.php');

		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		//need to get applicant ID to connect back to main page
		$idQuery = "SELECT userID FROM application_form WHERE applicationID = '$applicationID'";
		$result = mysqli_query($dbc, $idQuery);
		$info = mysqli_fetch_array($result);
		$applicantID = $info['userID'];

		//get test scores
		$greQuery = "SELECT * FROM GRE_score WHERE applicationID = '$applicationID'";
		$greResult = mysqli_query($dbc, $greQuery);

		$toeflQuery = "SELECT * FROM TOEFL_score WHERE applicationID = '$applicationID'";
		$toeflResult = mysqli_query($dbc, $toeflQuery);

		$advGREQuery = "SELECT * FROM Adv_GRE WHERE applicationID = '$applicationID'";
		$advGREResult = mysqli_query($dbc, $advGREQuery);

		//require_once('../includes/reviewHeader.php');
	?>


	<body>
		<h2 align="center">Qualifications</h2></br>

		<h3 align="center">GRE Scores</h3>

		<table class="table">
			<tr>
				<th>Exam Date</th>
				<th>Total Score</th>
				<th>Verbal Score</th>
				<th>Written Score</th>
				<th>Quantitative Score</th>
			</tr>

			<?php
				while($greScores = mysqli_fetch_array($greResult)){
					$date = $greScores['examDate'];
					$total = $greScores['totalScore'];
					$verbal = $greScores['verbalScore'];
					$written = $greScores['writtenScore'];
					$quantitative = $greScores['quantScore'];

					echo "<tr>
							<td>$date</td>
							<td>$total</td>
							<td>$verbal</td>
							<td>$written</td>
							<td>$quantitative</td>
						</tr>";
				}
			?>
		</table><br/>

		<h3 align="center">TOEFL Scores</h3>
		<table class="table">
                        <tr>
                                <th>Exam Date</th>
                                <th>Total Score</th>
                        </tr>

                        <?php
                                while($toeflScores = mysqli_fetch_array($toeflResult)){
                                        $date = $toeflScores['examDate'];
                                        $total = $toeflScores['totalScore'];

                                        echo "<tr>
                                                        <td>$date</td>
                                                        <td>$total</td>
                                                </tr>";
                                }
                        ?>
		</table><br/>

		<h3 align="center">Advanced GRE Scores</h3>
                <table class="table">
                        <tr>
				<th>Exam Date</th>
				<th>Subject</th>
                                <th>Total Score</th>
                        </tr>

                        <?php
                                while($advGREScores = mysqli_fetch_array($advGREResult)){
                                        $date = $advGREScores['examDate'];
					$total = $advGREScores['totalScore'];
					$subject = $advGREScores['subject'];

                                        echo "<tr>
							<td>$date</td>
							<td>$subject</td>
							<td>$total</td>
                                                </tr>";
                                }
                        ?>
		</table><br/>

		<?php

		//create buttons to go to the previous and next page
		echo "<button class='btn btn-primary' id='prev' onclick='loadForm(\"./review.php?applicantID=$applicantID\")'>Back</button>";
		echo "<button class='btn btn-primary' id='next' onclick='loadForm(\"./reviewForms/priorDegrees.php?applicationID=$applicationID\")'>Next</button>";
		?>

	</body>

</html>

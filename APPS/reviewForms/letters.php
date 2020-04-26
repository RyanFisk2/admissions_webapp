<!DOCTYPE HTML>

<html>

	<head>

		<?php
			session_start();
			$applicationID = $_GET['applicationID'];
			require_once('../includes/connectvars.php');
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			//need to get applicant ID to connect back to main page
                	$idQuery = "SELECT userID FROM application_form WHERE applicationID = '$applicationID'";
                	$result = mysqli_query($dbc, $idQuery);
                	$info = mysqli_fetch_array($result);
                	$applicantID = $info['userID'];

			$letterQuery = "SELECT * FROM rec_letter WHERE applicationID = '$applicationID'";
			$letterResult = mysqli_query($dbc, $letterQuery);
			require_once('../includes/reviewHeader.php');

			$fID = $_SESSION['id'];


		?>
	</head>
	<body>


		<table class="table">
			<tr>
				<th>Written By</th>
				<th>Title</th>
				<th>Company</th>
				<th>Letter</th>
			</tr>
			<?php
				$index = 0;	
				while($letter = mysqli_fetch_array($letterResult)){
					$author = $letter['writerName'];
					$title = $letter['writerTitle'];
					$company = $letter['writerEmployer'];
					$letterID = $letter['letterID'];
					
					$displayQuery = "SELECT * FROM letter_rating WHERE facultyID = '$fID' AND letterID = '$letterID'";
					$displayResults = mysqli_query($dbc, $displayQuery);
					//only display letters the reviewer hasnt reviewed yet
					if(mysqli_num_rows($displayResults) == 0){
						echo "<tr>
							<td>$author</td>
							<td>$title</td>
							<td>$company</td>
							<td> <button class='btn btn-primary' onclick='viewLetter($letterID)'>View Letter</button></td>
						</tr>
						</table>";
						
						echo "<form method='post' id='$letterID'>
						
							<label for='generic'>Generic</label>
							<input type='text' name='generic' id='generic$letterID' placeholder='<Y/N>'/>
							<label for='credible'>Credible</label>
							<input type='text' name='credible' id='credbile$letterID' placeholder='<Y/N>'/>
							<label for='score'>Rating</label>
							<input type='text' name='score' id='score$letterID' placeholder='1 - 5'/>
							<input type='hidden' name='id' id='$letterID' value='$letterID'/></br>
							<button class='btn btn-primary id='submit$letterID' onclick='submitForm(\"./reviewForms/submitLetterRating.php?applicationID=$applicationID\", $letterID);'>Submit</button>
						</form>";
					}
						
				}
			?>
	</body>

	<?php
		echo"<button class='btn btn-primary' onclick='loadForm(\"./reviewForms/priorDegrees.php?applicationID=$applicationID\")'>Back</button>";
		if($_SESSION['p_level'] == 4) echo"<button class='btn btn-primary' onclick='loadForm(\"./reviewForms/recDecision.php?applicationtID=$applicationID\")'>Next</button>";
		else echo "<button class='btn btn-primary' onclick='loadForm(\"./reviewForms/facultyReviews.php?applicationID=$applicationID\")'Next</button>";
	?>
</html>

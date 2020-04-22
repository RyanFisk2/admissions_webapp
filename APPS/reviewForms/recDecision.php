<!DOCTYPE HTML>

<html>
<head>

	<?php
		session_start();

		require_once('../includes/connectvars.php');
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		$applicationID = $_GET['applicationID'];
		$query = "SELECT userID FROM application_form WHERE applicationID = '$applicationID'";

		$result = mysqli_query($dbc, $query);
		$info = mysqli_fetch_array($result);

		$applicantID = $info['userID'];

		require_once('../includes/reviewHeader.php');

	?>

</head>

<body>

	<h2 align="center">Recommended Decision</h2>
<?php echo"<form method='post' action='./reviewForms/submitRecommendation.php?applicantID=$applicantID'>"; ?>
			
		<label for="reject">Reject</label>
		<input type="radio" name="rating" id="reject" value="0">
			
			
		<label for="bdAdmit">Boderline Admit</label>
		<input type="radio" name="rating" id="bdAdm" value="1">

		<label for="admNoAid">Admit Without Aid</label>
		<input type="radio" name="rating" id="admNoAid" value="2">

		<label for="admWAid">Admit With Aid</label>
		<input type="radio" name="rating" id="admWAid" value="3"><br/>

		<label for="reasons">Reasons</label>		
		<input type="text" name="reasons" id="reasons" required><br/>

		<label for="comments">Comments</label>
		<input type="text" name="comments" id="comments" placeholder="Comments..." required><br/>

		<label for="courses">Deficient Courses</label>
		<input type="text" name="courses" id="courses" placeholder="Courses..." required><br/>

		<button name="submit" class="btn btn-primary">Submit</button>
	</form>

</body>

</html>

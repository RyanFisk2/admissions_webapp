<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<!-- link the bootstrap stylesheets -->
	<link rel="Stylesheet" href="css/review.css">
	<link rel="Stylesheet" href="templCss/landing-page-in.css">

	<title>Applicant Review</title>

	<?php
		session_start();
		require_once('./includes/connectvars.php');
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if(!($dbc)){
			die('error connecting to DB');
			header('Refresh:0');
		}

		$applicantID = $_GET['applicantID'];

		//get applicant info from the application form
		$applicationQuery = "SELECT applicationID, term, degree, interest
					FROM application_form
					WHERE userID = '$applicantID'";

		$result = mysqli_query($dbc, $applicationQuery);
		$applicantInfo = mysqli_fetch_array($result);

		$applicationID = $applicantInfo['applicationID'];
		$term = $applicantInfo['term'];
		$degree = $applicantInfo['degree'];
		$interest = $applicantInfo['interest'];

		$expQuery = "SELECT * FROM experience WHERE applicationID = '$applicationID'";
		$expResult = mysqli_query($dbc, $expQuery);

		//link review form nav
		require_once('./includes/reviewHeader.php');	

	?>

</head>
<body>


  	<h2 align="center">Applicant Information</h2> 
	<table>
		 <tr>
			<th>Semester and Year of Application: </th>
			<td><?php if(strcmp($term, "2020-08") == 0){
					echo "Fall 2020"; 
				}else{
					echo"Spring 2021";
				}?></td>
		</tr>
		<tr>
			<th>Degree: </th>
			<td><?php echo $degree; ?></td>
		</tr>
		<tr>
			<th>Areas of Interest: </th>
			<td><?php echo $interest; ?> </td>
		</tr>
	</table><br/>


	<h2 align="center">Experience</h2>
	<table>
		<tr>
			<th>Employer</th>
			<th>Start Date</th>
			<th>End Date</th>
			<th>Position</th>
		</tr>
		<tr>
			<?php
				while($applicantExp = mysqli_fetch_array($expResult)){
					$employer = $applicantExp['employer'];
					$startDate = $applicantExp['startDate'];
					$endDate = $applicantExp['endDate'];
					$position = $applicantExp['position'];
					$description = $applicantExp['description'];

					echo "<td> $employer</td>
						<td>$startDate</td>
						<td>$endDate</td>
						<td>$position</td>
					</tr>
					<tr>
						<th>Description</th>
						<td>$description</td>
					</tr>";
				}
			?>
	</table>

	
	<?php
		echo"<button class='btn btn-primary' id='next' onclick='loadForm(\"./reviewForms/qualifications.php?applicationID=$applicationID\")'>Next</button>";
	?>		

	</div>

</body>

</html>




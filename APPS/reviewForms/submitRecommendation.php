<!DOCTYPE HTML>

<html>

	<?php
		session_start();

		$fID = $_SESSION['userID'];

		require_once('../includes/connectvars.php');
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		$applicantID = $_GET['applicantID'];

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$recDecision = $_POST['rating'];
			$reasons = $_POST['reasons'];
			if(ctype_alpha($reaons)){
				$comments = $_POST['comments'];
				if(ctype_alpha($comments)){
					$courses = $_POST['courses'];

					$insertCourses = "INSERT INTO deficient_courses VALUES ('$applicantID', '$courses')";

					if($result = mysqli_query($dbc, $insertCourses)){

							$insertReview = "INSERT INTO 
								review_form(facultyID, applicantID, suggested_decision, reasons, comments)
								VALUES ('$fID', '$applicantID', '$recDecision', '$reasons', '$comments')";	

							if($inserted = mysqli_query($dbc, $insertReview)){
								echo "recommendation entered, thank you!";
								header("Location: ../index.php");
							}else{
								echo"error inserted recommendation";
								echo $fID . " ";
								echo $applicantID . " ";
								echo $recDecision . " ";
								echo $reasons . " ";
								echo $comments . " ";
							}
					}else{
						echo"error inserting courses";
						echo $applicantID;
						echo $courses;
					}
				}else{
					echo"Invalid input, comments cannot contain numbers or special characters";
				}
			}else{
				echo"Invalid input, reasons cannot contain numbers or special characters";
			}
		}

		

	?>

<html>

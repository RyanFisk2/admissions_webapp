<!DOCTYPE HTML>

<?php
	//echo"in submit letter ";
	session_start();

	$fID = $_SESSION['user_id'];
	require_once('../includes/connectvars.php');
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$applicationID = $_GET['applicationID'];

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//echo "in request method = geti\n";
		//print_r($_POST);
		if(isset($_POST)){
			//echo"post was submitted";
			$generic = $_POST['generic'];
			if((strcmp($generic, "Y") == 0) || (strcmp($generic, "N") == 0)){
				//echo "generic = " . $generic;
				$credible = $_POST['credible'];
				if((strcmp($credible, "Y") == 0) || (strcmp($credible, "N") == 0)){
				//echo "credible = " . $credible;
					$score = $_POST['score'];
					//echo "score = " . $score;
					if((ctype_digit($score)) && ($score <= 5) && ($score >= 1)){

						$letterNum = $_POST['id'];
						//echo "letter# = " . $letterNum;

						$insertQuery = "INSERT INTO letter_rating VALUES
			       					($fID, $applicationID, $letterNum, $score)";

						if($result = mysqli_query($dbc, $insertQuery)){
							echo "trying to redirect back to letters page";
						}else{
							echo"Error submitting review, please contact you system administrator";
						}
					}else{
						echo"<script> alert('Invalid input: score must be a digit 1 - 5'); </script>";
					}
				}else{
					echo"<script> alert('Invalid input: credible should be Y or N'); </script>";
				}
			

			}else{
				echo "<script> alert('Invalid input: generic should be Y or N'); </script>";
			}	

		}
	}

?>

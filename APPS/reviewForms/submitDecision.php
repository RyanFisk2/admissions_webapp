<!DOCTYPE HTML>

<html>

        <?php
                session_start();

                $fID = $_SESSION['user_id'];

                require_once('../includes/connectvars.php');
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

                $applicantID = $_GET['applicantID'];

                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $final = $_POST['rating'];
                        $advisor = $_POST['advisor'];

                                $insertDecision = "INSERT INTO 
                                                        final_decision VALUES ($fID, $applicantID, $final)";

                                if($inserted = mysqli_query($dbc, $insertDecision)){
					echo "recommendation entered, thank you!";
					//delete application from DB once decision is made
					$updateQuery = "UPDATE application_form SET decision = $final WHERE userID = '$applicantID'";

					if(($final == 2) || ($final == 3)){
						//applicant has been admitted, move them to students table TODO:make the applicant accept first
						$updateStatusQuery = "UPDATE users SET p_level = '5' WHERE id = '$applicantID'";
						$statusResult = mysqli_query($dbc, $updateStatusQuery);

						//get student info from application form
						$applicantQuery = "SELECT fname, lname FROM applicant WHERE app_id = '$applicantID'";
						$applicantResult = mysqli_query($dbc, $applicantQuery);
						$applicant = mysqli_fetch_array($applicantResult);
						$fname = $applicant['fname'];
						$lname = $applicant['lname'];

						//split advisor name into first and last name for query
						$advisorName = explode(" ", $advisor);
						$aFname = $adivsorName[0];
						$aLname = $adivorName[1];

						//get advisor info from faculty table
						$advisorQuery = "SELECT f_id FROM faculty WHERE fname = '$aFname' AND lname = '$aLname'";
						$advisorResult = mysqli_query($dbc, $query);
						$advID = $advisorResult['f_id'];

						//insert info into students table
						$studentEntry = "INSERT INTO student (u_id, fname, lname, advisor) VALUES ($applicantID, $fname, $lname, $advID)";
						if($studentResult = mysqli_query($dbc, $studentEntry)){
							header("Location: ../index.php");
						}
					};

					if($updateResult = mysqli_query($dbc, $updateQuery)){
						$msg = "Your application has been reviewed! Login to view your status!";
                                        	header("Location: ../index.php");
					}else{
						echo"error updating table";
					}
                                }else{
                                        echo"error inserted decision";
                                        echo $fID . " ";
                                        echo $applicantID . " ";
                                        echo $final. " ";
                                }

                }

        ?>

<html>


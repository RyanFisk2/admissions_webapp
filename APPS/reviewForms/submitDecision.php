<!DOCTYPE HTML>

<html>

        <?php
                session_start();

                $fID = $_SESSION['id'];

                require_once('../includes/connectvars.php');
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

                $applicantID = $_GET['applicantID'];

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			print_r($_POST);
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
						$applicantQuery = "SELECT fname, lname, email FROM applicant WHERE app_id = '$applicantID'";
						$applicantResult = mysqli_query($dbc, $applicantQuery);
						$applicant = mysqli_fetch_array($applicantResult);
						$fname = $applicant['fname'];
						$lname = $applicant['lname'];
						$email = $applicant['email'];
						echo "email" . $email;

						//split advisor name into first and last name for query
						$advisorName = explode(" ", $advisor);
						$aFname = $advisorName[0];
						$aLname = $advisorName[1];

						//get advisor info from faculty table
						$advisorQuery = "SELECT f_id FROM faculty WHERE fname = '$aFname' AND lname = '$aLname'";
						$advisorResult = mysqli_query($dbc, $advisorQuery);
						$adv = mysqli_fetch_array($advisorResult);
						$advID = $adv['f_id'];
						
						echo "advisor: " . $advID;

						//get interest from application form
						$interestQuery = "SELECT interest, address1 FROM application_form WHERE userID = $applicantID";
						$interestResult = mysqli_query($dbc, $interestQuery);
						$interest = mysqli_fetch_array($interestResult);
						$major = $interest['interest'];
						$address = $interest['address1'];

						//insert info into students table
						$studentEntry = "INSERT INTO student (u_id, fname, lname, addr, email, major, advisor) VALUES ($applicantID, '$fname', '$lname', '$address', '$email', '$major', $advID)";
						if($entryResult = mysqli_query($dbc, $studentEntry)){
							echo "sucess";
							//header("Location: ../index.php");
						}else { echo "error inserting into student table"; }
					}

					if($updateResult = mysqli_query($dbc, $updateQuery)){
						$msg = "Your application has been reviewed! Login to view your status!";
                                        	//header("Location: ../index.php");
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


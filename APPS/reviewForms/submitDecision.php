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


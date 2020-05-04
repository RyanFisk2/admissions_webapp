<?php

require_once('header.php');

require_once('connectvars.php');

$corecourse = 1;

$totalcredits = 0;


$csCredits = 0;

?>
<br><br>
<div class="site-section">
    <div class="container">
		<div class="row mb-4 justify-content-center text-center">
          	<div class="col-lg-4 mb-4">
            	<h2 class="section-title-underline mb-4">
              		<span>Approve Form 1's</span>
            	</h2>
          	</div>
		</div>
		<div class="row mb-4 justify-content-center text-center">
		<?php

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mess= "Form One not Approved";
for($i = 1; $i < 13; $i++){
	$id =  $_POST['course'.$i];
	$dept= $_POST['cdept'.$i];

	if($id == 6212 && strcmp($dept, 'CSCI') == 0){
		$corecourse *= 2; 
	}
	if(strcmp($dept, 'CSCI') == 0 && $id == 6221){
		$corecourse *=3;
	}
	if(strcmp($dept, 'CSCI') == 0 && $id == 6461){
		$corecourse *=5;
	}
	$query = "SELECT credits FROM catalog WHERE department='$dept' AND c_no='$id'";
	$data = mysqli_query($dbc, $query);
	$row = mysqli_fetch_array($data);
	$totalcredits += $row[0];
	
	$credits = $row[0];
	if(strcmp($dept, 'CSCI') == 0){
		$csCredits += $credits;	
	}
}

$uid = $_SESSION['user_id'];
$query = "select degree from student WHERE u_id = '$uid'";
$data = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($data);

//master check
if(strcmp($row[0], 'MS') == 0 && $corecourse == 30 && $totalcredits >=30 && $csCredits >= 25){
	$query = "UPDATE student_transcript set inform1 = 'false' WHERE t_id = '$uid'";
       	$data = mysqli_query($dbc, $query);

	$query = "delete from form1 WHERE f1_id = '$uid'";
       	$data = mysqli_query($dbc, $query);

	for($i = 1; $i < 13; $i++){
        	$id =  $_POST['course'.$i];
		$dept= $_POST['cdept'.$i];
		$query = "UPDATE student_transcript set inform1 = 'true' WHERE dept='$dept' AND cno='$id' AND t_id = '$uid'";
        	$data = mysqli_query($dbc, $query);
		$query = "INSERT INTO form1 VALUES ($uid, '$dept', $id)";
                $data = mysqli_query($dbc, $query);

        }
         $query = "update student set form1status = 1 WHERE u_id = $uid";
         $data = mysqli_query($dbc, $query);
	
	$mess = "Form1 Submitted Successfully";
}

//phdcheck
if(strcmp($row[0], 'PhD') == 0 && $corecourse == 30 && $totalcredits >= 36 && $csCredits >= 35){

	$query = "UPDATE student_transcript set inform1 = 'false' WHERE t_id = '$uid'";
       	$data = mysqli_query($dbc, $query);
	
	$query = "delete from form1 WHERE f1_id = '$uid'";
       	$data = mysqli_query($dbc, $query);
	
	$query = "delete from form1 WHERE f1_id = '$uid'";
       	$data = mysqli_query($dbc, $query);	
	for($i = 1; $i < 13; $i++){
                $id =  $_POST['course'.$i];
                $dept= $_POST['cdept'.$i];
                $query = "UPDATE student_transcript set inform1 = 'true' WHERE dept='$dept' AND cno='$id' AND t_id = '$uid'";
                $data = mysqli_query($dbc, $query);
		$query = "INSERT INTO form1 VALUES ($uid, '$dept', $id)";
                $data = mysqli_query($dbc, $query);

        }
         $query = "update student set form1status = 1 WHERE u_id = $uid";
         $data = mysqli_query($dbc, $query);
	$mess = "Form1 Submitted Successfully";

}



echo $mess;
?>

<?php

require_once('header.php');

require_once('connectvars.php');
// Show the navigation menu

//3*2*5=30 first three prime numbers excluding 1
//since the maxium credits 2 non csci course can take up is 5 we can check for 25 credits in csci
$corecourse = 1;
//$duplicates = 0;
//$idarray = array();
//$deptarray = array();
$totalcredits = 0;

//$gradesbelowB=0;
//$gradepoints = 0;
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
	$query = "SELECT credits FROM courses WHERE dept='$dept' AND cno='$id'";
	$data = mysqli_query($dbc, $query);
	$row = mysqli_fetch_array($data);
	$totalcredits += $row[0];
//	echo $row[0] . "row ";
	
	
	$credits = $row[0];
	if(strcmp($dept, 'CSCI') == 0){
		$csCredits += $credits;	
	}
/**	
	$uid = $_SESSION['user_id'];
	$query = "SELECT grade FROM transcripts WHERE dept='$dept' AND cno='$id' AND uid = '$uid'";
        $data = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($data);
	$grade = 0;
	if(strcmp($row[0], 'A') == 0){
		$grade = 4;
	}
	if(strcmp($row[0], 'A-') == 0){
                $grade = 3.70;
        }
	if(strcmp($row[0], 'B+') == 0){
                $grade = 3.33;
	}
	if(strcmp($row[0], 'B') == 0){
                $grade = 3.00;
	}
	if(strcmp($row[0], 'B-') == 0){
		$grade = 2.70;
		$gradesbelowB++;
	}
	if(strcmp($row[0], 'C+') == 0){
                $grade = 2.30;
                $gradesbelowB++;
	}
	if(strcmp($row[0], 'C') == 0){
                $grade = 2.00;
                $gradesbelowB++;
	}
	if(strcmp($row[0], 'C-') == 0){
                $grade = 1.70;
                $gradesbelowB++;
	}
	if(strcmp($row[0], 'D+') == 0){
                $grade = 1.30;
                $gradesbelowB++;
	}
	if(strcmp($row[0], 'D') == 0){
                $grade = 1.00;
                $gradesbelowB++;
	}
	if(strcmp($row[0], 'D-') == 0){
                $grade = 0.70;
                $gradesbelowB++;
        }
	$gradepoints += $credits * $grade;

**/
}

//$gpa = $gradepoints/$totalcredits;

$uid = $_SESSION['user_id'];
$query = "select degree from student WHERE uid = '$uid'";
$data = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($data);
//echo 'total'. $totalcredits;
//master check
if(strcmp($row[0], 'MS') == 0 && $corecourse == 30 && $totalcredits >=30 && $csCredits >= 25){
	$query = "UPDATE transcript set inform1 = 'false' WHERE uid = '$uid'";
       	$data = mysqli_query($dbc, $query);

	$query = "delete from form1 WHERE uid = '$uid'";
       	$data = mysqli_query($dbc, $query);

	for($i = 1; $i < 13; $i++){
        	$id =  $_POST['course'.$i];
		$dept= $_POST['cdept'.$i];
		$query = "UPDATE transcript set inform1 = 'true' WHERE dept='$dept' AND cno='$id' AND uid = '$uid'";
        	$data = mysqli_query($dbc, $query);
		$query = "INSERT INTO form1 VALUES ($uid, '$dept', $id)";
                $data = mysqli_query($dbc, $query);

        }
         $query = "update student set form1status = 1 WHERE uid = $uid";
         $data = mysqli_query($dbc, $query);
	
	$mess = "Form1 Submitted Successfully";
}

//phdcheck
if(strcmp($row[0], 'PhD') == 0 && $corecourse == 1 && $totalcredits >= 36 && $csCredits >= 35){

	$query = "UPDATE transcript set inform1 = 'false' WHERE uid = '$uid'";
       	$data = mysqli_query($dbc, $query);
	
	$query = "delete from form1 WHERE uid = '$uid'";
       	$data = mysqli_query($dbc, $query);
	
	$query = "delete from form1 WHERE uid = '$uid'";
       	$data = mysqli_query($dbc, $query);	
	for($i = 1; $i < 13; $i++){
                $id =  $_POST['course'.$i];
                $dept= $_POST['cdept'.$i];
                $query = "UPDATE transcript set inform1 = 'true' WHERE dept='$dept' AND cno='$id' AND uid = '$uid'";
                $data = mysqli_query($dbc, $query);
		$query = "INSERT INTO form1 VALUES ($uid, '$dept', $id)";
                $data = mysqli_query($dbc, $query);

        }
         $query = "update student set form1status = 1 WHERE uid = $uid";
         $data = mysqli_query($dbc, $query);
	$mess = "Form1 Submitted Successfully";

}



echo $mess;
?>

<!DOCTYPE html>

<?php
	// Insert the page header
	//$page_title = 'Welcome!';
    require_once('header.php');

	require_once('connectvars.php');
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	// Show the navigation menu
$msg = 'is eligible to graduate.'; 
$uid = $_GET['uid'];
?>
<body>
<br><br>
<div class="site-section">
    <div class="container">
		<div class="row mb-4 justify-content-center text-center">
          	<div class="col-lg-4 mb-4">
            	<h2 class="section-title-underline mb-4">
              		<span>Graduation Audit</span>
            	</h2>
          	</div>
        </div>
        <div class="row mb-4 justify-content-center text-center">
        <?php
$formcourses = mysqli_query($dbc, "select count(a.cno) from form1 a, student_transcript b where b.t_id = '$uid' and a.f1_id = b.t_id and (a.dept, a.cno) not in (select dept, cno from student_transcript where t_id = '$uid');");
$fc = mysqli_fetch_array($formcourses);
if (!empty($fc)) {
    $fcourses = $fc[0];
    if ($fcourses > 0) {
        $msg = 'is not eligible to graduate.';
    }
}

$form1info = mysqli_query($dbc, "select form1status from student where u_id = '$uid';");
$f1 = mysqli_fetch_array($form1info);
if (!empty($f1)) {
    $form1 = $f1[0];
    if ($form1 != 2) {
        $msg = 'is not eligible to graduate.';
    }
}

$dtype = mysqli_query($dbc, "select degree from student where u_id = '$uid';");
$dt = mysqli_fetch_array($dtype);
    if (!empty($dt)) {
        $degree = $dt[0];
    }
    else {
        echo 'Error: could not determine degree type<br>';
    }



if ($degree == 'MS') {
    $gpainfo = mysqli_query($dbc, "select gpa from student where u_id = '$uid';");
    $g = mysqli_fetch_array($gpainfo);
    if (!empty($g)) {
        $gpa = $g[0];
        if ($gpa < 3.0) {
            $msg = 'is not eligible to graduate.';
        }
    }
    else {
        echo 'Error: could not determine gpa<br>';
    }

    $transcriptinfo = mysqli_query($dbc, "select dept, cno from student_transcript where t_id = '$uid' and dept = 'CSCI' and cno = 6212;");
    $t = mysqli_fetch_array($transcriptinfo);
    if (empty($t)) {
        $msg = 'is not eligible to graduate.';
    }
    $transcriptinfo = mysqli_query($dbc, "select dept, cno from student_transcript where t_id = '$uid' and dept = 'CSCI' and cno = 6221;");
    $t = mysqli_fetch_array($transcriptinfo);
    if (empty($t)) {
        $msg = 'is not eligible to graduate.';
    }
    $transcriptinfo = mysqli_query($dbc, "select dept, cno from student_transcript where t_id = '$uid' and dept = 'CSCI' and cno = 6461;");
    $t = mysqli_fetch_array($transcriptinfo);
    if (empty($t)) {
        $msg = 'is not eligible to graduate.';
    }

    $credithours = mysqli_query($dbc, "select sum(credits) from student_transcript a, catalog b where a.t_id = '$uid' and a.dept = b.department and a.cno = b.c_no;");
	$c = mysqli_fetch_array($credithours);
    if (!empty($c)) {
        $credits = $c[0];
        if ($credits < 30) {
            $msg = 'is not eligible to graduate.';
        }
    }	
    else {
        echo 'Error: could not determine gpa<br>';
    }

    $gradesbelowb = mysqli_query($dbc, "select count(grade) from student_transcript where t_id = '$uid' and grade not in (select grade from student_transcript where t_id = '$uid' and (grade = 'A' or grade = 'B' or grade = 'IP'));");
	$grade = mysqli_fetch_array($gradesbelowb);
    if (!empty($grade)) {
        $grades = $grade[0];
        if ($grades > 2) {
            $msg = 'is not eligible to graduate.';
        }
    }	
    else {
        echo 'Error: could not determine grades<br>';
    }
}
else {
    $gpainfo = mysqli_query($dbc, "select gpa from student where u_id = '$uid';");
    $g = mysqli_fetch_array($gpainfo);
    if (!empty($g)) {
        $gpa = $g[0];
        if ($gpa < 3.5) {
            $msg = 'is not eligible to graduate.';
        }
    }
    else {
        echo 'Error: could not determine gpa<br>';
    }

    $credithours = mysqli_query($dbc, "select sum(credits) from student_transcript a, catalog b where a.t_id = '$uid' and a.dept = b.department and a.cno = b.c_no;");
	$c = mysqli_fetch_array($credithours);
    if (!empty($c)) {
        $credits = $c[0];
        if ($credits < 36) {
            $msg = 'is not eligible to graduate.';
        }
    }	
    else {
        echo 'Error: could not determine gpa<br>';
    }

    $corecredithours = mysqli_query($dbc, "select sum(credits) from student_transcript a, catalog b where a.t_id = '$uid' and b.department = 'CSCI' and a.dept = b.department and a.cno = b.c_no;");
	$cc = mysqli_fetch_array($corecredithours);
    if (!empty($cc)) {
        $corecredits = $cc[0];
        if ($corecredits < 30) {
            $msg = 'is not eligible to graduate.';
        }
    }	
    else {
        echo 'Error: could not determine gpa<br>';
    }

    $gradesbelowb = mysqli_query($dbc, "select count(grade) from student_transcript where t_id = '$uid' and grade not in (select grade from student_transcript where t_id = '$uid' and (grade = 'A' or grade = 'B' or grade = 'IP'));");
	$grade = mysqli_fetch_array($gradesbelowb);
    if (!empty($grade)) {
        $grades = $grade[0];
        if ($grades > 1) {
            $msg = 'is not eligible to graduate.';
        }
    }	
    else {
        echo 'Error: could not determine grades<br>';
    }
}

$studentname = mysqli_query($dbc, "select fname, lname from student where u_id = '$uid';");
$sn = mysqli_fetch_array($studentname);
    if (!empty($sn)) {
        $fname = $sn[0];
	$lname = $sn[1];
    }
    else {
        echo 'Error: could not determine student name<br>';
    }


//echo '<br>';
echo "$fname $lname $msg";
?>
</div>
<br>
<div class="row mb-4 justify-content-center text-center">

<form><input type="button" class="button" value="Return to previous page" onClick="javascript:history.go(-1)"></form>

</div>
</div>
</div>
</body>
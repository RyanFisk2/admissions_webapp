<!DOCTYPE html>
<head>

<?php
    require_once('header.php');
?>
</head>
<br>
<body>
<?php

	require_once('connectvars.php');
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    // Show the navigation menu
    if (isset($_SESSION['user_id'])) {

?>
<div class="site-section">
    <div class="container pt-3">
        <h1 class="text-primary">Graduation Application</h1>
        <br>
    <?php
        $uid = $_SESSION['user_id'];


// the following code is the grad audit
$check = true;
// gets number of courses in form 1 that do not exist in transcript. Sudent cannot graduate if any exist.
$formcourses = mysqli_query($dbc, "select count(a.cno) from form1 a, student_transcript b where b.t_id = '$uid' and a.f1_id = b.t_id and (a.dept, a.cno) not in (select dept, cno from student_transcript where t_id = '$uid');");
$fc = mysqli_fetch_array($formcourses);
if (!empty($fc)) {
    $fcourses = $fc[0];
    if ($fcourses > 0) {
        $check = false;
	    echo 'ERROR: Transcript does not contain all Form One classes <br>';
    }
}
// checks if form 1 was approved
$form1info = mysqli_query($dbc, "select form1status from student where u_id = '$uid';");
$f1 = mysqli_fetch_array($form1info);
if (!empty($f1)) {
    $form1 = $f1[0];
    if ($form1 != 2) {
        $check = false;
	    echo 'ERROR: Form 1 not approved<br>';
    }
}
// finds the degree of the student
$dtype = mysqli_query($dbc, "select degree from student where u_id = '$uid';");
$dt = mysqli_fetch_array($dtype);
    if (!empty($dt)) {
        $degree = $dt[0];
    }
    else {
        echo 'ERROR: could not determine degree type<br>';
    }



if ($degree == 'MS') {
    // makes sure gpa isn't below a 3.0
    $gpainfo = mysqli_query($dbc, "select gpa from student where u_id = '$uid';");
    $g = mysqli_fetch_array($gpainfo);
    if (!empty($g)) {
        $gpa = $g[0];
        if ($gpa < 3.0) {
		$check = false;
		echo 'ERROR: GPA below 3.0<br>';
	}
    }
    else {
        echo 'ERROR: Could not determine GPA<br>';
    }
    // makes sure transcript meets course requirements
    $transcriptinfo = mysqli_query($dbc, "select dept, cno from student_transcript where t_id = '$uid' and dept = 'CSCI' and cno = 6212;");
    $t = mysqli_fetch_array($transcriptinfo);
    if (empty($t)) {
        $check = false;
	    echo 'ERROR: Course req check failed<br>';
    }
    $transcriptinfo = mysqli_query($dbc, "select dept, cno from student_transcript where t_id = '$uid' and dept = 'CSCI' and cno = 6221;");
    $t = mysqli_fetch_array($transcriptinfo);
    if (empty($t)) {
        $check = false;
	    echo 'ERROR: Course req check failed<br>';
    }
    $transcriptinfo = mysqli_query($dbc, "select dept, cno from student_transcript where t_id = '$uid' and dept = 'CSCI' and cno = 6461;");
    $t = mysqli_fetch_array($transcriptinfo);
    if (empty($t)) {
        $check = false;
	    echo 'ERROR: Course req check failed<br>';
    }
    // makes sure student has taken at least 30 credit hours
    $credithours = mysqli_query($dbc, "select sum(credits) from student_transcript a, catalog b where a.t_id = '$uid' and a.dept = b.department and a.cno = b.c_no;");
	$c = mysqli_fetch_array($credithours);
    if (!empty($c)) {
        $credits = $c[0];
        if ($credits < 30) {
            $check = false;
		echo 'ERROR: Credit check failed<br>';
        }
    }	
    else {
        echo 'ERROR: could not determine credit hours<br>';
    }
    // makes sure student doesn't have more than 2 grades below a B
    $gradesbelowb = mysqli_query($dbc, "select count(grade) from student_transcript where t_id = '$uid' and grade not in (select grade from student_transcript where t_id = '$uid' and (grade = 'A' or grade = 'B' or grade = 'A-' or grade = 'B+' or grade = 'B-' or grade = 'IP'));");
	$grade = mysqli_fetch_array($gradesbelowb);
    if (!empty($grade)) {
        $grades = $grade[0];
        if ($grades > 2) {
            $check = false;
		echo 'ERROR: Min grades req check failed<br>';
        }
    }	
    else {
        echo 'ERROR: could not determine grades<br>';
    }
    if ($check == true){
        // sets gradapp attribute to 2, which signifies that the student needs to be approved to graduate by grad secretary
        $apply = "update student set gradapp = 2 where u_id = '$uid';";
        mysqli_query($dbc, $apply);   
        echo '<br>Application sent in.';
    }
    else {
	echo '<br>Application Failed. Please check requirements.';
    }
}
// if student is a phd student
else {
    // makes sure gpa isn't below a 3.5
    $gpainfo = mysqli_query($dbc, "select gpa from student where u_id = '$uid';");
    $g = mysqli_fetch_array($gpainfo);
    if (!empty($g)) {
        $gpa = $g[0];
        if ($gpa < 3.5) {
            $check = false;
        }
    }
    else {
        echo 'ERROR: could not determine GPA<br>';
    }
    // makes sure student has taken at least 36 credit hours
    $credithours = mysqli_query($dbc, "select sum(credits) from student_transcript a, catalog b where a.t_id = '$uid' and a.dept = b.department and a.cno = b.c_no;");
	$c = mysqli_fetch_array($credithours);
    if (!empty($c)) {
        $credits = $c[0];
        if ($credits < 36) {
            $check = false;
            echo 'ERROR: Not enough credit hours GPA<br>';
        }
    }	
    else {
        echo 'ERROR: could not determine GPA<br>';
    }
    // makes sure student has taken at least 30 credit hours in CSCI courses
    $corecredithours = mysqli_query($dbc, "select sum(credits) from student_transcript a, catalog b where a.t_id = '$uid' and b.department = 'CSCI' and a.dept = b.department and a.cno = b.c_no;");
	$cc = mysqli_fetch_array($corecredithours);
    if (!empty($cc)) {
        $corecredits = $cc[0];
        if ($corecredits < 30) {
            $check = false;
            echo 'ERROR: Not enough CS credit hours<br>';
        }
    }	
    else {
        echo 'ERROR: could not determine GPA<br>';
    }
    // makes sure student doesn't have more than 1 grade below a B
    $gradesbelowb = mysqli_query($dbc, "select count(grade) from student_transcript where T_id = '$uid' and grade not in (select grade from student_transcript where t_id = '$uid' and (grade = 'A' or grade = 'A-' or grade = 'B+' or grade = 'B-' or grade = 'B' or grade = 'IP'));");
	$grade = $grade = mysqli_fetch_array($gradesbelowb);;
    if (!empty($grade)) {
        $grades = $grade[0];
        if ($grades > 1) {
            $check = false;
            echo 'ERROR: More than one grade below C<br>';
        }
    }	
    else {
        echo 'ERROR: Grade determintation failed<br>';
    }
    if ($check == true) {
        // sets the gradapp attribute to 1, which signifies that the student has applied but still needs their thesis approved
        $checkthesis = mysqli_query($dbc, "SELECT gradapp FROM student WHERE U_id = $uid");
        $result = mysqli_fetch_array($checkthesis);
        if ($result[0] != 2){
            $apply = "update student set gradapp = 1 where u_id = '$uid';";
            mysqli_query($dbc, $apply);
            echo '<br>Application sent in. Awaiting approval of thesis.';
        }
        else{
            echo '<br> Applicaiton sent in. Thesis approved. Awaiting final graduation approval.';
        }
    }
    else {
	echo '<br>Application Failed. Please check requirements.';
    }
}
echo '<br>';
echo '<br>';
?>
    <form><input type="button" class="button" value="Return to previous page" onClick="javascript:history.go(-1)"></form>

    </div>
</div>
<?php
}
?>
</body>
</html>
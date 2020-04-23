<?php
	// Start the session
    session_start();
    require_once('connectvars.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
$uid = $_GET['uid'];

//retrieve the variables to plug into alumni
$info = mysqli_query($dbc, "select * from student where u_id = '$uid';");
    $grad = mysqli_fetch_array($info);
    if (!empty($grad)) {
        $degree = $grad['degree'];
        $gpa = $grad['gpa'];
    }
    else {
        echo 'Error: Unable to retrieve student info';
    }
$gradyear = date("Y");

    //$year = mysqli_query($dbc, "select year from semester where semesterid = ;");

// delete grad from student

mysqli_query($dbc, "DELETE FROM form1 WHERE f1_id=$uid");
mysqli_query($dbc, "DELETE FROM student WHERE u_id=$uid");

mysqli_query($dbc, "UPDATE users SET p_level=6 WHERE id=$uid");

$fname = $grad['fname'];
$lname = $grad['lname'];
$email = $grad['email'];
$address = $grad['addr'];

// add grad to alumni
mysqli_query($dbc, "insert into alumni (a_id, fname, lname, degree, gpa, email, gradyear, addr) values ($uid, '$fname', '$lname', '$degree', '$gpa', '$email', '$gradyear', '$address');");

$home_url = 'http://' . $_SERVER["HTTP_HOST"] .
dirname($_SERVER["PHP_SELF"]) . '/gradapproval.php';;
    header('Location: ' . $home_url);
?>

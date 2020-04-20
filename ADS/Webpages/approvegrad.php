<?php
	// Start the session
    session_start();
    require_once('connectvars.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
$uid = $_GET['uid'];

//retrieve the variables to plug into alumni
$info = mysqli_query($dbc, "select * from student where uid = '$uid';");
    $grad = mysqli_fetch_array($info);
    if (!empty($grad)) {
        $degree = $grad[1];
        $gpa = $grad[2];
    }
    else {
        echo 'Error: Unable to retrieve student info';
    }
$gradyear = date("Y");

    //$year = mysqli_query($dbc, "select year from semester where semesterid = ;");

// delete grad from student

mysqli_query($dbc, "DELETE FROM student WHERE uid=$uid");

mysqli_query($dbc, "UPDATE user SET account=2 WHERE uid=$uid");

// add grad to alumni
mysqli_query($dbc, "insert into alumni values ('$uid', '$degree', '$gpa', '$gradyear');");

$home_url = 'http://' . $_SERVER["HTTP_HOST"] .
dirname($_SERVER["PHP_SELF"]) . '/gradapproval.php';;
        header('Location: ' . $home_url);
?>

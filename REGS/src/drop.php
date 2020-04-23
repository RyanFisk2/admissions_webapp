<?php
session_start();

    include ('php/connectvars.php');	
    // Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $uid =  $_SESSION['id'];
    $crn =  $_GET['crn'];

    $cno =  $_GET['cno'];
    $dept = $_GET['dept'];

    $drop1 = "DELETE FROM student_transcript WHERE t_id=$uid and cno=$cno and dept='$dept'";
    mysqli_query($dbc, $drop1);

    $drop = "DELETE FROM courses_taken WHERE u_id=$uid and crn=$crn";
    mysqli_query($dbc, $drop);

    //echo $drop1;
    // die(mysqli_error($dbc));

    header("Location: course.php?cno=$cno&dept=$dept");

    
?>
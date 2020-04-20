<?php
	// Start the session
    session_start();
    require_once('connectvars.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
$uid = $_GET['uid'];

$rejectform1 = "update student set form1status = 0 where uid = '$uid';";
    mysqli_query($dbc, $rejectform1);

$home_url = 'http://' . $_SERVER["HTTP_HOST"] .
dirname($_SERVER["PHP_SELF"]) . '/form1approval.php';;
          header('Location: ' . $home_url);
?>

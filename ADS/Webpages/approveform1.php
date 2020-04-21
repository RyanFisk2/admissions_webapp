<?php
	// Start the session
    session_start();
    require_once('connectvars.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
$uid = $_GET['uid'];

$approveform1 = "update student set form1status = 2 where u_id = '$uid';";
    mysqli_query($dbc, $approveform1);

//$home_url = 'http://' . $_SERVER["HTTP_HOST"] .
//dirname($_SERVER["PHP_SELF"]) . '/gradapproval.php';;
    header('Location:form1approval.php');
?>

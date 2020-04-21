<?php
	// Start the session
    session_start();
    require_once('connectvars.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
$uid = $_GET['uid'];

// student will not appear on gradapproval page
$rejectgrad = "update student set gradapp = 0 where u_id = '$uid';";
    mysqli_query($dbc, $rejectgrad);

$home_url = 'http://' . $_SERVER["HTTP_HOST"] .
dirname($_SERVER["PHP_SELF"]) . '/gradapproval.php';;
          header('Location: ' . $home_url);
?>

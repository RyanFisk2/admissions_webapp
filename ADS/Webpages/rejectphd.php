<?php
	// Start the session
    session_start();
    require_once('connectvars.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
$uid = $_GET['uid'];

$rejectphd = "update student set gradapp = 0 where uid = '$uid';";
    mysqli_query($dbc, $rejectphd);

$home_url = 'http://' . $_SERVER["HTTP_HOST"] .
dirname($_SERVER["PHP_SELF"]) . '/thesisapproval.php';;
          header('Location: ' . $home_url);
?>

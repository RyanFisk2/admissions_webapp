<?php
  // TODO: start session
  session_start();

  $home_url = "login.php";
  // TODO: If the user is logged in, delete the session vars to log them out
  if(isset($_SESSION["user_id"])) {
    // Delete the session vars by clearing the $_SESSION array
    $_SESSION = array();
    // Destroy the session
    session_destroy();
    // Redirect to the home page
    header('Location: ' . $home_url);
   }
   if(isset($_POST["back"])) {
    // Redirect them to the login page
    header('Location: ' . $home_url);
   }

  // TODO: Redirect to the login page
  header('Location:login.php') 
?>

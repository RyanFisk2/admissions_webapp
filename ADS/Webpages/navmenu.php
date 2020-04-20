<?php
  // Generate the navigation menu
  echo '<ul>';
  if (isset($_SESSION['user_id'])) {
    echo '<li><a href="index.php">Home</a></li>  ';
    echo '<li><a href="logout.php">Log Out (' . $_SESSION['username'] . ')</a></li>';
    echo '<li><a href="reset.php">Reset Database</a></li>';
  }
  else{
      echo '<li><a href="login.php">Log In</a></li>';
  }
  echo '</ul>';
  
?>
<link rel="stylesheet" href="style.css">

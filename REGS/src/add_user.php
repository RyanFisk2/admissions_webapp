<!DOCTYPE html>
<html lang="en">

<style>
    .not-nav {
        margin-top: 3%; 
    }
</style>

<head>
  <?php require_once ('header.php'); ?>  
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

<?php
  require_once('php/connectvars.php');
  // TODO: Start the session

  if (!isset($_SESSION['id'])){ // If user is not logged in redirect to login
          header("Location: login.php");
  }

  // Clear the error message
  $error_msg = "";
  // TODO: If the user isn't logged in, try to log them in
  if (!empty($_POST)) {
    if (isset($_POST['submit'])) {
      // Connect to the database
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      // Grab the user-entered log-in data
      $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $user_privilege = mysqli_real_escape_string($dbc, trim($_POST['plevel']));
      if (!empty($user_username) && !empty($user_privilege)) {
        // TODO: Look up the username and password in the database
        $query = "INSERT INTO users (id, p_level, password) VALUES ('$user_username', '$user_privilege', '$user_username')";
        $data = mysqli_query($dbc, $query);
        // If The log-in is OK
        if ($data) {
          //TODO: redirect to index.php
          $home_url = "home.php";
          header('Location: ' . $home_url);
        }
        else {
          // The username/password are incorrect so set an error message
          $error_msg = 'Sorry, you must enter a valid user id and privilege level to create a new user.';
        }
      }
      else {
        // The username/password weren't entered so set an error message
        $error_msg = 'Sorry, you must enter a user id and privilege level to create a new user.';
      }
    }
  }
?>

<body>
  <br />
  <div class="site-section">
  <div class="container">
  <div class="row justify-content-center">
      <div class="col-md-5">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <div class="row">
            <div class="col-md-12 form-group">
              <label for="username">New User ID: </label>
              <input type="text" maxlength=8 id="username" name="username" class="form-control form-control-lg">
            </div>
            <div class="col-md-12 form-group">
              <label for="plevel">New User Privilege Level: </label>
              <input type="text" maxlength=20 id="plevel" name="plevel" class="form-control form-control-lg">
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <input type="submit" value="Create User" class="btn btn-primary btn-lg px-5" name="submit">
<?php
  if (empty($_SESSION['id'])) {
    echo '<br><br><p class="text-danger">' . $error_msg . '</p>';
  }
?>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
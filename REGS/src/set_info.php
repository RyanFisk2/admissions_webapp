<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="css/jquery.fancybox.min.css">
  <link rel="stylesheet" href="css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
  <link rel="stylesheet" href="css/aos.css">
  <link href="css/jquery.mb.YTPlayer.min.css" media="all" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/style.css">
</head>

<?php
    session_start();

    if(!isset($_SESSION['id'])){ // If user is not logged in redirect to login
        header("Location: login.php");
    }

    include('php/connectvars.php');
    $id = $_SESSION['id'];

    $fNameError = $lNameError = $addrError = $emailError = $currentPassError = $newPassError = $newPassConfirmError = $majorError = $progError = $deptError = "";
    $submissionValid = false;
    $showSuccessMsg = false;

    $permLevel = $_SESSION['p_level'];

    switch ($permLevel) {
        
        case 5:
        break;

        case 4:
        break;
        
        case 1:
        header("Location: home.php");
        break;

        case 2:
        header("Location: home.php");
        break;

        default:
        header("Location: home.php");
    }

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $requiredField = " * Required Field ";
    $invalidEntry = " * Invalid Entry ";

    if(isset($_POST['submit'])){
        $emailValid = false;
        $fnameValid = false;
        $lnameValid = false;
        $addrValid = false;
        $majorValid = false;
        $progValid = false;
        $deptValid = false;
        $programNull = false;

        // Check that inputted first name is valid
        if (empty($_POST["fname"])) {
            // First name was empty  - throw error
            $fNameError  = $requiredField;
            $fnameValid = false;
        } else {
            if(preg_match("/^[a-zA-Z ]*$/",$_POST['fname'])) {
                $fnameValid = true;
            } else {
                $fNameError = $invalidEntry;
            }
        }

        // Check that inputted last name is valid
        if (empty($_POST['lname'])) {
            // Last name was empty  - throw error
            $lNameError  = $requiredField;
            $lnameValid = false;
        } else {
            if(preg_match("/^[a-zA-Z ]*$/",$_POST['lname'])) {
                $lnameValid = true;
            } else {
                $lNameError = $invalidEntry;
            }
        }

        // Check that inputted email is valid
        if (empty($_POST['email'])) {
            // Email name was empty  - throw error
            $emailError = $requiredField;
            $emailValid = false;
        } else {
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $emailValid = true;
            } else {
                $emailError = $invalidEntry;
            }
        }

        // Check that inputted address is valid
        if (empty($_POST['address'])) {
            // Address was empty  - throw error
            $addrError = $requiredField;
            $addrValid = false;
        } else {
            if (preg_match("/^[a-zA-Z0-9,.!? ]*$/", $_POST['address'])) { //!preg_match("/^[0-9-]*$/",$_POST["address"])
                $addrValid = true;
            } else {
                $addrError = $invalidEntry;
            }
        }

        if($_SESSION['p_level'] == 5){
          // Check that inputted major is valid
          if (empty($_POST['major'])) {
            // Email name was empty  - throw error
            $majorError = $requiredField;
            $majorValid = false;
          } else {
            if (preg_match("/^[a-zA-Z ]*$/", $_POST['major'])){
                $majorValid = true;
            } else {
                $majorError = $invalidEntry;
            }
          }

          // Check that inputted program is valid
            if (preg_match("/^[a-zA-Z ]*$/", $_POST['program'])) {
                $progValid = true;
            }else if(empty($_POST['program'])){
                $progValid = true;    
            } else {
                $progError = $invalidEntry;
            }
        }else if($_SESSION['p_level'] == 4){
          // Check that inputted department is valid
          if (empty($_POST['dept'])) {
            // Department name was empty  - throw error
            $deptError = $requiredField;
            $deptValid = false;
          } else {
            if (preg_match("/^[A-Z]*$/", $_POST['dept'])){
                $deptValid = true;
            } else {
                $deptError = $invalidEntry;
            }
          }
        }

        if ($fnameValid && $lnameValid && $emailValid && $addrValid) {
          if($_SESSION['p_level'] == 5 && $majorValid && $progValid){
            $submissionValid = true;
          }else if($_SESSION['p_level'] == 4 && $deptValid){
            $submissionValid = true;
          }
        }
        
        if ($submissionValid) {
            
            $showSuccessMsg = true;

            $infoUpdatedMsg = "Account Information Updated Succesfully";
            
            $fname = mysqli_real_escape_string($dbc, trim($_POST['fname']));
            $lname = mysqli_real_escape_string($dbc, trim($_POST['lname']));
            $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
            $address = mysqli_real_escape_string($dbc, trim($_POST['address']));
            if($_SESSION['p_level'] == 5){
              $major = mysqli_real_escape_string($dbc, trim($_POST['major']));
              $program = mysqli_real_escape_string($dbc, trim($_POST['program']));
              if(strcmp($program, "") == 0){
                $programNull = true;
              }
            }else if($_SESSION['p_level'] == 4){
              $dept = mysqli_real_escape_string($dbc, trim($_POST['dept']));
            }
            
            if($_SESSION['p_level'] == 5){
              if($programNull == false){
                $query = "INSERT INTO student (u_id, fname, lname, addr, email, major, degree) VALUES ('$id', '$fname', '$lname', '$address', '$email', '$major', '$program');";
              }else{
                $query = "INSERT INTO student (u_id, fname, lname, addr, email, major) VALUES ('$id', '$fname', '$lname', '$address', '$email', '$major');";
              }
            }else if($_SESSION['p_level'] == 4){
              $query = "INSERT INTO faculty (f_id, fname, lname, addr, email, dept) VALUES ('$id', '$fname', '$lname', '$address', '$email', '$dept');";
            }
            
            if (!mysqli_query($dbc, $query)) {
                echo "Error: " .$query . "<br/>" . mysqli_error($dbc);
                $showSuccessMsg = false;
                $infoUpdatedMsg = "Error: Update was not processed. Contact an administrator.";
            }else{
                $home_url = "change_password.php";
                header('Location: ' . $home_url);
            }
        }
    }
    mysqli_close($dbc);
?>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap">

        <div class="">
            <div class="container not-nav">

                <?php
        $empty_string = "";

        if ($submissionValid && $showSuccessMsg) {
           echo "<div class='alert alert-success' role='alert'>
                    $infoUpdatedMsg
                </div>";
        } else if ($submissionValid && !$showSuccessMsg) {
            echo "<div class='alert alert-danger' role='alert'>
                    $infoUpdatedMsg
                 </div>";
        }
        if($_SESSION['p_level'] == 5){
        ?>

                <form method="post" class="card p-5 mt-4" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="fname">First Name</label><span
                                class="text-danger"><?php echo $fNameError;?></span>
                            <?php echo '<input type="text" maxlength=20 id="fname" name="fname" class="form-control form-control-lg text-muted" value="">';?>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="lname">Last Name</label><span
                                class="text-danger"><?php echo $lNameError;?></span>
                            <?php echo '<input type="text" maxlength=20 id="lname" name="lname" class="form-control form-control-lg text-muted" value="">';?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="email">Email Address</label><span
                                class="text-danger"><?php echo $emailError;?></span>
                            <?php echo '<input type="text" maxlength=30 id="email" name="email" class="form-control form-control-lg text-muted" value="">';?>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="address">Address</label><span
                                class="text-danger"><?php echo $addrError;?></span>
                            <?php echo '<input type="text" maxlength=50 id="address" name="address" class="form-control form-control-lg text-muted" value="">';?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="email">Major</label><span
                                class="text-danger"><?php echo $majorError;?></span>
                            <?php echo '<input type="text" maxlength=20 id="major" name="major" class="form-control form-control-lg text-muted" value="">';?>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="address">Degree (If Masters or PhD)</label><span
                                class="text-danger"><?php echo $progError;?></span>
                            <?php echo '<input type="text" maxlength=3 id="program" name="program" class="form-control form-control-lg text-muted" value="">';?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <input type="submit" value="Set Account Info" name="submit"
                                class="btn btn-primary btn-lg px-5">
                        </div>
                    </div>
                </form>
          <?php
            }else if($_SESSION['p_level'] == 4){
          ?>
                <form method="post" class="card p-5 mt-4" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="fname">First Name</label><span
                                class="text-danger"><?php echo $fNameError;?></span>
                            <?php echo '<input type="text" maxlength=20 id="fname" name="fname" class="form-control form-control-lg text-muted" value="">';?>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="lname">Last Name</label><span
                                class="text-danger"><?php echo $lNameError;?></span>
                            <?php echo '<input type="text" maxlength=20 id="lname" name="lname" class="form-control form-control-lg text-muted" value="">';?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="email">Email Address</label><span
                                class="text-danger"><?php echo $emailError;?></span>
                            <?php echo '<input type="text" maxlength=30 id="email" name="email" class="form-control form-control-lg text-muted" value="">';?>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="address">Address</label><span
                                class="text-danger"><?php echo $addrError;?></span>
                            <?php echo '<input type="text" maxlength=50 id="address" name="address" class="form-control form-control-lg text-muted" value="">';?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="email">Department</label><span
                                class="text-danger"><?php echo $deptError;?></span>
                            <?php echo '<input type="text" maxlength=4 id="dept" name="dept" class="form-control form-control-lg text-muted" value="">';?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <input type="submit" value="Set Account Info" name="submit"
                                class="btn btn-primary btn-lg px-5">
                        </div>
                    </div>
                </form>
          <?php
            }
          ?>
            </div>
        </div>
    </div>
  <div id="loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#51be78"/></svg></div>
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.fancybox.min.js"></script>
  <script src="js/jquery.sticky.js"></script>
  <script src="js/jquery.mb.YTPlayer.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>

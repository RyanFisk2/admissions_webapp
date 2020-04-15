<!DOCTYPE html>
<html lang="en">

<style>
  /* .not-nav {
    margin-top: 2%;
  } */

</style>

<head>
  <title>Account Info - Farm Fresh Regs</title>
  <?php require_once ('header.php'); ?>
</head>

<?php
    session_start();

    if(!isset($_SESSION['id'])){ // If user is not logged in redirect to login
        header("Location: login.php");
    }

    $permLevel = $id = "";

    include('php/connectvars.php');
    if($_SESSION['id'] != 10000000){
        $id = $_SESSION['id'];
        $permLevel = $_SESSION['p_level'];
    }else if(!empty($_GET['student'])){
        $_SESSION['id1'] = $_GET['student'];
        $_SESSION['p_level1'] = "Student";
    }else if(!empty($_GET['faculty'])){
        $_SESSION['id1'] = $_GET['faculty'];
        $_SESSION['p_level1'] = "Faculty";
    }

    if($_SESSION['p_level1'] == "Student"){
        $permLevel = "Student";
        $id = $_SESSION['id1'];
    }else if($_SESSION['p_level1'] == "Faculty"){
        $permLevel = "Faculty";
        $id = $_SESSION['id1'];
    }

    $fNameError = $lNameError = $addrError = $emailError = $currentPassError = $newPassError = $newPassConfirmError =  "" ;
    $submissionValid = false;
    $showSuccessMsg = false;

    $userTable = "";
    $idFormat = "";

    switch ($permLevel) {
        
        case 'Student':
        $userTable = 'student';
        $idFormat = 'u_id';
        break;

        case 'Faculty':
        $userTable = 'faculty';
        $idFormat = 'f_id';
        break;

        case 'Admin':
        if($_SESSION['id'] != 10000000){
            header("Location: home.php");
        }
        break;

        case 'GS':
        if($_SESSION['id'] != 10000000){
            header("Location: home.php");
        }
        break;

        default:
        if($_SESSION['id'] != 10000000){
            header("Location: home.php");
        }
    }

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $user_userTable = mysqli_real_escape_string($dbc, trim($userTable));
    $user_idFormat = mysqli_real_escape_string($dbc, trim($idFormat));

    $account_info_query = "SELECT fname, lname, addr, email FROM $user_userTable WHERE $user_idFormat = $id ";

    $data = mysqli_query($dbc, $account_info_query);

    $accountInfo = mysqli_fetch_array($data);

    $fnameCurrent = $accountInfo['fname'];
    $lnameCurrent = $accountInfo['lname'];
    $emailCurrent = $accountInfo['email'];
    $addressCurrent = $accountInfo['addr'];

    $requiredField = " * Required Field ";
    $invalidEntry = " * Invalid Entry ";

    if(isset($_POST['submit'])){
        $emailValid = false;
        $fnameValid = false;
        $lnameValid = false;
        $addrValid = false;

        // Check that inputted first name is valid
        if (empty($_POST["fname"])) {
            // First name was empty  - throw error
            $fNameError  = $requiredField;
            $fnameValid = false;
        } else {
            if(preg_match("/^[a-zA-Z ]*$/",$_POST['fname'])) {
                $fnameValid = true;
                $fnameCurrent = $_POST['fname'];
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
                $lnameCurrent = $_POST['lname'];
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
                $emailCurrent = $_POST['email'];
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
                $addressCurrent = $_POST['address'];
            } else {
                $addrError = $invalidEntry;
            }
        }

        if ($fnameValid && $lnameValid && $emailValid && $addrValid) {
            $submissionValid = true;
        }
        
        if ($submissionValid) {
            
            $showSuccessMsg = true;

            $infoUpdatedMsg = "Account Information Updated Succesfully";
            
            $fname = mysqli_real_escape_string($dbc, trim($_POST['fname']));
            $lname = mysqli_real_escape_string($dbc, trim($_POST['lname']));
            $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
            $address = mysqli_real_escape_string($dbc, trim($_POST['address']));
            
            $query = "UPDATE $user_userTable SET fname='$fname', lname='$lname', addr='$address', email='$email' WHERE $user_idFormat=$id";

            mysqli_query($dbc, $query);
            
            if (!mysqli_query($dbc, $query)) {
                echo "Error: " .$query . "<br/>" . mysqli_error($dbc);
                $showSuccessMsg = false;
                $infoUpdatedMsg = "Error: Update was not processed. Contact an administrator.";
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
        ?>

                <form method="post" class="card p-5 mt-4" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="fname">First Name</label><span
                                class="text-danger"><?php echo $fNameError;?></span>
                            <?php echo '<input type="text" id="fname" name="fname" class="form-control form-control-lg text-muted" value="'.$fnameCurrent.'">';?>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="lname">Last Name</label><span
                                class="text-danger"><?php echo $lNameError;?></span>
                            <?php echo '<input type="text" id="lname" name="lname" class="form-control form-control-lg text-muted" value="'.$lnameCurrent.'">';?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="email">Email Address</label><span
                                class="text-danger"><?php echo $emailError;?></span>
                            <?php echo '<input type="text" id="email" name="email" class="form-control form-control-lg text-muted" value="'.$emailCurrent.'">';?>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="address">Address</label><span
                                class="text-danger"><?php echo $addrError;?></span>
                            <?php echo '<input type="text" id="address" name="address" class="form-control form-control-lg text-muted" value="'.$addressCurrent.'">';?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <input type="submit" value="Update Account Info" name="submit"
                                class="btn btn-primary btn-lg px-5">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>

</html>
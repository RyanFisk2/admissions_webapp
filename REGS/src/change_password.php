<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Password</title>
    <?php require_once ('header.php'); ?>
</head>

<?php
    session_start();

    if(!isset($_SESSION['id'])){ // If user is not logged in redirect to login
        header("Location: login.php");
    }

    include('php/connectvars.php');
    $id = $_SESSION['id'];

    $currentPassError = $newPassError = $newPassConfirmError =  "" ;
    $showSuccessMsg = false;

    $permLevel = $_SESSION['p_level'];

    switch ($permLevel) {
        
        case 5:
        $userTable = 'student';
        $idFormat = 'u_id';
        break;

        case 4:
        $userTable = 'faculty';
        $idFormat = 'f_id';
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

    // currentPassword
    // newPassword
    // newPasswordConfirm

    $pass_query = "SELECT password FROM users WHERE id = $id";
    $current_pass = mysqli_fetch_array(mysqli_query($dbc, $pass_query));
    $passwordCurrent = $current_pass['password'];

    $passSubmissionValid = false;
    $passFormSubmittedOnce = false;

    if (isset($_POST['submit'])) {
      $passFormSubmittedOnce = true;
      if (empty($_POST['currentPassword'])) {
        $currentPassError = $requiredField;
      } else {
        if ( strcmp($_POST['currentPassword'], $passwordCurrent) != 0) {
          $passSubmissionValid = false;
          $currentPassError = "You're current password does not match the password you've submitted. Please try again.";
        }
      } 

      if (empty($_POST['newPassword'])) {
        $passSubmissionValid = false;
        $newPassError = $requiredField;
      } else {
        // At least one upper and one lower
        // At least one special characters
        // At least one number
        // At least of length 8
        if (preg_match("^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%&*-]).{8,}$^", $_POST['newPassword'])) {
          $passSubmissionValid = true;
        }
      }

      if (empty($_POST['newPasswordConfirm'])) {
        $passSubmissionValid = false;
        $newPassConfirmError = $requiredField;
      }

      if ( !empty($_POST['newPasswordConfirm']) && !empty($_POST['newPassword'])) {
        if ( strcmp($_POST['newPasswordConfirm'], $_POST['newPassword']) != 0) {
          $newPassConfirmError = "Passwords do not match";
        } else {
          if ( strcmp($_POST['newPasswordConfirm'], $passwordCurrent) == 0) {
            $newPassConfirmError = "Please do not reuse old passwords, try something new!";
          }
        }
      }
      if ($passSubmissionValid) {
          $infoUpdatedMsg = "Password Updated Succesfully";
          $showSuccessMsg = true;

          $newPass = mysqli_real_escape_string($dbc, trim($_POST['newPassword']));
          
          $query = "UPDATE users SET password='$newPass' WHERE id = $id";

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

        <div class="site-section">
            <div class="container not-nav">

                <?php
                $empty_string = "";

                if ($passSubmissionValid && $showSuccessMsg) {
                echo "<div class='alert alert-success' role='alert'>
                            $infoUpdatedMsg
                        </div>";
                } else if ($passSubmissionValid && !$showSuccessMsg) {
                    echo "<div class='alert alert-danger' role='alert'>
                            $infoUpdatedMsg
                        </div>";
                }
                ?>

                <div class="row justify-content-center">
                    <div class="col-lg">
                        <div class="white-box">
                            <h3 class="h3 text-primary">Update Password</h3>
                            <p>Enter your current password and follow the proceeding instructions in order to change
                                your
                                password. Make it a good one!</p>
                            <p class="small">
                                <span class="text-danger"> * </span>field required
                            </p>

                            <div class="row">
                                <div class="col-12">
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <div class="form-group">
                                            <label for="PasswordCurrent">
                                                <span class="fas fa-asterisk text-danger"></span> Current Password<span
                                                    class="text-danger"><?php echo $currentPassError;?></span>
                                            </label>
                                            <input type="password" maxlength=20 name="currentPassword" id="currentPassword" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="PasswordNew">
                                                <span class="fas fa-asterisk text-danger"></span> New Password<span
                                                    class="text-danger"><?php echo $newPassError;?>
                                                </span>
                                            </label>
                                            <input type="password" maxlength=20 name="newPassword" class="form-control" id="PasswordNew">

                                            <p class="mt-1">
                                                <button class="btn btn-link btn-sm" id="passwordHelp" type="button"
                                                    data-toggle="collapse" data-target="#collapsePasswordTips"><span
                                                        class="fas fa-lightbulb"></span> Need help
                                                    creating a secure new password?</button>
                                            </p>

                                            <div class="collapse" id="collapsePasswordTips">
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <div class="media">
                                                            <span class="fas fa-times-circle text-danger"></span>
                                                            <div class="media-body ml-2">Don&rsquo;t use a password
                                                                you've used
                                                                before.</div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="media">
                                                            <span class="fas fa-check-circle text-success"></span>
                                                            <div class="media-body ml-2">Include both letters and
                                                                numbers.</div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="media">
                                                            <span class="fas fa-check-circle text-success"></span>
                                                            <div class="media-body ml-2">Use both upper and lower case
                                                                letters.
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="media">
                                                            <span class="fas fa-check-circle text-success"></span>
                                                            <div class="media-body text-primary ml-2">Use atleast one of
                                                                these
                                                                characters: <br><kbd> {
                                                                    [ ( < ! # , $ % ^ @ : \ | / & * - _ +=;> ) ] }
                                                                </kbd></div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="PasswordNewConfirm">
                                                <span class="fas fa-asterisk text-danger"></span> Confirm New
                                                Password<span
                                                    class="text-danger"><?php echo $newPassConfirmError;?></span>
                                            </label>
                                            <input type="password" class="form-control" maxlength=20 name="newPasswordConfirm" id="newPasswordConfirm">
                                            <div class="pl-2 pt-1">
                                                <input type="checkbox" onclick="showPasswords()"> <small>Show
                                                    Password</small>
                                            </div>
                                        </div>
                                        <?php
                          if (!$passSubmissionValid && $passFormSubmittedOnce) {
                            echo '<span class="d-inline-block text-primary bold" data-container="passwordHelp" data-toggle="popover" title="Click me!">';
                          }
                          ?>
                                        <!-- <input type="submit" value="Update Account Info" id="updatePassButton" name="updatePassButton"class="btn btn-primary btn-lg px-5">
                            Update Password</input> -->

                                        <div class="row">
                                            <div class="col-12">
                                                <input type="submit" value="Update Account Info" name="submit"
                                                    class="btn btn-primary btn-lg px-5">
                                            </div>
                                        </div>
                                        <?php
                          if (!$passSubmissionValid && $passFormSubmittedOnce) {
                              echo '</span>';
                          }
                          ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>


        <script>
            function showPasswords() {
                var pass1 = document.getElementById("newPasswordConfirm");
                var pass2 = document.getElementById("PasswordNew");
                if (pass1.type == "password" || pass2.type == "password") {
                    pass1.type = "text";
                    pass2.type = "text";
                } else {
                    pass1.type = "password";
                    pass2.type = "password";
                }
            }

            $(document).ready(function () {
                $('[data-toggle="popover"]').popover({
                    placement: 'right',
                    delay: {
                        show: 500,
                        hide: 100
                    }
                });

                $('[data-toggle="popover"]').click(function () {

                    setTimeout(function () {
                        $('.popover').fadeOut('slow');
                    }, 5000);
                });
            });
        </script>

</body>

</html>
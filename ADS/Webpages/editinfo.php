<?php
    $fnameErr = $minitErr = $lnameErr = $addrErr = $emailErr = "";

	require_once('header.php');
    //Very important if you actually want to use the DB
	require_once('connectvars.php');
      
    if ($_SERVER["REQUEST_METHOD"] == "POST"){ //If we submit changes

        $allInfo = true;

        if (empty($_POST["fname"])) {
            $fnameErr = "Please enter name";
            $allInfo = false;
        }
        else {
            $firstname = test_input($_POST["fname"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z]*$/",$firstname)) {
              $fnameErr = "Only letters allowed";
              $allInfo = false;
            }
            else if (strlen($firstname) > 30){
                $fnameErr = "Maximum name length is 30 characters";
                $allInfo = false;
            }
        }
        if (empty($_POST["lname"])) {
            $lnameErr = "Please enter name";
            $allInfo = false;
        }
        else {
            $lastname = test_input($_POST["lname"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-]*$/",$lastname)) {
              $lnameErr = "Only letters allowed";
              $allInfo = false;
            }
            else if (strlen($lastname) > 30){
                $lnameErr = "Maximum name length is 30 characters";
                $allInfo = false;
            }
        }
        if (empty($_POST["address"])) {
            $addrErr = "Address is required";
            $allInfo = false;
        } 
        else {
            $address = test_input($_POST["address"]);
            if (!preg_match("/^[a-zA-Z1-9., ]*$/",$address)) {
                $addrErr = "Only valid address characters allowed";
                $allInfo = false;
            }
            else if (strlen($address) > 80){
                $addrErr = "Maximum address length is 80 characters";
                $allInfo = false;
            }
        }
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
            $allInfo = false;
        } 
        else {
            $email = test_input($_POST["email"]);
            // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $emailErr = "Invalid email format";
              $allInfo = false;
            }
            else if (!preg_match("/^[a-zA-Z1-9@.]*$/",$email)) {
                $emailErr = "Only valid email characters allowed";
                $allInfo = false;
            }
            else if (strlen($lastname) > 50){
                $emailErr = "Maximum email length is 50 characters";
                $allInfo = false;
            }
        }
        if ($allInfo){
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if ($_SESSION['acc_type'] == 7){
                $db = "applicant";
                $id_name = "app_id";
            }
            else if ($_SESSION['acc_type'] == 6){
                $db = "alumni";
                $id_name = "a_id";
            }
            else if ($_SESSION['acc_type'] == 5){
                $db = "student";
                $id_name = "u_id";
            }		    
            else if ($_SESSION['acc_type'] == 4){
                $db = "faculty";
                $id_name = "f_id";
            } 
            $query = "UPDATE $db SET fname='".$_POST['fname']."', lname='".$_POST['lname']."', addr='".$_POST['address']."', email='".$_POST['email']."' WHERE $id_name='" . $_SESSION['user_id'] . "'";
            $data = mysqli_query($dbc, $query);
            header('Location:info.php');
        }
    }
    
	if (isset($_SESSION['user_id'])) {
        ?>
        <br><br>
        <div class="site-section">
            <div class="container">
		        <div class="row mb-4 justify-content-center text-center">
          	        <div class="col-lg-4 mb-4">
            			<h2 class="section-title-underline mb-4">
              			        <span>Personal Information</span>
            			</h2>
          		    </div>
        	    </div>
                <?php
        if ($_SESSION['acc_type'] == 7){
            $db = "applicant";
            $id_name = "app_id";
        }
        else if ($_SESSION['acc_type'] == 6){
            $db = "alumni";
            $id_name = "a_id";
        }
        else if ($_SESSION['acc_type'] == 5){
            $db = "student";
            $id_name = "u_id";
        }		    
        else if ($_SESSION['acc_type'] == 4){
            $db = "faculty";
            $id_name = "f_id";
        } 

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "SELECT * FROM $db WHERE $id_name='" . $_SESSION['user_id'] . "'";
    	$data = mysqli_query($dbc, $query);
		$row = mysqli_fetch_array($data);
        
        echo '<div class="row mb-4 justify-content-center text-center">';
        echo '<form method="post" class="form" action="'.$_SERVER['PHP_SELF'].'">'; //List all EDITABLE fields

        echo '<label for="fname">First name: </label>';
        echo '<input type="text" id="fname" name="fname" value="'.$row["fname"].'">'.$fnameErr.' &nbsp;';

        echo '<label for="lname">Last name:</label>';
        echo '<input type="text" id="lname" name="lname" value="'.$row["lname"].'">'.$lnameErr.'<br><br> ';

        echo '<label for="address">Address: </label>';
        echo '<input type="text" id="address" name="address" value="'.$row["addr"].'">'.$addrErr.' &nbsp;';

        echo '<label for="email">Email:</label>';
        echo '<input type="text" id="email" name="email" value="'.$row["email"].'">'.$emailErr.' &nbsp;';

        if ($_SESSION['acc_type'] != 5){
            echo '<br><b>*For any other information updates, please contact system administrator.</b><br>';
        }
        echo '<br><br>';
	    echo '<input type="submit" class="button" value="Submit Changes">';
	    echo '</form>';
    }
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } 
?>

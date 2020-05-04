<?php

require_once('header.php');
echo '<br><br>';
require_once('connectvars.php');
// Show the navigation menu


if($_SESSION['acc_type'] < 6) {
        ?>
        <div class="site-section">
      			<div class="container">
			        <div class="row mb-4 justify-content-center text-center">
          				<div class="col-lg-4 mb-4">
            				        <h2 class="section-title-underline mb-4">
              				        <span>View Form 1's</span>
            				        </h2>
          				</div>
                                </div>

                <?php
                //echo '<table class="table">';
                if($_SESSION['acc_type'] == 4)
                        $query = "SELECT * FROM student s WHERE advisor='" . $_SESSION['user_id'] . "'";
                else if (($_SESSION['acc_type'] < 4))
                        $query = "SELECT * FROM student";
                else{
                        $id = $_SESSION['id'];
                        $query = "SELECT * FROM student where u_id = $id";
                }

                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                $data = mysqli_query($dbc, $query);
                while($uid = mysqli_fetch_array($data)){
                        //echo '<div class="row mb-4 justify-content-center text-center">';
                        echo '<div class="row mb-4 justify-content-center text-center">';
                        echo '<table class="table">';
                        echo "User ID:".$uid['u_id']."  | Name: " . $uid['fname'] . " " . $uid['lname'];
                        if ($uid['form1status'] == 2)
                                echo ' | Status: Approved';
                        else
                                echo ' | Status: Unapproved';
                        //echo '</div>';
                        $query = "SELECT * FROM form1 f, catalog c WHERE c.c_no = f.cno AND f.dept = c.department AND f.f1_id='" . $uid['u_id'] . "'";

                        $dbc1 = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                        $data1 = mysqli_query($dbc1, $query);
                	if ( mysqli_num_rows($data1) >0) {
                                echo '<th>Department</th><th>Course Number</th><th>Course Title</th><th>Credits</th></tr>';
                                while($row = mysqli_fetch_array($data1)) {
                                                echo "<tr><td>".$row['dept'] . "</td><td>".  $row['cno'] . "</td><td>".  $row['title'] . "</td><td>".  $row['credits'] ."</td></tr>";
                                }
                                echo '<br>';
                        }
                        // else{
                        // echo ": Has no approved form one. <br />";
                        //}
                        echo '</div>';
                        echo '</table>';
                        echo '<br>';
                }

}
echo '</table>';
?>


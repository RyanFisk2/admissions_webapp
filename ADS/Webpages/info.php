<?php

	require_once('header.php');

	require_once('connectvars.php');

	
	if (isset($_SESSION['user_id'])) { ?>

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
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "SELECT * FROM user WHERE uid='" . $_SESSION['user_id'] . "'";
    	$data = mysqli_query($dbc, $query);
		$row = mysqli_fetch_array($data);
		echo '<table class="table">';
		echo '<tr><th>First Name</th><th>Middle Initial</th><th>Last name</th><th>Address</th><th>Email</th><th>Date of Birth</th>';

		if($_SESSION['acc_type'] == 1){ //Student
			echo '<th>GPA</th><th>Faculty Advisor</th><th>Degree</th></tr>';
			//Put all data from row into table
			echo '<td>'.$row["fname"].'</td><td>'.$row["minit"].'</td><td>'.$row["lname"].'</td><td>'.$row["address"].'</td><td>'.$row["email"].'</td><td>'.$row["dob"].'</td>';
			$query = "SELECT * FROM student WHERE uid='" . $_SESSION['user_id'] . "'";
			$data = mysqli_query($dbc, $query);
			$row = mysqli_fetch_array($data);
			//Put rest of data into table

			$query2 = "SELECT fname, lname FROM user WHERE uid='" . $row['advisor'] . "'";
			$data2 = mysqli_query($dbc, $query2);
			$row2 = mysqli_fetch_array($data2);
			echo '<td>'.$row["gpa"].'</td><td>'.$row2["fname"].' '.$row2["lname"].'</td><td>'.$row["degree"].'</td></tr>';
		}
		else if ($_SESSION['acc_type'] == 2){ //Alumni
			echo '<th>GPA</th><th>Graduation Year</th><th>Degree Completed</th></tr>';
			//Put data from user into table
			echo '<td>'.$row["fname"].'</td><td>'.$row["minit"].'</td><td>'.$row["lname"].'</td><td>'.$row["address"].'</td><td>'.$row["email"].'</td><td>'.$row["dob"].'</td>';
			$query = "SELECT * FROM alumni WHERE uid='" . $_SESSION['user_id'] . "'";
			$data = mysqli_query($dbc, $query);
			$row = mysqli_fetch_array($data);
			echo '<td>'.$row["gpa"].'</td><td>'.$row["gradyear"].'</td><td>'.$row["degree"].'</td></tr>';
		}
		else if ($_SESSION['acc_type'] == 3){ //Faculty
			echo '</tr><tr><td>'.$row["fname"].'</td><td>'.$row["minit"].'</td><td>'.$row["lname"].'</td><td>'.$row["address"].'</td><td>'.$row["email"].'</td><td>'.$row["dob"].'</td></tr>';
		}
		else if ($_SESSION['acc_type'] == 4){ //Graduation Secretary
			//Put data from user into table
			echo '</tr><tr><td>'.$row["fname"].'</td><td>'.$row["minit"].'</td><td>'.$row["lname"].'</td><td>'.$row["address"].'</td><td>'.$row["email"].'</td><td>'.$row["dob"].'</td></tr>';
		}
		else{ //System admin
			//Nothing additional 
			echo '</tr><tr><td>'.$row["fname"].'</td><td>'.$row["minit"].'</td><td>'.$row["lname"].'</td><td>'.$row["address"].'</td><td>'.$row["email"].'</td><td>'.$row["dob"].'</td></tr>';
		}
		
		echo '</table>';
	}
	echo '<br>';
	echo '<div class="row mb-4 justify-content-center text-center">';
	echo '<form action="editinfo.php">';
	echo '<input type="submit" class="button" value="Edit Personal Information">';
	echo '</form>';


?>

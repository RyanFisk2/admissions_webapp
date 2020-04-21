<?php
	require_once('header.php');
	require_once('connectvars.php');
	?>
	<br><br>
	<div class="site-section">
      			<div class="container">
					<div class="row mb-4 justify-content-center text-center">
          				<div class="col-lg-4 mb-4">
            				<h2 class="section-title-underline mb-4">
              				<span>Advisees</span>
            				</h2>
          				</div>
					</div>
					<div class="row mb-4 justify-content-center text-center">
	<?php
	
  	if (isset($_SESSION['user_id'])) {
		echo '<table class="table">';
		echo '<tr><th>First Name</th><th>Last name</th><th>Email</th><th>Degree</th><th>GPA</th><th>UID</th></tr>';
		if (isset($_SESSION['acc_type'])){
            //Get student info
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		    $query = "SELECT * FROM student WHERE advisor='" . $_SESSION['user_id'] . "'";
    	    $data = mysqli_query($dbc, $query);
			while ($row = mysqli_fetch_array($data)) {            
				echo '<tr><td>' . $row['fname'] . ' </td><td>' . $row['lname'] . ' </td><td>' . $row['email'] . ' </td><td>' . $row['degree'] . ' </td><td>' . $row['gpa'] . ' </td><td>' . $row['u_id'] . ' </td></tr>';
			}
            echo '</table>';
        }
    }

    ?>
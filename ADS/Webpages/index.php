<!DOCTYPE html>

<style>
    .not-nav {
        margin-top: 3%; 
    }
</style>

<head>

<?php
	require_once('header.php');

	require_once('connectvars.php');
?>
<br></br>
</head>
<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
<?php	
  	if (isset($_SESSION['user_id'])) {
		if (isset($_SESSION['acc_type'])){
			if($_SESSION['acc_type'] == 5){ //Student
			?>
			<div class="site-section">
      			<div class="container">
					<div class="row mb-4 justify-content-center text-center">
          				<div class="col-lg-4 mb-4">
            				<h2 class="section-title-underline mb-4">
							  <span>Home</span>
							  <br><br>
            				</h2>
          				</div>
        			</div>
					<div class="row">
          				<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>View Transcipt</h2>
                					<p>View the most up to date version of your unofficial transcript.</p>
                					<p><a href="transcript.php" class="btn btn-primary px-4 rounded-0">View Transcipt</a></p>
								</div>
            				</div>
          				</div>
						<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
                					<span class="flaticon-book text-white"></span>
              					</div>
              					<div class="feature-1-content">
                					<h2>Personal Information</h2>
                					<p>View and edit your personal information.</p>
                					<p><a href="info.php" class="btn btn-primary px-4 rounded-0">View Personal Information</a></p>
              					</div>
            				</div> 
          				</div>
						<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
									<span class="flaticon-university text-white"></span>
              					</div>
              					<div class="feature-1-content">
                					<h2>Submit Form One</h2>
                					<p>Fill out and submit for Form 1 approval.</p>
                					<p><a href="form1.php" class="btn btn-primary px-4 rounded-0">Fill out</a></p>
              					</div>
            				</div> 
          				</div>	
					</div>
    				<br></br>
    				<br />
					<div class="row mb-5 justify-content-center text-center">
          				<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
                					<span class="flaticon-mortarboard text-white"></span>
              					</div>
              					<div class="feature-1-content">
                					<h2>View Form 1</h2>
                					<p>View submitted Form One.</p>
                					<p><a href="viewform1.php" class="btn btn-primary px-4 rounded-0">View</a></p>
              					</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
                					<span class="flaticon-mortarboard text-white"></span>
              					</div>
              					<div class="feature-1-content">
                					<h2>Graduation Application</h2>
                					<p>Submit application for graduation.</p>
                					<p><a href="gradapp.php" class="btn btn-primary px-4 rounded-0">Apply</a></p>
              					</div>
            				</div>
          				</div>
					</div>
				</div>	
			</div>
			<?php 
			}
			else if($_SESSION['acc_type'] == 6){ //Alumni
			?>
			<br>
			<div class="site-section">
      			<div class="container">
					<div class="row mb-4 justify-content-center text-center">
          				<div class="col-lg-4 mb-4">
            				<h2 class="section-title-underline mb-4">
							  <span>Home</span>
							  <br><br>
            				</h2>
          				</div>
        			</div>
					<div class="row">
          				<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>View Transcipt</h2>
                					<p>View the most updated version of your unofficial transcript.</p>
                					<p><a href="transcript.php" class="btn btn-primary px-4 rounded-0">View Transcipt</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
                					<span class="flaticon-book text-white"></span>
              					</div>
              					<div class="feature-1-content">
                					<h2>Personal Information</h2>
                					<p>View and edit your personal information.</p>
                					<p><a href="info.php" class="btn btn-primary px-4 rounded-0">View Personal Information</a></p>
              					</div>
            				</div> 
						</div>
					</div>
				</div>	
			</div>
			<?php	
			}
			else if($_SESSION['acc_type'] == 4){ //Faculty
			?>
			<div class="site-section">
      			<div class="container">
					<div class="row mb-4 justify-content-center text-center">
          				<div class="col-lg-4 mb-4">
            				<h2 class="section-title-underline mb-4">
							  <span>Home</span>
							  <br><br>
            				</h2>
          				</div>
        			</div>
					<div class="row">
          				<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-mortarboard text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Advisee Graduation</h2>
                					<p>View the graduation status of your advisees.</p>
                					<p><a href="gradstatus.php" class="btn btn-primary px-4 rounded-0">View Status</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Advisee Transcripts</h2>
                					<p>View transcipts of your advisees.</p>
                					<p><a href="transcript.php" class="btn btn-primary px-4 rounded-0">View Transcipts</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Advisee Form Ones</h2>
                					<p>Check Form Ones from advisees.</p>
                					<p><a href="viewform1.php" class="btn btn-primary px-4 rounded-0">View Transcipts</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-ink text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Approve Form Ones</h2>
                					<p>Approve Form Ones from advisees.</p>
                					<p><a href="form1approval.php" class="btn btn-primary px-4 rounded-0">Approve Forms</a></p>
								</div>
            				</div>
          				</div>
					</div>
					<br><br><br>
					<div class="row">
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-ink text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Advisee PhD Theses</h2>
                					<p>Approve Theses from advisees.</p>
                					<p><a href="thesisapproval.php" class="btn btn-primary px-4 rounded-0">View Theses</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Advisees</h2>
                					<p>Show list of all advisees.</p>
                					<p><a href="advisees.php" class="btn btn-primary px-4 rounded-0">View Advisees</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-book text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Personal Information</h2>
                					<p>View and edit personal information.</p>
                					<p><a href="info.php" class="btn btn-primary px-4 rounded-0">View Information</a></p>
								</div>
            				</div>
          				</div>
					</div>
				</div>
			</div>
			<?php }
			else if($_SESSION['acc_type'] == 2){ //Grad Secretary
			?>
			<div class="site-section">
      			<div class="container">
					<div class="row mb-4 justify-content-center text-center">
          				<div class="col-lg-4 mb-4">
            				<h2 class="section-title-underline mb-4">
							  <span>Home</span>
							  <br><br>
            				</h2>
          				</div>
        			</div>
					<div class="row">
          				<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-mortarboard text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Graduation Status</h2>
                					<p>View graduation status of students.</p>
                					<p><a href="gradstatus.php" class="btn btn-primary px-4 rounded-0">View Status</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Transcripts</h2>
                					<p>View transcipts of all students.</p>
                					<p><a href="transcript.php" class="btn btn-primary px-4 rounded-0">View Transcipts</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Forms 1's</h2>
                					<p>View Form Ones of all students.</p>
                					<p><a href="form1approval.php" class="btn btn-primary px-4 rounded-0">View Forms</a></p>
								</div>
            				</div>
						</div>
					</div>
					<br><br><br>
					<div class="row">
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-mortarboard text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Graduation Approval</h2>
                					<p>Approve requests to graduate.</p>
                					<p><a href="gradapproval.php" class="btn btn-primary px-4 rounded-0">Student Graduation</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-ink text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Assign Advisor</h2>
                					<p>Assign faculty advisors to students.</p>
                					<p><a href="assignadvisor.php" class="btn btn-primary px-4 rounded-0">Assign</a></p>
								</div>
            				</div>
          				</div>
						  <div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-mortarboard text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Find Alumni</h2>
                					<p>View or search for Alumni.</p>
                					<p><a href="alumni.php" class="btn btn-primary px-4 rounded-0">View Alumni</a></p>
								</div>
            				</div>
          				</div>
					</div>
				</div>
			</div>
			<?php
			}
			else if($_SESSION['acc_type'] == 1){	//Sys Admin
			?>
			<div class="site-section">
      			<div class="container">
					<div class="row mb-4 justify-content-center text-center">
          				<div class="col-lg-4 mb-4">
            				<h2 class="section-title-underline mb-4">
							  <span>Home</span>
							  <br><br>
            				</h2>
          				</div>
        			</div>
					<div class="row">
          				<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-mortarboard text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Create User</h2>
                					<p>Create a new user with specifications.</p>
                					<p><a href="createnewuser.php" class="btn btn-primary px-4 rounded-0">Create User</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Transcripts</h2>
                					<p>View transcipts of all students.</p>
                					<p><a href="transcript.php" class="btn btn-primary px-4 rounded-0">View Transcipts</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Forms 1's</h2>
                					<p>View Form Ones of all students.</p>
                					<p><a href="form1approval.php" class="btn btn-primary px-4 rounded-0">View Forms</a></p>
								</div>
            				</div>
						</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-mortarboard text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Graduation Status</h2>
                					<p>Check graduation status of all students.</p>
                					<p><a href="gradstatus.php" class="btn btn-primary px-4 rounded-0">View Status</a></p>
								</div>
            				</div>
						</div>
					</div>
					<br><br><br>
					<div class="row">
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-mortarboard text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>PhD Theses</h2>
                					<p>Check PhD theses for all PhD students.</p>
                					<p><a href="thesisapproval.php" class="btn btn-primary px-4 rounded-0">View Theses</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Grad. Applications</h2>
                					<p>View graduation applications for students.</p>
                					<p><a href="gradapproval.php" class="btn btn-primary px-4 rounded-0">View Requests</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Assign Advisors</h2>
                					<p>Assign or switch faculty advisors of students.</p>
                					<p><a href="assignadvisor.php" class="btn btn-primary px-4 rounded-0">Advising</a></p>
								</div>
            				</div>
						</div>
					</div>
			<?php	
			}

		}
		else{
			echo'Account error. Not authorized access to advising system.';
		}
		//echo '</div>';
	}
?>


<?php
	//require_once('footer.php');
	/*if (isset($_SESSION['gpacalc']))
	if (!($_SESSION['gpacalc'])){
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "SELECT id, p_level FROM users WHERE p_level=5 OR p_level=6";
    	$data = mysqli_query($dbc, $query);
		while ($row = mysqli_fetch_array($data)){
			$query2 = "SELECT grade FROM student_transcript WHERE t_id='" . $row['id'] . "'";
			$data2 = mysqli_query($dbc, $query2);
			$totalClasses = 0.00;
			$totalCred = 0.00;
			if ($data2)
			while ($row2 = mysqli_fetch_array($data2)){
				$totalClasses++;
				if ($row2['grade'] == 'A+' || $row2['grade'] == 'A')
					$totalCred += 4.00;
				else if ($row2['grade'] == 'A-')
					$totalCred += 3.70;
				else if ($row2['grade'] == 'B+')
					$totalCred += 3.30;
				else if ($row2['grade'] == 'B')
					$totalCred += 3.00;
				else if ($row2['grade'] == 'B-')
					$totalCred += 2.70;	
				else if ($row2['grade'] == 'C+')
					$totalCred += 2.30;	
				else if ($row2['grade'] == 'C')
					$totalCred += 2.00;
				else if ($row2['grade'] == 'C-')
					$totalCred += 1.70;	
				else if ($row2['grade'] == 'D+')
					$totalCred += 1.30;
				else if ($row2['grade'] == 'D')
					$totalCred += 1.00;	
				else if ($row2['grade'] == 'D-')
					$totalCred += 0.70;	
				else if ($row2['grade'] == 'IP')
					$totalClasses--;
			}
			if ($totalClasses != 0.00){
				$gpa = $totalCred/$totalClasses;
				if ($row['p_level'] == 5){
					$query3 = "UPDATE student SET gpa='".$gpa."' WHERE u_id='" . $row['id'] . "'";
				}
				else
					$query3 = "UPDATE alumni SET gpa='".$gpa."' WHERE a_id='" . $row['id'] . "'";
				$data3 = mysqli_query($dbc, $query3);
			}
		}
		$_SESSION['gpacalc'] = true;
	}*/
?>
</body>
</html>
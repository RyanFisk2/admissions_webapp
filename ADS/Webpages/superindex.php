<!DOCTYPE html>

<style>
    .not-nav {
        margin-top: 3%; 
    }
</style>

<head>

<?php
	require_once('superindexheader.php');

    require_once('connectvars.php');
    
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $id = $_SESSION['user_id'];
    if ($_SESSION['acc_type'] == 7){
		$data = mysqli_query($dbc, "SELECT fname FROM applicant WHERE app_id=$id");
		$row = mysqli_fetch_array($data);
	}
    else if ($_SESSION['acc_type'] == 6){
		$data = mysqli_query($dbc, "SELECT fname FROM alumni WHERE a_id=$id");
		$row = mysqli_fetch_array($data);
	}
    else if ($_SESSION['acc_type'] == 5){
		$data = mysqli_query($dbc, "SELECT fname FROM student WHERE u_id=$id");
		$row = mysqli_fetch_array($data);
	}
	else if ($_SESSION['acc_type'] == 4){
		$data = mysqli_query($dbc, "SELECT fname FROM faculty WHERE f_id=$id");
		$row = mysqli_fetch_array($data);
	}
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
          				<div class="col">
            				<h2 class="section-title-underline mb-4">
                                <span>Welcome, <?php echo $row[0]; ?></span>
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
                					<h2>Registration System</h2>
                					<p>Access all tools needed for registartion.</p>
                					<p><a href="../../REGS/src/home.php" class="btn btn-primary px-4 rounded-0">Access System</a></p>
								</div>
            				</div>
          				</div>
						<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
                					<span class="flaticon-book text-white"></span>
              					</div>
              					<div class="feature-1-content">
                					<h2>Advising System</h2>
                					<p>Access all tools related to advising.</p>
                					<p><a href="index.php" class="btn btn-primary px-4 rounded-0">Access System</a></p>
              					</div>
            				</div> 
          				</div>	
					</div>
				</div>	
			</div>
			<?php 
			}
			else if($_SESSION['acc_type'] == 6){ //Alumni only needs ads system
                header('Location:index.php');
            }
            else if($_SESSION['acc_type'] == 7){ //Applicant only needs apps system
                header('Location:../../APPS/index.php');
            }
            else{ //Faculty, GS, and Admin have access to all systems
                if($_SESSION['acc_type'] == 3)
                    $user = "Chair of Admissions";
                else if($_SESSION['acc_type'] == 2)
                    $user = "Graduation Secretary";
                else if($_SESSION['acc_type'] == 1)
					$user = "Administrator";
				else if($_SESSION['acc_type'] == 4)
					$user = $row[0];

			?>
			<div class="site-section">
      			<div class="container">
					<div class="row mb-4 justify-content-center text-center">
          				<div class="col">
            				<h2 class="section-title-underline mb-4">
                                <span>Welcome, <?php echo $user; ?></span>
                                <br><br><br>
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
                					<h2>Application System</h2>
                					<p>Access all tools needed for student application.</p>
                					<p><a href="../../APPS/index.php" class="btn btn-primary px-4 rounded-0">Access System</a></p>
								</div>
            				</div>
          				</div>
          				<div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
               						<span class="flaticon-books-1 text-white"></span>
              					</div>
								<div class="feature-1-content">
                					<h2>Registration System</h2>
                					<p>Access all tools needed for registartion.</p>
                					<p><a href="../../REGS/src/home.php" class="btn btn-primary px-4 rounded-0">Access System</a></p>
								</div>
            				</div>
          				</div>
                          <div class="col">
            				<div class="feature-1 border">
              					<div class="icon-wrapper bg-primary">
                					<span class="flaticon-book text-white"></span>
              					</div>
              					<div class="feature-1-content">
                					<h2>Advising System</h2>
                					<p>Access all tools related to student advising.</p>
                					<p><a href="index.php" class="btn btn-primary px-4 rounded-0">Access System</a></p>
              					</div>
            				</div> 
          				</div>
					</div>
				</div>	
            </div>
            <?php
            }
        }
    }

	//require_once('footer.php');
	if (isset($_SESSION['gpacalc']))
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
	}
?>
</body>
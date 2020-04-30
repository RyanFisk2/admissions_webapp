<?php
	require_once('header.php');

	require_once('connectvars.php');
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>
<br><br>
<div class="site-section">
      			<div class="container">
					<div class="row mb-4 justify-content-center text-center">
          				<div class="col-lg-4 mb-4">
            				<h2 class="section-title-underline mb-4">
              				<span>Approve Theses</span>
            				</h2>
          				</div>
        			</div>
<!-- search bar -->
	<div class="row mb-4 justify-content-center text-center">
		<form>
		<input type="search" name="search" placeholder="Search..."/>
		<input type="submit" />
		</form>
	</div>
	<div class="row mb-4 justify-content-center text-center">
<br>
<div>
  
<?php

// prints out users that match the search
if ($_SESSION['acc_type'] == 2){
	if(isset($_GET['search'])) {
		$searchapplicants = mysqli_query($dbc, "select * from student where (fname like '%{$_GET['search']}%' or lname like '%{$_GET['search']}%') and degree = phd and gradapp = 1;");
		while ($searchapplicant = mysqli_fetch_array($searchapplicants)) {
			?>
		  
		  <label><?php echo $searchapplicant['fname']; echo ' '; echo $searchapplicant['lname'] . ' '; ?></label>
		  <form action='approvephd.php'>
		  <input type="hidden" name="uid" value="<?php echo $searchapplicant['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Approve" />
		  </form>
		  <form action='rejectphd.php'>
		  <input type="hidden" name="uid" value="<?php echo $searchapplicant['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Reject" />
		  </form>
		  <br>
		  <?php
		}
	
	}
	else {
		// prints out everyone applying to graduate
		$applicants = mysqli_query($dbc, "select * from student where degree = 'PhD' and gradapp = 1;");
		while ($applicant = mysqli_fetch_array($applicants)) {
			?>
			<label><?php echo $applicant['fname']; echo ' '; echo $applicant['lname'] . ' '; ?></label>
			  <form action='approvephd.php'>
			  <input type="hidden" name="uid" value="<?php echo $applicant['u_id']?>" />
			  <input type="submit" class="button" name="decision" value="Approve" />
			  </form>
			  <form action='rejectphd.php'>
			  <input type="hidden" name="uid" value="<?php echo $applicant['u_id']?>" />
			  <input type="submit" class="button" name="decision" value="Reject" />
			  </form>
			  <br>
			<?php
		}
	}
}
else if ($_SESSION['acc_type'] == 4){
	$myid = $_SESSION['id'];
	if(isset($_GET['search'])) {
		$searchapplicants = mysqli_query($dbc, "select * from student where (fname like '%{$_GET['search']}%' or lname like '%{$_GET['search']}%') and degree = phd and gradapp = 1 and advisor = $myid;");
		while ($searchapplicant = mysqli_fetch_array($searchapplicants)) {
			?>
		  
		  <label><?php echo $searchapplicant['fname']; echo ' '; echo $searchapplicant['lname'] . ' '; ?></label>
		  <form action='approvephd.php'>
		  <input type="hidden" name="uid" value="<?php echo $searchapplicant['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Approve" />
		  </form>
		  <form action='rejectphd.php'>
		  <input type="hidden" name="uid" value="<?php echo $searchapplicant['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Reject" />
		  </form>
		  <br>
		  <?php
		}
	
	}
	else {
	// prints out everyone applying to graduate
		$applicants = mysqli_query($dbc, "select * from student where degree = 'PhD' and gradapp = 1 and advisor = $myid;");
		while ($applicant = mysqli_fetch_array($applicants)) {
			?>
		  <label><?php echo $applicant['fname']; echo ' '; echo $applicant['lname'] . ' '; ?></label>
		  <form action='approvephd.php'>
		  <input type="hidden" name="uid" value="<?php echo $applicant['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Approve" />
		  </form>
		  <form action='rejectphd.php'>
		  <input type="hidden" name="uid" value="<?php echo $applicant['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Reject" />
		  </form>
		  <br>
		  <?php
		}
	}
}
?>
<br>	
<?php

?>
</div>

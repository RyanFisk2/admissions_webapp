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
              		<span>Graduation Approval</span>
            	</h2>
          	</div>
        </div>
		<div class="row mb-4 justify-content-center text-center">
<!-- search bar -->
	<form>
	<input type="search" name="search" placeholder="Search..." />
	<input type="submit" />
	</form>
	</div>
<br>
<div>
  
<?php

// prints out users that match the search
	  if(isset($_GET['search'])) {
		  $searchapplicants = mysqli_query($dbc, "select * from student where (fname like '%{$_GET['search']}%' or lname like '%{$_GET['search']}%') and gradapp = 2;");
		while ($searchapplicant = mysqli_fetch_array($searchapplicants)) {
			?>
		  <div class="row mb-4 justify-content-center text-center">
		  <label><?php echo $searchapplicant['fname']; echo ' '; echo $searchapplicant['lname'] . ' &nbsp;'; ?></label>
		  <form action='approvegrad.php'>
		  <input type="hidden" name="uid" value="<?php echo $searchapplicant['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Approve" />
		  </form>
		  <?php echo '&nbsp;'; ?>
		  <form action='removegrad.php'>
		  <input type="hidden" name="uid" value="<?php echo $searchapplicant['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Reject" />
		  </form>
		  <br>
		  </div>
		  <?php
		}
	
	  }
	else {
	// prints out everyone applying to graduate
	$applicants = mysqli_query($dbc, "select * from student where gradapp = 2;");
	while ($applicant = mysqli_fetch_array($applicants)) {
		?>
		<div class="row mb-4 justify-content-center text-center">
		<label><?php echo $applicant['fname']; echo ' '; echo $applicant['lname'] . ' &nbsp;';?></label>
		  <form action='approvegrad.php'>
		  <input type="hidden" name="uid" value="<?php echo $applicant['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Approve" />
		  </form>
		  <?php echo '&nbsp;'; ?>
		  <form action='removegrad.php'>
		  <input type="hidden" name="uid" value="<?php echo $applicant['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Reject" />
		  </form>
		  </div>
		  <br>
		<?php
	}
?>
<br>	
<?php
 }

?>
</div>

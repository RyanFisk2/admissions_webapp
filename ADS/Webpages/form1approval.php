
<?php
	// Insert the page header
	require_once('header.php');

	require_once('connectvars.php');
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	// Show the navigation menu
	$myuid = $_SESSION['user_id'];
?>
<br><br>
<div class="site-section">
    <div class="container">
		<div class="row mb-4 justify-content-center text-center">
          	<div class="col-lg-4 mb-4">
            	<h2 class="section-title-underline mb-4">
              		<span>Approve Form 1's</span>
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
<br>
<div>
  
<?php

// prints out users that match the search
	  if(isset($_GET['search'])) {
		if($_SESSION['acc_type'] == 4) {
            $searchstudents = mysqli_query($dbc, "select * from student where (fname like '%{$_GET['search']}%' or lname like '%{$_GET['search']}%') and form1status > 0 and advisor = '$myuid';");
        }
        else {
		    $searchstudents = mysqli_query($dbc, "select * from student where (fname like '%{$_GET['search']}%' or lname like '%{$_GET['search']}%') and form1status > 0;");
        }
		while ($searchstudent = mysqli_fetch_array($searchstudents)) {
			?>
		  
		  <label><?php echo $searchstudent['fname']; echo ' '; echo $searchstudent['lname'] . ' '; ?></label>
		  <form action='checkform1.php'>
		  <input type="hidden" name="uid" value="<?php echo $searchstudent['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Check Form 1" />
		  </form>
		  <br>
		  <?php
		}
	
	  }
	else {
		if($_SESSION['acc_type'] == 4) { // prints out every advisee with an approved or unapproved form1
			$students = mysqli_query($dbc, "select * from student where form1status > 0 and advisor = '$myuid';");
		}
		else { // prints out every student with an approved or unapproved form1
			$students = mysqli_query($dbc, "select * from student where form1status > 0;");
		}
	while ($student = mysqli_fetch_array($students)) {
		?>
		<div class="row mb-4 justify-content-center text-center">
		<label><?php echo $student['fname']; echo ' '; echo $student['lname'] . ' &nbsp;'; ?></label>
		  <form action='checkform1.php'>
		  <input type="hidden" name="uid" value="<?php echo $student['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Check Form 1" />
		  </form>
		  <br>
		</div>
		<?php
	}
?>
<br>	
<?php
 }
?>
</div>

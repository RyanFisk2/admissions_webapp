
<?php
	// Start the session

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
		if($_SESSION['acc_type'] == 3) {
            $searchstudents = mysqli_query($dbc, "select * from user, student where (fname like '%{$_GET['search']}%' or minit like '%{$_GET['search']}%' or lname like '%{$_GET['search']}%') and user.uid = student.uid and form1status > 0 and student.advisor = '$myuid';");
        }
        else {
		    $searchstudents = mysqli_query($dbc, "select * from user, student where (fname like '%{$_GET['search']}%' or minit like '%{$_GET['search']}%' or lname like '%{$_GET['search']}%') and user.uid = student.uid and form1status > 0;");
        }
		while ($searchstudent = mysqli_fetch_array($searchstudents)) {
			?>
		  
		  <label><?php echo $searchstudent['fname']; echo ' ' . $searchstudent['minit'] . '. '; echo $searchstudent['lname'] . ' '; ?></label>
		  <form action='checkform1.php'>
		  <input type="hidden" name="uid" value="<?php echo $searchstudent['uid']?>" />
		  <input type="submit" class="button" name="decision" value="Check Form 1" />
		  </form>
		  <br>
		  <?php
		}
	
	  }
	else {
		if($_SESSION['acc_type'] == 3) { // prints out every advisee with an approved or unapproved form1
			$students = mysqli_query($dbc, "select * from user, student where user.uid = student.uid and form1status > 0 and student.advisor = '$myuid';");
		}
		else { // prints out every student with an approved or unapproved form1
			$students = mysqli_query($dbc, "select * from user, student where user.uid = student.uid and form1status > 0;");
		}
	while ($student = mysqli_fetch_array($students)) {
		?>
		<div class="row mb-4 justify-content-center text-center">
		<label><?php echo $student['fname']; echo ' ' . $student['minit'] . '. '; echo $student['lname'] . ' &nbsp;'; ?></label>
		  <form action='checkform1.php'>
		  <input type="hidden" name="uid" value="<?php echo $student['uid']?>" />
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

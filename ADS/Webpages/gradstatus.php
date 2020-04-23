<!DOCTYPE html>

<?php

	// Insert the page header
	require_once('header.php');

	require_once('connectvars.php');
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	// Show the navigation menu
	$myuid = $_SESSION['user_id'];

	if (isset($_SESSION['acc_type'])){

?>
<body>
<br><br>
<div class="site-section">
    <div class="container">
		<div class="row mb-4 justify-content-center text-center">
          	<div class="col-lg-4 mb-4">
            	<h2 class="section-title-underline mb-4">
              		<span>Graduation Audit</span>
            	</h2>
          	</div>
        </div>
		<div class="row mb-4 justify-content-center text-center">
			<!-- search bar -->
			<form>
				<input type="search" name="search" placeholder="Search..."/>
				<input type="submit" />
			</form>
			<br>
		</div>
  
<?php

// prints out users that match the search
	  if(isset($_GET['search'])) {
        if($_SESSION['acc_type'] == 4) {
            $searchstudents = mysqli_query($dbc, "select * from student where (fname like '%{$_GET['search']}%' or lname like '%{$_GET['search']}%') and advisor = '$myuid';");
        }
        else if ($_SESSION['acc_type'] < 4){
		    $searchstudents = mysqli_query($dbc, "select * from student where (fname like '%{$_GET['search']}%' lname like '%{$_GET['search']}%');");
        }
        while ($searchstudent = mysqli_fetch_array($searchstudents)) {
			?>
		  <div class="row mb-4 justify-content-center text-center">
		  <label><?php echo $searchstudent['fname'].' '; echo $searchstudent['lname'] . ' &nbsp;&nbsp;'; ?></label>
		  <form action='gradaudit.php'>
		  <input type="hidden" name="uid" value="<?php echo $searchstudent['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Run Audit" />
		  </form>
		  <br>
		  </div>
		  <?php
		}
	
	  }
	else {
    // prints out every student
    if($_SESSION['acc_type'] == 4) {
        $students = mysqli_query($dbc, "select * from student where advisor = '$myuid';");
    }
    else if($_SESSION['acc_type'] < 4){
        $students = mysqli_query($dbc, "select * from student;");
    }
	while ($student = mysqli_fetch_array($students)) {
		?>
		<div class="row mb-4 justify-content-center text-center">
		<label><?php echo $student['fname'].' '; echo $student['lname'] . ' &nbsp;&nbsp;'; ?></label>
		  <form action='gradaudit.php'>
		  <input type="hidden" name="uid" value="<?php echo $student['u_id']?>" />
		  <input type="submit" class="button" name="decision" value="Run Audit" />
		  </form>
		</div>
		<?php
	}
?>
<br>	
<?php
 }
}
?>
</div>

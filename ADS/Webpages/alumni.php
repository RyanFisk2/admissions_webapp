<?php
	// Insert the page header
	require_once('header.php');

	require_once('connectvars.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if(!isset($_SESSION['acc_type'])){
        header('Location:login.php');
    }
?>
<br><br>
<div class="site-section">
    <div class="container">
		<div class="row mb-4 justify-content-center text-center">
          	<div class="col-lg-4 mb-4">
            	<h2 class="section-title-underline mb-4">
              		<span>View Alumni</span>
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

<table class="table">
<tr><th>First Name</th><th>Middle Initial</th><th>Last Name</th><th>Email Address</th><th>Degree</th><th>Graduation Year</th></tr>
<?php
    if(isset($_GET['search'])) {
        $search1 = mysqli_query($dbc, "select * from user, alumni where (gradyear like '%{$_GET['search']}%' or degree like '%{$_GET['search']}%') and user.uid = alumni.uid"); 
    }
    else{
        $search1 = mysqli_query($dbc, "select * from user, alumni where user.uid = alumni.uid");
    }
    while ($search = mysqli_fetch_array($search1)) {
        echo '<tr><td>'.$search['fname'].'</td><td>'.$search['minit'].'</td><td>'.$search['lname'].'</td><td>'.$search['email'].'</td><td>'.$search['degree'].'</td><td>'.$search['gradyear'].'</td></tr>';
    }
?>
</table>
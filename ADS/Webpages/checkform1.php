<?php
	// Insert the page header
	require_once('header.php');

	require_once('connectvars.php');
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	// Show the navigation menu
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
	<?php
    $uid = $_GET['uid'];
    $info = mysqli_query($dbc, "select form1status from student where uid = '$uid';");
    $f1s = mysqli_fetch_array($info);
    if (!empty($f1s)) {
        $form1status = $f1s[0];
    }
    else {
        echo 'Error: Unable to retrieve form 1 status';
    }
	echo '<table class="table">';
	if ($_SESSION['acc_type'] == 3) {
		$query = "SELECT * FROM student s, user a WHERE a.uid = s.uid AND a.uid = '$uid' AND advisor='" . $_SESSION['user_id'] . "'";
	}
	else {
		$query = "SELECT * FROM student s, user a WHERE a.uid = s.uid AND a.uid = '$uid'";
	}
	$data = mysqli_query($dbc, $query);
	$studentinfo = mysqli_fetch_array($data);
	echo '<div class="row mb-4 justify-content-center text-center">';

	echo "User id:".$studentinfo[0]." Name: " . $studentinfo['fname'] . " " . $studentinfo['lname'];
	//echo '<br>';
	echo '</div>';
	$query = "SELECT * FROM form1 f, courses c WHERE c.cno = f.cno AND f.dept = c.dept AND f.uid='" . $studentinfo[0] . "'";
        $data1 = mysqli_query($dbc, $query);
	echo "<br />";
        while($row = mysqli_fetch_array($data1)) {
			  echo "<tr><td>".$row['dept'] . "</td><td>".  $row['cno'] . "</td><td>".  $row['title'] . "</td><td>".  $row['credits'] ."</td></tr>";
        }

if($_SESSION['acc_type'] == 3) {
    if($form1status == 1) {
	?>
	<div class="row mb-4 justify-content-center text-center">
	<form action='approveform1.php'>
	<input type="hidden" name="uid" value="<?php echo $uid?>" />
    <input type="submit" class="button" name="decision" value="Approve" />
	</form>
	<form action='rejectform1.php'>
	<input type="hidden" name="uid" value="<?php echo $uid?>" />
	<input type="submit" class="button" name="decision" value="Reject" />
	</form>
	</div>
  <?php
    }
}
echo '</table>';
?>
</div>
</div>

<!DOCTYPE html>


<?php
	
	require_once('header.php');

	require_once('connectvars.php');

	//echo '<table id="t01" class="center">';
?>
<br><br>
<body>
<div class="site-section">
    <div class="container">
		<div class="row mb-4 justify-content-center text-center">
          	<div class="col-lg-4 mb-4">
            	<h2 class="section-title-underline mb-4">
					<?php if($_SESSION['acc_type'] < 3){ ?>
              		<span>Transcipt</span>
					<?php }else{ ?>
					<span>Transcipts</span>
					<?php } ?>
            	</h2>
          	</div>
		</div>
<?php

	if (isset($_SESSION['user_id'])) {
	if($_SESSION['acc_type'] < 3) {
		
		echo '<table class="table">';
		echo '<tr><th>Department</th><th>Course Number</th><th>Grade</th><th>Semester</th></tr>';
		
		if ($_SESSION['acc_type'] == 1)
			$query = "select gpa FROM student WHERE uid='" . $_SESSION['user_id'] . "'";
		else
			$query = "select gpa FROM alumni WHERE uid='" . $_SESSION['user_id'] . "'";

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $data = mysqli_query($dbc, $query);
		$gpadata = mysqli_fetch_array($data);


		$query = "select fname, lname FROM user WHERE uid='" . $_SESSION['user_id'] . "'";
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                //echo $query;
                $data = mysqli_query($dbc, $query);
                $namedata = mysqli_fetch_array($data);


		$query = "SELECT * FROM transcript t, semester s WHERE t.semesterid = s.semesterid &&t.uid='" . $_SESSION['user_id'] . "'";

                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                //echo $query;
                $data = mysqli_query($dbc, $query);
                //$row = mysqli_fetch_array($data)

				echo '<div class="row mb-4 justify-content-center text-center">';
				echo "User ID: ". $_SESSION['user_id'] . " Name: ". $namedata['fname'] . " ". $namedata['lname'] . " GPA: " . $gpadata['gpa'];
				echo '</div>';
                while($row = mysqli_fetch_array($data)) {
                    echo "<tr><td>".$row['dept'] . "</td><td>".  $row['cno'] . "</td><td>".  $row['grade'] . "</td><td>".  $row['semester'] . " " . $row['year'] ."</td></tr>";        
                }	

		echo '</table>';
	}
	if($_SESSION['acc_type'] == 3){
				?>
				<div class="row mb-4 justify-content-center text-center">
					<form>
						<input type="search" name="search" placeholder="Search..."/>
						<input type="submit" />
					</form>
				</div>
				<?php
				if(isset($_GET['search'])) {
					$query = "SELECT * FROM student s, user a WHERE (fname like '%{$_GET['search']}%' or minit like '%{$_GET['search']}%' or lname like '%{$_GET['search']}%') AND a.uid = s.uid AND advisor='" . $_SESSION['user_id'] . "'";
				}
				else{
					$query = "SELECT * FROM student s, user a WHERE a.uid = s.uid AND advisor='" . $_SESSION['user_id'] . "'";
				}
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
				$data = mysqli_query($dbc, $query);
				$lastid = '0';
                while($uid = mysqli_fetch_array($data)){
					if($lastid != $uid['uid']) {
						$lastid = $uid['uid'];
						//echo '</table>';
						echo '<div class="row mb-4 justify-content-center text-center">';
						echo "User ID:".$uid[0]." Name: " . $uid['fname'] . " " . $uid['lname'] . " GPA:" . $uid['gpa'];
						echo '</div>';
						echo '<table class="table">';
						echo '<tr><th>Department</th><th>Course Number</th><th>Grade</th><th>Semester</th></tr>';
						
					}

                       	$query = "SELECT * FROM transcript t, semester s WHERE t.semesterid = s.semesterid &&t.uid='" . $uid[0] . "'";
						$dbc1 = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                        $data1 = mysqli_query($dbc1, $query);

                        while($row = mysqli_fetch_array($data1)) {
							echo "<tr><td>".$row['dept'] . "</td><td>".  $row['cno'] . "</td><td>".  $row['grade'] . "</td><td>".  $row['semester'] . " ". $row['year'] ."</td></tr>";        
					}
					echo '</table>';
					echo "<br>";
                }

	
	}

	if($_SESSION['acc_type'] > 3){
				?>
				<div class="row mb-4 justify-content-center text-center">
					<form>
						<input type="search" name="search" placeholder="Search..."/>
						<input type="submit" />
					</form>
				</div>
				<?php
				if(isset($_GET['search'])) {
					$query = "SELECT * FROM semester m, transcript t, user a, student s WHERE (fname like '%{$_GET['search']}%' or minit like '%{$_GET['search']}%' or lname like '%{$_GET['search']}%') Having t.semesterid = m.semesterid AND t.uid = a.uid AND a.uid = s.uid ORDER BY t.uid ASC";/*orders it so its somewhat neat also can't see alumni transcripts*/
				}
				else{
					$query = "SELECT * FROM semester m, transcript t, user a, student s Having t.semesterid = m.semesterid AND t.uid = a.uid AND a.uid = s.uid ORDER BY t.uid ASC";/*orders it so its somewhat neat also can't see alumni transcripts*/
				}
                $dbc1 = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                $data1 = mysqli_query($dbc1, $query);
				$lastid = '0';
                while($row = mysqli_fetch_array($data1)) {
			      	if($lastid != $row['uid']) {
			      		$lastid = $row['uid'];
					 	echo '</table>';
						echo '<div class="row mb-4 justify-content-center text-center">';
					 	echo "User ID : $lastid Name: " . $row['fname'] . " " . $row['lname'] . " GPA:" . $row['gpa'] . "<br />";
					 	echo '</div>';
			      	 	echo '<table class="table">';
			      	 	echo '<th>Department</th><th>Course Number</th><th>Grade</th><th>Semester</th></tr>';
			      	}   
			       	echo "<td>".$row['dept'] . "</td><td>".  $row['cno'] . "</td><td>";
			       	echo $row['grade'] . "</td><td>".  $row['semester'] . " ".$row['year'] ."</td></tr>";
                }
        }
	}
	echo '</table>';
?>
</div>
</div>
</body>

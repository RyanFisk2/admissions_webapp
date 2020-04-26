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
					<?php if($_SESSION['acc_type'] > 4){ ?>
              		<span>Transcipt</span>
					<?php }else{ ?>
					<span>Transcipts</span>
					<?php } ?>
            	</h2>
          	</div>
		</div>
<?php

	if (isset($_SESSION['user_id'])) {
	if($_SESSION['acc_type'] > 4) {
		
		echo '<table class="table">';
		echo '<tr><th>Department</th><th>Course Number</th><th>Grade</th><th>Semester</th></tr>';
		
		if ($_SESSION['acc_type'] == 5)
			$query = "select gpa FROM student WHERE u_id='" . $_SESSION['user_id'] . "'";
		else
			$query = "select gpa FROM alumni WHERE a_id='" . $_SESSION['user_id'] . "'";

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $data = mysqli_query($dbc, $query);
		$gpadata = mysqli_fetch_array($data);

		if ($_SESSION['acc_type'] == 5)
			$query = "select fname, lname FROM student WHERE u_id='" . $_SESSION['user_id'] . "'";
		else
			$query = "select fname, lname FROM alumni WHERE a_id='" . $_SESSION['user_id'] . "'";

            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            //echo $query;
            $data = mysqli_query($dbc, $query);
            $namedata = mysqli_fetch_array($data);


		$query = "SELECT * FROM student_transcript t, semester s WHERE t.semesterid = s.semesterid && t.t_id='" . $_SESSION['user_id'] . "' ORDER BY t.semesterid ASC";

                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                $data = mysqli_query($dbc, $query);

				echo '<div class="row mb-4 justify-content-center text-center">';
				echo "User ID: ". $_SESSION['user_id'] . " Name: ". $namedata['fname'] . " ". $namedata['lname'] . " GPA: " . $gpadata['gpa'];
				echo '</div>';
                while($row = mysqli_fetch_array($data)) {
                    echo "<tr><td>".$row['dept'] . "</td><td>".  $row['cno'] . "</td><td>".  $row['grade'] . "</td><td>".  $row['semester'] . " " . $row['year'] ."</td></tr>";        
                }	

		echo '</table>';
	}
	if($_SESSION['acc_type'] == 4){ //New faculty
				?>
				<div class="row mb-4 justify-content-center text-center">
					<form>
						<input type="search" name="search" placeholder="Search..."/>
						<input type="submit" />
					</form>
				</div>
				<?php
				if(isset($_GET['search'])) {
					$query = "SELECT DISTINCT s.u_id, s.fname, s.lname, s.gpa FROM student s, faculty a WHERE (s.fname like '%{$_GET['search']}%' or s.lname like '%{$_GET['search']}%') AND s.advisor='" . $_SESSION['user_id'] . "'";
				}
				else{
					$query = "SELECT DISTINCT s.u_id, s.fname, s.lname, s.gpa FROM student s, faculty a WHERE s.advisor='" . $_SESSION['user_id'] . "'";
				}
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
				$data = mysqli_query($dbc, $query);
				$lastid = '0';
                while($uid = mysqli_fetch_array($data)){
					if($lastid != $uid['u_id']) {
						$lastid = $uid['u_id'];
						//echo '</table>';
						echo '<div class="row mb-4 justify-content-center text-center">';
						echo "User ID:".$uid['u_id']." Name: " . $uid['fname'] . " " . $uid['lname'] . " GPA:" . $uid['gpa'];
						echo '</div>';
						echo '<table class="table">';
						echo '<tr><th>Department</th><th>Course Number</th><th>Grade</th><th>Semester</th></tr>';
						
					}
                       	$query = "SELECT * FROM student_transcript t, semester s WHERE t.semesterid = s.semesterid && t.t_id='" . $uid['u_id'] . "'";
						$dbc1 = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                        $data1 = mysqli_query($dbc1, $query);

                        while($row = mysqli_fetch_array($data1)) {
							echo "<tr><td>".$row['dept'] . "</td><td>".  $row['cno'] . "</td><td>".  $row['grade'] . "</td><td>".  $row['semester'] . " ". $row['year'] ."</td></tr>";        
					}
					echo '</table>';
					echo "<br>";
                }

	
	}

	else if($_SESSION['acc_type'] < 3){ //GS and admin
				?>
				<div class="row mb-4 justify-content-center text-center">
					<form>
						<input type="search" name="search" placeholder="Search..."/>
						<input type="submit" />
					</form>
				</div>
				<?php
				if(isset($_GET['search'])) {
					$query = "SELECT * FROM semester m, student_transcript t, student s WHERE (fname like '%{$_GET['search']}%' or lname like '%{$_GET['search']}%') Having t.semesterid = m.semesterid AND t.t_id = s.u_id ORDER BY t.t_id ASC";/*orders it so its somewhat neat*/
				}
				else{
					$query = "SELECT * FROM semester m, student_transcript t, student s Having t.semesterid = m.semesterid AND t.t_id = s.u_id ORDER BY t.t_id ASC";/*orders it so its somewhat neat also can't see alumni transcripts*/
				}
                $dbc1 = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                $data1 = mysqli_query($dbc1, $query);
				$lastid = '0';
                while($row = mysqli_fetch_array($data1)) {
			      	if($lastid != $row['u_id']) {
			      		$lastid = $row['u_id'];
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

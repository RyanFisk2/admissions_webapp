<!DOCTYPE html>
<html lang="en">

<head>
    <title>Schedule - Farm Fresh Regs</title>
    <?php 
		require_once ('header.php'); 
		session_start ();
		
		if (empty ($_SESSION['id'])) {
			header ('Location: home.php');
		}

		if ($_SESSION['p_level'] == 5) {
			$descrip = " Click on a course's title to see more details or drop the course.";
		} else if ($_SESSION['p_level'] == 4) {
			$descrip = " Click on a course's title to see view enrollment in a course or change student's grades.";
		}
	?>

	<style>

		table {
		table-layout: fixed;
		width: 100%;
		}
		
		td {
		width: 25%;
		}
		.dropbtn:hover, .dropbtn:focus {
		  background-color: #3e8e41;
		}

		#myInput {
		  box-sizing: border-box;
		  background-image: url('searchicon.png');
		  background-position: 14px 12px;
		  background-repeat: no-repeat;
		  font-size: 16px;
		  padding: 14px 20px 12px 45px;
		  border: none;
		  border-bottom: 1px solid #ddd;
		}

		#myInput:focus {outline: 3px solid #ddd;}

		.dropdown {
		  position: relative;
		  display: inline-block;
		}

		.dropdown-content {
		  display: none;
		  position: absolute;
		  background-color: #f6f6f6;
		  min-width: 230px;
		  overflow: auto;
		  border: 1px solid #ddd;
		  z-index: 1;
		}

		.dropdown-content a {
		  color: black;
		  padding: 12px 16px;
		  text-decoration: none;
		  display: block;
		}

		.dropdown a:hover {background-color: #ddd;}

		.show {display: block;}
	</style>
	
</head>

<body  data-spy="scroll" data-target=".site-navbar-target" data-offset="300">


<div class="site-section">
<div class="container">
	
	<div class="row">
		<h1 class="text-primary"> Schedule </h1>
	</div>
	
	<div class="row">
		<h4 class="pl-1 font-weight-lighter"><small> 
			Use the dropdown menu to select a specific semester.
			<?php echo $descrip; ?>
		</small></h4>  
	</div>	

	<div class="row mt-1 pt-2">
		<div class="dropdown">
			<button onclick="myFunction()" class="btn-primary">
				Select Semester
			</button>
			<div id="myDropdown" class="dropdown-content">
				<input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
			
<?php

	// Check which type of user this is
	if ($_SESSION['p_level'] == 5) {
		$id_type = "u_id";
		$courses_type = "taken";
	} else if ($_SESSION['p_level'] == 4){ 
		$id_type = "f_id";
		$courses_type = "taught";
	} else {
		header ('Location: home.php');
	}

	include ('php/connectvars.php');		
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	// Find all unique semesters for this student
	$query = 'SELECT semester, year
			  FROM schedule, semester, courses_' . $courses_type .
			 ' WHERE ' . $id_type . '="'. $_SESSION['id'] .'" and sem=semesterid and schedule.crn=courses_'.$courses_type.'.crn
			  GROUP BY semester, year;';
	$semesters = mysqli_query ($dbc, $query);

	// Print all semesters this student is in to the dropdown menu
	while ($s = mysqli_fetch_array ($semesters)) {
		echo '<a href="schedule.php?semester=' . $s['semester'] . $s['year'] . '">' .
			$s['semester'] . ' ' . $s['year'] .
			'</a>';
	}
	
	echo  '	</div>
		</div>
	</div>';
?>

	<div class="row mt-5">
		<h4> <span class="font-weight-bold"> Current Semester: </span> 

<?php
		
	// If a specific semester is set, add clause to filter it
	if (isset ($_GET['semester'])) {

		// Check if it is fall or spring
		if (strcmp (substr($_GET['semester'], 0, 1), "F") == 0) {
			$s = "Fall";
		} else {
			$s = "Spring";
		}

		$y = substr ($_GET['semester'], -4);

	} else { // Default to the current one
		$today = new DateTime();
	
		// Find which season it is	
		if ($today < new DateTime ('June 1')) {
			$s = "Spring";
		} else if ($today > new DateTime ('August 15')){
			$s = "Fall";	
		} else {
			$s = "Summer";
		}

		$y = date ("Y");
	}

	// Find all classes this student is taking
	if ($_SESSION['p_level'] == 5) {
		$query = 'SELECT semester, year, day, start_time, c_no, fname, lname, title, department, sem 
				  FROM faculty, schedule, courses_taken, catalog, courses_taught, semester
				  WHERE u_id="'. $_SESSION['id'] .'" and courses_taken.crn=schedule.crn 
					and schedule.course_id=catalog.c_id and faculty.f_id=courses_taught.f_id
					and courses_taught.crn=courses_taken.crn and sem=semesterid';
	} else if ($_SESSION['p_level'] == 4) {
		$query = 'SELECT semester, year, day, start_time, c_no, title, department, lname, fname, sem 
				  FROM faculty, schedule, catalog, courses_taught, semester
				  WHERE courses_taught.f_id="'. $_SESSION['id'] .'" 
					and courses_taught.crn=schedule.crn and schedule.course_id=catalog.c_id
					and faculty.f_id=courses_taught.f_id and sem=semesterid';
	}

	// Finish printing the current semester
	echo $s . ' ' , $y . '</h4>
	</div>';
	
	$query = $query . ' and semester="' . $s . '" and year="' . $y . '"';

	// Append a where clause that specifies the start time
	$t1 = $query . ' and start_time="15:00";';
	$t2 = $query . ' and start_time="16:00";';
	$t3 = $query . ' and start_time="18:00";';

	$t1 = mysqli_query ($dbc, $t1);
	$t2 = mysqli_query ($dbc, $t2);
	$t3 = mysqli_query ($dbc, $t3);
	
	$days = array("M", "T", "W", "R", "F");
?>

		<div class="row">
			<table class="table mt-3 mb-5 table-bordered">
				
				<thead>
					<tr class="text-center table-primary">
						<th style="width: 13%"> </th>
						<th class=""> Monday </th>	
						<th class=""> Tuesday </th>
						<th class=""> Wednesday </th>
						<th class=""> Thursday </th>
						<th class=""> Friday </th>
					</tr>
				</thead>

				<tbody>
					<tr class="text-center">
						<th class="table-secondary align-middle"> 3:00-5:30 pm </th>
						
						<?php
							$row = mysqli_fetch_array ($t1);

							// Loop through days of the week
							foreach ($days as $d) {

								echo '<td class="">';

								// If this class meets on this day, print it in the correct spot on schedule
								if ($row != false and strcmp ($row['day'], $d) == 0) { 
									$cno = $row['c_no']; 
									$dept = $row['department']; 
									$sem = $row['sem'];
									if($_SESSION['p_level'] == 5){?>
									<b><a href="course.php?cno=<?php echo $cno ?>&dept=<?php echo $dept ?>&sem=<?php echo $sem ?>"><?php echo $row['department'] . ' ' . $row['c_no'] . '</b></a>: <br>' . 
										$row['title'] . ' ' . '<br>' . 
										'<i> Instructor: ' . $row['fname'] . ' ' . $row['lname'] , '</i>';
									}else if($_SESSION['p_level'] == 4){?>
										<b><a href="grades.php?cno=<?php echo $cno ?>&sem=<?php echo $sem ?>"><?php echo $row['department'] . ' ' . $row['c_no'] . '</b></a>: <br>' . 
										$row['title'] . ' ' . '<br>' . 
										'<i> Instructor: ' . $row['fname'] . ' ' . $row['lname'] , '</i>';
									}
									$row = mysqli_fetch_array ($t1);
								} else { // Keep spacing consistent even if there is no data to print
									echo '<br><br><br>';
								} 

								echo '</td>';
							}
						?>
	
					</tr>
					<tr class="text-center">
						<th class="table-secondary align-middle"> 4:00-6:30 pm </td>
						<?php
							$row = mysqli_fetch_array ($t2);

							// Loop through days of the week
							foreach ($days as $d) {

								echo '<td class="">';

								// If this class meets on this day, print it in the correct spot on schedule
								if ($row != false and strcmp ($row['day'], $d) == 0) { 
									$cno = $row['c_no']; 
									$dept = $row['department'];
									$sem = $row['sem'];
									if($_SESSION['p_level'] == 5){?>
									<b><a href="course.php?cno=<?php echo $cno ?>&dept=<?php echo $dept ?>"><?php echo $row['department'] . ' ' . $row['c_no'] . '</b></a>: <br>' . 
										$row['title'] . ' ' . '<br>' . 
										'<i> Instructor: ' . $row['fname'] . ' ' . $row['lname'] , '</i>';
									}else if($_SESSION['p_level'] == 4){?>
										<b><a href="grades.php?cno=<?php echo $cno ?>&sem=<?php echo $sem ?>"><?php echo $row['department'] . ' ' . $row['c_no'] . '</b></a>: <br>' . 
										$row['title'] . ' ' . '<br>' . 
										'<i> Instructor: ' . $row['fname'] . ' ' . $row['lname'] , '</i>';
									}
									$row = mysqli_fetch_array ($t2);
								} else { // Keep spacing consistent even if there is no data to print
									echo '<br><br><br>';
								}

								echo '</td>';
							}
						?>
					</tr>
					<tr class="text-center">
						<th class="table-secondary align-middle"> 6:00-8:30 pm </td>
						<?php
							$row = mysqli_fetch_array ($t3);

							// Loop through days of the week
							foreach ($days as $d) {

								echo '<td class="">';
								
								// If this class meets on this day, print it in the correct spot on schedule
								if ($row != false and strcmp ($row['day'], $d) == 0) { 
									$cno = $row['c_no']; 
									$dept = $row['department'];
									$sem = $row['sem'];
									if($_SESSION['p_level'] == 5){?>
									<b><a href="course.php?cno=<?php echo $cno ?>&dept=<?php echo $dept ?>&sem=<?php echo $sem ?>"><?php echo $row['department'] . ' ' . $row['c_no'] . '</b></a>: <br>' . 
										$row['title'] . ' ' . '<br>' . 
										'<i> Instructor: ' . $row['fname'] . ' ' . $row['lname'] , '</i>';
									}else if($_SESSION['p_level'] == 4){?>
										<b><a href="grades.php?cno=<?php echo $cno ?>&sem=<?php echo $sem?>"><?php echo $row['department'] . ' ' . $row['c_no'] . '</b></a>: <br>' . 
										$row['title'] . ' ' . '<br>' . 
										'<i> Instructor: ' . $row['fname'] . ' ' . $row['lname'] , '</i>';
									}
									$row = mysqli_fetch_array ($t3);
								} else { // Keep spacing consistent even if there is no data to print
									echo '<br><br><br>';
								}

								echo '</td>';
							}
						?>
					</tr>
				</tbody>
	
			</table>
		</div>
	</div>
	</div>
	
	<script>
      /* When the user clicks on the button,
      toggle between hiding and showing the dropdown content */
      function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
      }

      function filterFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdown");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
          txtValue = a[i].textContent || a[i].innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
          } else {
            a[i].style.display = "none";
          }
        }
      }
    </script>

</body>

</html>

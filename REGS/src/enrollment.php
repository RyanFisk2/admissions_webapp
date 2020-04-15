<!DOCTYPE html>
<html lang="en">

<head>
	<title> Enrollment - Farm Fresh Regs </title>

    <?php
		if (empty($id)) {
			header("Location: login.php");
		}

		if (strcmp ($_SESSION['p_level'], "Student") == 0) {
			header("Location: home.php");
		}	

		if (empty($_GET['cno'])) {
			header("Location: schedule.php");
		}
		
		$query = 'SELECT department, c_no, title FROM catalog WHERE c_no="'. $_GET['cno'] .'"';
		$course = mysqli_query ($dbc, $query);
		$course = mysqli_fetch_array ($course);
		$course = $course['department'] . " " . $course['c_no'] . ": " . $course['title'];

		// Query for all the students in this course
		$query = 'SELECT student.u_id, fname, lname, email, grade, courses_taken.crn
						  FROM student, courses_taken, schedule, catalog
						  WHERE student.u_id=courses_taken.u_id
							and courses_taken.crn=schedule.crn 
							and schedule.course_id=catalog.c_id
							and catalog.c_no="'. $_GET['cno'] .'"';

		$students = mysqli_query ($dbc, $query);

		// Variable to track the last student that's grades were updated
		$last_update = "";

		// Check if grades need to be updated
		while ($s = mysqli_fetch_array ($students)) {
			if (isset ($_POST['U'. $s['u_id']])) {
				$grade = $_POST['U'. $s['u_id']];

				// If this is not a faculty, then grades can be editied infinitely	
				if (strcmp ($_SESSION['p_level'], "Faculty") != 0) {
					$update_query = 'UPDATE courses_taken SET grade="'. $grade .'" 
									 WHERE u_id="'. $s['u_id'] .'" and crn="'. $s['crn'] .'"';
					mysqli_query ($dbc, $update_query);

					// Set the last update so the select menu for the grade can be made 
					// green to show user that the changes have been made
					$last_update = $s['u_id'];
				} 
				// For faculty, only update if the grade hasn't been set
				else if (strcmp ($grade, "IP") != 0) { 
					$update_query = 'UPDATE courses_taken SET grade="'. $grade .'" 
									 WHERE u_id="'. $s['u_id'] .'" and crn="'. $s['crn'] .'"';
					mysqli_query ($dbc, $update_query);
				}
			}
		}
	?>
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

	<div class="container">
            <div class="row mt-5">
				<h2 class="text-primary"> <?php echo $course; ?> </h2>
			</div>
			
            <div class="row">
				<p><strong class="text-black d-block"> 
					To change a student's grade, use the dropdown menu and click the submit button for each student. 
				</strong></p>
			</div>

			<div class="row mt-3">
			<table class="table table-bordered">

				<thead>
					<tr class="text-center table-primary">
						<th scope="col"> U_ID </th>
						<th scope="col"> First Name </th>
						<th scope="col"> Last Name </th>
						<th scope="col"> Email </th>
						<th scope="col"> Grade </th>
						<th scope="col"></th>
					</tr>
				</thead>

				<tbody>

			<?php
				$students = mysqli_query ($dbc, $query);
				while ($students && $s = mysqli_fetch_array ($students)) {
					echo '<tr class="text-center">';

					// Print each field of each student, except for grade
					for ($i = 0; $i < 4; $i++) {
						echo '<td class="align-middle">' . $s[$i] . '</td>';
					}

					// Check if grade has been set OR this is not a faculty	
					if (strcmp ($s['grade'], "IP") == 0 || strcmp ($_SESSION['p_level'], "Faculty") != 0) {
						// Add a dropdown to enter grade 
						echo '<td class="align-middle"> 
								<form action="grades.php?cno='. $_GET['cno'] .'" method="post">';
						
						// If this was the last grade changed, make the select green
						if (strcmp ($s["u_id"], $last_update) == 0) {
							echo '<select class="btn btn-primary" name="U'. $s['u_id'] .'">';
							$last_update = "";
						} else { // Make it the normal, muted grey
							echo '<select class="btn btn-muted" name="U'. $s['u_id'] .'">';
						}

						$grades = array ("IP", "A", "A-", "B+", "B", "B-", "C+", "C", "C-", "D+", "D", "D-", "F");

						foreach ($grades as $g) {
							// If this option is the current grade for student, set it as selected
							if (strcmp ($g, $s['grade']) == 0) {
								echo '<option value="'. $g .'" selected="selected"> '. $g .'</option>';
							} else { // Echo normal option (unselected)
								echo '<option value="'. $g .'"> '. $g .'</option>';
							}
						}
						
						echo '</td> </select>';
						echo '<td> <input type="submit" value="Submit Grade" class="btn btn-danger"> </td>';
						echo '</form>';
					} else { // Grade has already been set, no need for dropdown menu
						echo '<td class="align-middle">' . $s['grade'] . '</td>';
						echo '<td class="align-middle"> <button class="btn btn-muted" disabled> Submit Grade </button> </td>';
					}
					echo '</tr>';
				}
			?>
				</tbody>

			</table>	
			</div>	
	</div>

</body>

</html>



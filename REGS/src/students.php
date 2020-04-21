<?php
require_once ('header.php'); 
session_start();

if (!isset ($_SESSION["id"]) || $_SESSION["p_level"] != 1) {
    header("Location: login.php");
}

include ('php/connectvars.php');		

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check if a student needs to be deleted
if (isset ($_GET['id'])) { 

	// Query to delete from each relation
	$query = 'DELETE FROM courses_taken WHERE u_id="'. $_GET['id'] .'";';
	$query = $query . 'DELETE FROM student WHERE u_id="'. $_GET['id'] .'";';
	$query = $query . 'DELETE FROM users WHERE id="'. $_GET['id'] .'";';

	mysqli_multi_query ($dbc, $query);

	header ('Location: students.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Students - Farm Fresh Regs</title>
</head>

<body>

	<div class="container mt-5 pt-3">
        <h1 class="text-primary"> Students </h1> <br>
		<input class="form-control" id="search_filter" type="text" placeholder="Search...">

			
		<div class="row mt-3">
			<table class="table table-bordered">

				<thead>
					<tr class="text-center table-primary">
						<th scope="col">  U_ID </th>
						<th scope="col"> First Name </th>
						<th scope="col"> Last Name </th>
						<th scope="col"> Email </th>
						<th scope="col"> Address </th>
						<th scope="col"> Major </th>
						<th scope="col"> Program Type </th>
						<th> </th>
					</tr>
				</thead>

				<tbody id="student_table">

			<?php
				$query = 'SELECT u_id, fname, lname, email, addr, major, degree
						  FROM student';
				$students = mysqli_query ($dbc, $query);

				while ($students && $s = mysqli_fetch_assoc ($students)) {
					echo '<tr class="text-center">';

					// Print each field of each student
					foreach ($s as $data) {
						echo '<td class="align-middle">' . $data . '</td>';
					}

					// Add the edit button
					echo '<td class="align-middle">';
					echo '<a href="students.php?id='. $s['u_id'] .'" class="btn btn-primary"> Delete </a>';
					echo '</td> </tr>';
				}
			?>
				</tbody>

			</table>
		</div>		

	</div>	

</body>

<script>
    $(document).ready(function(){
    $("#search_filter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#student_table tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });
</script>

</html>

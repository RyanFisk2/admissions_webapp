<?php
require_once ('header.php');
session_start();

if (!isset ($_SESSION["id"]) || $_SESSION["p_level"] != 1) {
    header("Location: login.php");
}

include ('php/connectvars.php');		

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check if a faculty member needs to be deleted
if (isset ($_GET['id'])) { 

	// Query to delete from each relation
	$query = 'DELETE FROM courses_taught WHERE f_id="'. $_GET['id'] .'";';
	$query = $query . 'DELETE FROM faculty WHERE f_id="'. $_GET['id'] .'";';
	$query = $query . 'DELETE FROM users WHERE id="'. $_GET['id'] .'";';

	mysqli_multi_query ($dbc, $query);

	header ('Location: faculty.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Faculty - Farm Fresh Regs</title>
</head>

<body>

	<div class="container mt-5 pt-3">
        <h1 class="text-primary"> Faculty </h1> <br>
		<input class="form-control" id="search_filter" type="text" placeholder="Search...">

			
		<div class="row mt-3">
			<table class="table table-bordered">

				<thead>
					<tr class="text-center table-primary">
						<th scope="col"> F_ID </th>
						<th scope="col"> First Name </th>
						<th scope="col"> Last Name </th>
						<th scope="col"> Email </th>
						<th scope="col"> Address </th>
						<th scope="col"> Department </th>
						<th> </th>
					</tr>
				</thead>

				<tbody id="faculty_table">

			<?php
				$query = 'SELECT f_id, fname, lname, email, addr, dept
						  FROM faculty';
				$faculty = mysqli_query ($dbc, $query);

				// Print each field of each faculty
				while ($f = mysqli_fetch_assoc ($faculty)) {
					echo '<tr class="text-center">';
				
					foreach ($f as $data) {
						echo '<td class="align-middle">' . $data . '</td>';
					}

					// Add the edit button
					echo '<td class="align-middle">';
					echo '<a href="faculty.php?id='. $f['f_id'] .'" class="btn btn-primary"> Delete </a>';
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
        $("#faculty_table tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });
</script>

</html>

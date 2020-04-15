<?php
require_once ('header.php'); 
session_start();

if (!isset ($_SESSION["id"]) || strcmp ($_SESSION["p_level"], "Admin") != 0) {
    header("Location: login.php");
}

include ('php/connectvars.php');		

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check if a student needs to be deleted
if (isset ($_GET['id'])) { 

	// Query to delete from each relation
	$query = 'DELETE FROM courses_taken WHERE u_id="'. $_GET['id'] .'";';
	$query = $query . 'DELETE FROM courses_taught WHERE f_id="'. $_GET['id'] .'";';
	$query = $query . 'DELETE FROM student WHERE u_id="'. $_GET['id'] .'";';
	$query = $query . 'DELETE FROM faculty WHERE f_id="'. $_GET['id'] .'";';
	$query = $query . 'DELETE FROM users WHERE id="'. $_GET['id'] .'";';

	mysqli_multi_query ($dbc, $query);

	header ('Location: users.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title> Users - Farm Fresh Regs</title>
</head>

<body>

	<div class="container mt-5">
		<h1 class="text-primary"> Users </h1> <br>
		
		<input class="form-control" id="search_filter" type="text" placeholder="Search...">

		<div class="row mt-3">
			<table class="table table-bordered">

				<thead>
					<tr class="text-center table-primary">
						<th scope="col"> ID </th>
						<th scope="col"> Type </th>
						<th> </th>
					</tr>
				</thead>

				<tbody id="users_table">

			<?php
				$query = 'SELECT id, p_level
						  FROM users';
				$users = mysqli_query ($dbc, $query);

				while ($users && $u = mysqli_fetch_assoc ($users)) {
					echo '<tr class="text-center">';

					// Print each field of each user
					foreach ($u as $data) {
						echo '<td class="align-middle">' . $data . '</td>';
					}

					// Add the delete button
					echo '<td class="align-middle">';
					
					// Only allow a working delete button if this isn't the admin
					if (strcmp ($u['p_level'], "Admin") == 0) {
						echo '<button class="btn btn-secondary" disabled> Delete </button>';
					} else {
						echo '<a href="users.php?id='. $u['id'] .'" class="btn btn-primary"> Delete </a>';
					}

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
        $("#users_table tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });
</script>

</html>

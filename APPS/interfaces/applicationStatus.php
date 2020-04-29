<?php
	require_once('../includes/connectvars.php');	
	require_once('../appvars.php');
	require_once('../includes/utils.php');

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT decisions.description AS description, application_form.submitted AS submitted FROM application_form ";
	$query .= "JOIN decisions ON decisions.decisionID = application_form.decision ";
	$query .= "WHERE application_form.applicationID = " . $_SESSION["applicationID"];
	$data = try_query($dbc, $query, NULL);
	$status = mysqli_fetch_array($data);
?>

<table class="table">
	<thead>
		<tr>
			<th scope="col">Application ID</th>
			<th scope="col">Submitted</th>
			<th scope="col">Status</th>
		</tr>
	</thead>
	<tbody>
			<tr>
				<td><?= $_SESSION["applicationID"] ?></td>
				<td><?= $status["submitted"] ? '<i class="fas fa-check" style="color: green"></i>' : '<i class="fas fa-times" style="color: red"></i>' ?></td>
				<td><?= $status["description"] ?></td>
			</tr>
	</tbody>
</table>

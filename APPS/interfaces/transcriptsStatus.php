<?php
	require_once('../includes/connectvars.php');	
	require_once('../appvars.php');
	require_once('../includes/utils.php');

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT pathToFile, received FROM transcript ";
	$query .= "WHERE applicationID = " . $_SESSION["applicationID"];
	$data = try_query($dbc, $query, NULL);
?>

<table class="table">
	<thead>
		<tr>
			<th scope="col">Date Received</th>
			<th scope="col">File</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach ($data as $row) : 
		?>
			<tr>
				<td><?= $row["received"] ?></td>
				<td><?= $row["pathToFile"] ?> - <a href='<?= DATA_PATH . "/transcripts/" . $row["pathToFile"] ?>' target="_blank">view file</a></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php
	require_once('../includes/connectvars.php');	
	require_once('../includes/utils.php');

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$keys = array("writerName", "writerEmail", "writerTitle", "writerEmployer");

	$query = "SELECT writerName, writerEmail, received FROM rec_letter ";
	$query .= "WHERE applicationID = " . $_SESSION["applicationID"];
	$data = try_query($dbc, $query, NULL);


	// to test, manually insert something liek this:
	// UPDATE rec_letter SET received = '2020-03-21' WHERE letterID = 1;
?>

<table class="table">
	<thead>
		<tr>
			<th scope="col">Recommender</th>
			<th scope="col">Email</th>
			<th scope="col">Received</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach ($data as $row) : 
		?>
			<tr>
				<td><?= $row["writerName"] ?></td>
				<td><?= $row["writerEmail"] ?></td>
				<td><?= is_null($row["received"]) ? "Not received" : $row["received"] ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php 
	// A page for the RW to submit a recommendation / view a submitted recommendation.
	require_once('includes/utils.php');
	require_once('appvars.php');
	require_once('includes/header.php');

	session_destroy();
	session_start();

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	if (!isset($_GET["id"])) :
		echo 'Page not valid. To submit a recommendation letter, use the link in your email.';
	else :
		$_SESSION["letterID"] = $_GET["id"];
		$query = "SELECT writerName, writerEmail, writerTitle, writerEmployer, received FROM rec_letter ";
		$query .= "WHERE letterID = " . $_GET["id"];
		$data = try_query($dbc, $query, NULL);
		$row = mysqli_fetch_array($data);

		if (is_null($row)):
			echo "No letter matching this ID found, check your link again.";
		else:
?>

<body>

<div id="content">


<br><br>

<div class="row">
<div class="col-xl-9 mx-auto">
<h4>Recommendation Letter Submission</h1>
<form id="info">

	<?php
		if (isset($row["received"])) {
			echo '<p>Your recommendation letter was received on ' . $row["received"] . '. Thank you!</p>';
		}
		else {
			echo '<p>Please submit your recommendation below. If you are not the person whose name is listed here, please do not fill out this form.';
		}
	?>

	<hr>
	<div class="form-row">
		<div class="form-group col-sm-3">
	        <label for="name">Name</label>
			<input type="text" class="form-control" name="name" value='<?= $row["writerName"]?>' disabled>
		</div>
		<div class="form-group col-sm-3">
	        <label for="email">Email</label>
			<input type="text" class="form-control" name="email" value='<?= $row["writerEmail"]?>' disabled>
		</div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-4">
			<label for="writerTitle">Title</label>
			<input type="text" class="form-control" name="writerTitle" value='<?= $row["writerTitle"]?>' disabled>
		</div>
		<div class="form-group col-md-4">
			<label for="writerEmployer">Employer</label>
			<input type="text" class="form-control" name="writerEmployer" value='<?= $row["writerEmployer"]?>' disabled>
		</div>
	</div>
</form>
<form id="recommendation">
	<div class="form-group">
		<label for="letter">Recommendation Letter</label>
		<textarea class="form-control" rows="5" name="letter"></textarea>
	</div>
	<?php if (!isset($row["received"])) : ?>
	<button type="submit">Submit</button>
	<?php endif ; ?>
</form>
</div>

</div>

</div>

</body>

<?php 
	require_once('includes/footer.php'); 
?>

<script>
	$(document).ready(function() {
		// initialize UI
		autoFill('interfaces/submitRecommendation.php', 'recommendation');
		initValidators();
		console.log("handling");
		$("#recommendation").validate({
			rules: {
				letter: {
					required: true,
					longform: true
				}
			},
			submitHandler: function(form) {
				submitForm('interfaces/submitRecommendation.php', 'recommendation');
				// reload();
				return false;
			}
		});
	})
</script>

<?php
		endif;
	endif;
?>


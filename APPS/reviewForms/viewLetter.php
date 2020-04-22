<!DOCTYPE HTML>

<body>
	<?php
		require_once('../includes/connectvars.php');
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$letterID = $_GET['letterID'];

		$contentQuery = "SELECT letter FROM rec_letter WHERE letterID = '$letterID'";
		$contentResult = mysqli_query($dbc, $contentQuery);
		$content = mysqli_fetch_array($contentResult);
		$letter = $content['letter'];

		echo $letter;
		echo "<br/>";

	?>

		<button onclick="closeWindow()">Close</button><br/>

		<script>
			function closeWindow() {
				window.close();
			}
		</script>



</body>

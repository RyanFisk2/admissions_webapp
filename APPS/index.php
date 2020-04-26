<?php
	if (!isset($_SESSION)) {
		session_start();
		$_SESSION["created"] = True;
	}
	require_once('appvars.php');
	require_once('includes/utils.php');
	require_once('includes/header.php');
	//require_once('includes/connectvars.php');

	# temporary automatic sign in
	// $_SESSION["userID"] = 44444444;
?>

<body>  
<style>
	.content { margin: 200px; }
</style>

	<div id="content">
	<?php
		require_once('home.php');	    		
	    ?>  
	</div>

	<!-- load the correct form based on the button clicked by the user -->
	<script>
		function loadForm (url){
			// Using simpler jQuery ajax method for loading pages
			loadFormToContainer(url, $("#content"));
		}

		function loadFormToContainer(url, container) {
			container.load(url, function(responseText, statusText, jqXHR) {
				console.log(statusText, "loading", url);
			})
		}

		function viewLetter(letterID) {
                                window.open("./reviewForms/viewLetter.php?letterID="+letterID, resizeable=true, width=200, height=200);
                }
		
		
	</script>

</body>

<?php require_once('includes/footer.php'); ?>

</html>

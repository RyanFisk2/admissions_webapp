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

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">  
<style>
	.content { margin: 200px; }
</style>
<br/>
<br/>
<br/>
<br/>
<br/>
	<div id="content">
	<?php
		if($_SESSION['acc_type'] == 7){

			?>
			<div class="site-section">
			<div class="container">
			<div class="feature-1 border">

				<div class="icon-wrapper bg-primary">
					<span class="flaticon-books-1 text-white"></span>
				</div>

				<div class="feature-1-content">
					<h2>My Application</h2>
					<p>Continue your application</p>
					<p><button class="btn btn-primary px-4 py-2 rounded-0" onclick="loadForm('application.php')">My application</button></p>
				</div>
			</div>
			</div>
			</div>
		<?php
		}else if ($_SESSION['acc_type'] < 5) {

			?>
			<div class="site-section">
			<div class="feature-1 border">

				<div class="icon-wrapper bg-primary">
					<span class="flaticon-books-1 text-white"></span>
				</div>

				<div class="feature-1-content">
					<h2>Review Applicants</h2>
					<p>Review Current Applicants and Submit Review</p>
					<p><button class="btn btn-primary px-4 py-2 rounded-0" onclick="loadForm('applicants.php')">Review Applicants</button></p>
				</div>
			</div>
			</div>
		<?php
		}
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

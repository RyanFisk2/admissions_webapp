<!-- Navigation -->
<nav class="navbar navbar-light bg-light static-top">
	<div class="container" id="navContainer">
	<?php
	if (empty($_SESSION['role'])) {	//no one log in
	?>		
		<a class="navbar-brand" href="#">Menu</a>
		<button class="btn btn-primary" id="home"  onclick="document.location.reload()">Home</button>
		<button class="btn btn-primary" id="login" onclick="loadForm('./includes/login.php')">Sign In</button>
	<?php
	} else if ($_SESSION['role'] == 1) {//applicants login	
	?>	
		<a class="navbar-brand" href="#">Menu</a>
		<button class="btn btn-primary" id="home"  onclick="document.location.reload()">Home</button>
		<button class="btn btn-primary" id="appForm" onclick="loadForm('application.php')">My Application</button>
		<button class="btn btn-primary" id="logout" onclick="loadForm('./includes/logout.php')">Sign Out</button>
	<?php
	} else if ($_SESSION['role'] == 2 || $_SESSION['role'] == 3 || $_SESSION['role'] == 4) {	//gs log in	
	?>	
		<a class="navbar-brand" href="#">Menu</a>
		<button class="btn btn-primary" id="home"  onclick="document.location.reload()">Home</button>
		<button class="btn btn-primary" id="revForm" onclick="loadForm('applicants.php')">Review Applications</button>
		<button class="btn btn-primary" id="logout" onclick="loadForm('./includes/logout.php')">Sign Out</button>
	<?php
	} else { //admin log in 
	?>
		<a class="navbar-brand" href="#">Menu</a>
		<button class="btn btn-primary" id="home"  onclick="document.location.reload()">Home</button>
		<button class="btn btn-primary" id="revForm" onclick="loadForm('applicants.php')">Review Applications</button>
		<button class="btn btn-primary" id="home"  onclick="loadForm('./includes/create.php')">Create an Account</button>
		<button class="btn btn-primary" id="home"  onclick="loadForm('./includes/update.php')">Update Information</button>
		<button class="btn btn-primary" id="logout" onclick="loadForm('./includes/logout.php')">Sign Out</button>
	<?php
	}  
	?>
	<?php if (ENV == 'development') : ?>
	<button class="btn btn-secondary" id="reset" onclick="$.get('./interfaces/reset.php', function(response) {console.log(response);})">Reset Database</button>
	</div>	
	<?php endif; ?>
</nav>

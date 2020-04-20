<?php
	require_once('header.php');
	echo '<head>';
	echo '<title>Assign fauluty advisors</title>';
	require_once('connectvars.php');
	


	if(isset($_SESSION['acc_type']) && $_SESSION['acc_type'] == 4) {

		if ($_SERVER["REQUEST_METHOD"] == "POST"){ 

			$studid = $_POST['studid'];
			$advidid = $_POST['advidid'];

			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
				

			$query = "update student set advisor = $advidid where uid = $studid;";
        	$data = mysqli_query($dbc, $query);               
		}
	}

   
?>
<br><br>
	<div class="site-section">
      			<div class="container">
					<div class="row mb-4 justify-content-center text-center">
          				<div class="col-lg-4 mb-4">
            				<h2 class="section-title-underline mb-4">
              				<span>Assign Adivsors</span>
            				</h2>
          				</div>
					</div>
					<div class="row mb-4 justify-content-center text-center">


 <form action="assignadvisor.php" method="post">
   <label for="studid">Student ID:</label>
   <input type="number" id="studid" name="studid" required>
   <label for="advidid">Advisor ID:</label>
   <input type="number" id="advidid" name="advidid" required><br>
   </br><input type="submit" class="button" name ="assign" value="Assign">
 </form>


<?php

	$pg_title = "Create New User";
  	require_once('header.php');
	$degreeErr = "";


	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		// Connect to the database
		require_once('connectvars.php');
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$studid = $_POST['studid'];
		$pass = $_POST['password'];
		$fname = $_POST['fname'];
		$minit = $_POST['minit'];
		$lname = $_POST['lname'];
		$dob = $_POST['dob'];
		$address = $_POST['address'];
		$email = $_POST['email'];
		$acctype = $_POST['acctype'];
		$degree = $_POST['degree'];
		
		$gpa = $_POST['gpa'];
		$gradyear = $_POST['gradyear'];


		if($acctype < 3 && empty($degree)  && !(strcmp($degree, 'MS') == 0 || strcmp($degree, 'PhD') == 0)){
				echo '<p class="error"><font color="red"> Need a degree when creating a student or alumni</font></p>';
			
		}

		else if($acctype == 2 && (empty($gradyear) || empty($gpa))){
                                echo '<p class="error"><font color="red"> Need a Graduation year and gpa when creating an alumni</font></p>';

                }



                else if(isset($_SESSION['acc_type']) &&  $_SESSION['acc_type'] > 4 && !empty($fname)){//checks permissions and if the form was entered
                        $query = "insert into user values ($studid, '$pass', '$fname', '$minit', '$lname', '$email', '$dob', '$address', '$acctype');";
                        $data = mysqli_query($dbc, $query);


                        if($acctype == 1) {
                                $query = "insert into student (uid, degree) values ('$studid', '$degree')";
                                $data = mysqli_query($dbc, $query);
                        }


                        if($acctype == 2){
 
                                $query = "insert into alumni values ($studid, '$degree', '$gpa', '$gradyear');";
                                $data = mysqli_query($dbc, $query);
                        
                        }



                        //echo $query;

                }


        }

 ?>
 <br><br>
<div class="site-section">
        <div class="container">
		<div class="row mb-4 justify-content-center text-center">
          	        <div class="col-lg-4 mb-4">
            			<h2 class="section-title-underline mb-4">
              			        <span>Create User</span>
            			</h2>
          		</div>
        	</div>


<form action="createnewuser.php" method="post" class="form">
   
<div class="row mb-4 justify-content-center text-center">

   <label for="studid">Student ID &nbsp</label>
   <input type="number" id="studid" name="studid" required>
   <?php echo '&nbsp;'; ?>
   <label for="password">Password &nbsp</label>
   <input type="text"  id="password" name="password" required>
     
</div>
        
<div class="row mb-4 justify-content-center text-center">

   <label for="fname">First Name &nbsp</label>
   <input type="text"  id="fname" name="fname" required>
   <?php echo '&nbsp;'; ?>      
   <label for="minit">Middle Intial &nbsp</label>
   <input type="text"  id="minit" name="minit">

</div>

<div class="row mb-4 justify-content-center text-center">

   <label for="lname">Last Name &nbsp</label>
   <input type="text" id="lname" name="lname" required>
   <?php echo '&nbsp;'; ?>      
   <label for="dob">Date of Birth &nbsp</label>
   <input type="date" id="dob" name="dob">
	
</div>  

<div class="row mb-4 justify-content-center text-center">

   <label for="address">Address &nbsp</label>
   <input type="text" id="address" name="address" required>
   <?php echo '&nbsp;'; ?>        
   <label for="email">Email &nbsp</label>
   <input type="email" id="email" name="email" required>

</div>

<div class="row mb-4 justify-content-center text-center">
   
   <label for="acctype">Account type &nbsp</label>
   <input type="number" id="acctype" name="acctype" min = "1" max = "5" required>	 
   <?php echo '&nbsp;'; ?>        
   <label for="degree">Degree &nbsp</label>
   <input type="text" id="degree" name="degree">

</div>
	 
<div class="row mb-4 justify-content-center text-center">

 <label for="gpa">GPA &nbsp</label>
 <input type="number" id="gpa" name="gpa" min = "0" max="4" step = ".01">	
 <?php echo '&nbsp;'; ?>        
 <label for="gradyear">Graduation Year &nbsp</label>
 <input type="text" id="gradyear" name="gradyear"></tr><br>

 </div>

 <div class="row mb-4 justify-content-center text-center">
 <input type="submit" name ="create" value="Create">
 </div>

 </form>
</table>
</div>




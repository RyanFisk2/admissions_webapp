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
		$lname = $_POST['lname'];
		$dob = $_POST['dob'];
		$address = $_POST['address'];
		$email = $_POST['email'];
		$acctype = $_POST['acctype'];
      $degree = $_POST['degree'];
      $major = $_POST['major'];
		
		$gpa = $_POST['gpa'];
		$gradyear = $_POST['gradyear'];


		if($acctype > 4 && empty($degree)){
			echo '<br><br><p class="error"><font color="red"> Need a degree when creating a student or alumni</font></p>';
      }
      else if ($acctype > 4 && empty($major)){
         echo '<p class="error"><font color="red"> Need a major when creating a student or alumni</font></p>';
      }

		else if($acctype == 6 && (empty($gradyear) || empty($gpa))){
         echo '<p class="error"><font color="red"> Need a Graduation year and gpa when creating an alumni</font></p>';
      }

      else if(isset($_SESSION['acc_type']) &&  $_SESSION['acc_type'] < 2 && !empty($fname)){//checks permissions and if the form was entered
         $query = "insert into users (id, password, p_level) values ('$studid', '$pass', '$acctype');";
         $data = mysqli_query($dbc, $query);
         if($acctype == 5) {
            $query = "insert into student (u_id, fname, lname, addr, email, degree, major, gradapp, form1status, gpa) values ('$studid', '$fname', '$lname', '$address', '$email', '$degree', '$major', 0, 0, '0.00');";
            $data = mysqli_query($dbc, $query);
         }
         else if($acctype == 6){
            $query = "insert into alumni (a_id, fname, lname, degree, gpa, email, gradyear, addr) values ($studid, '$fname', '$lname', '$degree', '$gpa', '$email', '$gradyear', '$address');";
            $data = mysqli_query($dbc, $query);            
         }
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
   <label for="email">Email &nbsp</label>
   <input type="email" id="email" name="email" required>

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
   <?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>        
   <label for="acctype">Account type &nbsp</label>
   <input type="number" id="acctype" name="acctype" min = "1" max = "7" required>	

</div>

<div class="row mb-4 justify-content-center text-center">
    
   <label for="major">Major &nbsp</label>
   <input type="text" id="major" name="major">	
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

<p>
   <span>*User levels 1, 2, 3, 4, 5, 6, 7 correspond to Admin, Graduation Secretary, Chair, Faculty, Student, Alumni, and Applicant, respectively.</span>
</p>

</div>




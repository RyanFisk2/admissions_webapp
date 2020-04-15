<!DOCTYPE html>
<html lang="en">

<style>
    .not-nav {
        margin-top: 3%; 
    }
</style>

<head>
  <?php require_once ('header.php'); ?>  

  <title> Home - Farm Fresh Regs </title>
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
	
<?php
	  session_start();

      if (!isset($_SESSION['id'])){ // If user is not logged in redirect to login
          header("Location: login.php");
      }
  
      $permLevel = $_SESSION['p_level'];

      if (strcmp($permLevel, "Student") == 0) {
?>
    <div class="site-section">
      <div class="container">
        <div class="row mb-4 justify-content-center text-center">
          <div class="col-lg-4 mb-4">
            <h2 class="section-title-underline mb-4">
              <span>Home</span>
            </h2>
          </div>
        </div>
		<div class="row">
          <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-mortarboard text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>View Schedule</h2>
                <p>Check on your current and past schedules.</p>
                <p><a href="schedule.php" class="btn btn-primary px-4 rounded-0">View Schedules</a></p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-school-material text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>View Transcript</h2>
                <p>View the most updated version of your unofficial transcript.</p>
                <p><a href="transcript.php" class="btn btn-primary px-4 rounded-0">View Transcript</a></p>
              </div>
            </div> 
          </div>
          <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-library text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Courses</h2>
                <p>View avaiable courses and enroll.<br></br></p>
                <p><a href="courses.php" class="btn btn-primary px-4 rounded-0">Register</a></p>
              </div>
            </div> 
          </div>
    </div>
    <br></br>
    <br />
    <div class="row mb-5 justify-content-center text-center">
          <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-mortarboard text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Account Information</h2>
                <p>View and edit your personal account information.</p>
                <p><a href="account.php" class="btn btn-primary px-4 rounded-0">View Accout</a></p>
              </div>
            </div>
          </div>
        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-mortarboard text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Change Password</h2>
                <p>Change Your Password.<br></br></p>
                <p><a href="change_password.php" class="btn btn-primary px-4 rounded-0">Change Password</a></p>
              </div>
            </div>
          </div>
    </div>  
<?php
} else if (strcmp($permLevel, "Faculty") == 0) {
?>
	<div></div>
  <br></br>
    <div class="site-section">
      <div class="container">
        <div class="row mb-5 justify-content-center text-center">
          <div class="col-lg-4 mb-5">
            <h2 class="section-title-underline mb-5">
              <span>Home</span>
            </h2>
          </div>
        </div>
		<div class="row">
          <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-school-material text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>View Your Courses</h2>
                <p>View all courses you are currently teaching.<br></br></p>
                <p><a href="schedule.php" class="btn btn-primary px-4 rounded-0">View Courses</a></p>
              </div>
            </div> 
          </div>
          <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-library text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Input Student Grades</h2>
                <p>Enter grades for the students taking your courses.</p>
                <p><a href="grades.php" class="btn btn-primary px-4 rounded-0">Input Grades</a></p>
              </div>
            </div> 
          </div>
          <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-mortarboard text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Account Information</h2>
                <p>View and edit your personal account information.</p>
                <p><a href="account.php" class="btn btn-primary px-4 rounded-0">View Account</a></p>
              </div>
            </div>
          </div>
        <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-mortarboard text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Change Password</h2>
                <p>Change Your Password.<br></br><br /></p>
                <p><a href="change_password.php" class="btn btn-primary px-4 rounded-0">Change Password</a></p>
              </div>
            </div>
          </div>
    </div>    
<?php
} else if (strcmp($permLevel, "Admin") == 0) {
?>
	<div></div>
  <br></br>
    <div class="site-section">
      <div class="container">
        <div class="row mb-5 justify-content-center text-center">
          <div class="col-lg-4 mb-5">
            <h2 class="section-title-underline mb-5">
              <span>Home</span>
            </h2>
          </div>
        </div>
		<div class="row">
		  <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-mortarboard text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Create Users</h2>
                <p>Create a new user.<br></br></p>
                <p><a href="add_user.php" class="btn btn-primary px-4 rounded-0">Create User</a></p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-school-material text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Courses</h2>
                <p>View the full list of courses offered by the university.</p>
                <p><a href="courses.php" class="btn btn-primary px-4 rounded-0">View Courses</a></p>
              </div>
            </div> 
          </div>
          <div class="col-lg-3 col-md-6 mb-4 mb-lg-2">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-library text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Students</h2>
                <p>View the current list of students at the university.</p>
                <p><a href="students.php" class="btn btn-primary px-4 rounded-0">View Students</a></p>
              </div>
            </div> 
          </div>
          <div class="col-lg-3 col-md-6 mb-4 mb-lg-2">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-mortarboard text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Faculty</h2>
                <p>View the current list of faculty at the university.</p>
                <p><a href="faculty.php" class="btn btn-primary px-4 rounded-0">View Faculty</a></p>
              </div>
            </div>
          </div>
        </div>
    <br></br>
    <br />
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-mortarboard text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Change Grades</h2>
                <p>Change a Student's Grades</p>
                <p><a href="grades.php" class="btn btn-primary px-4 rounded-0">Change Grades</a></p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-school-material text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Transcripts</h2>
                <p>View Any Student's Transcript.</p>
                <p><a href="transcript.php" class="btn btn-primary px-4 rounded-0">Transcripts</a></p>
              </div>
            </div> 
          </div>
          <div class="col-lg-3 col-md-6 mb-4 mb-lg-2">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-library text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Edit Users</h2>
                <p>Edit a Users's Personal Information.</p>
                <p><a href="edit_user.php" class="btn btn-primary px-4 rounded-0">Edit Users</a></p>
              </div>
            </div> 
          </div>
          <div class="col-lg-3 col-md-6 mb-4 mb-lg-2">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-library text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>View Users</h2>
                <p>View and Delete all Users in the Database.</p>
                <p><a href="users.php" class="btn btn-primary px-4 rounded-0">View Users</a></p>
              </div>
            </div> 
          </div>

        </div>
<?php
} else if (strcmp($permLevel, "GS") == 0) {
?>
	<div></div>
  <br></br>
    <div class="site-section">
      <div class="container">
        <div class="row mb-5 justify-content-center text-center">
          <div class="col-lg-4 mb-5">
            <h2 class="section-title-underline mb-5">
              <span>Home</span>
            </h2>
          </div>
        </div>
		<div class="row mb-5 justify-content-center text-center">
		  <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-mortarboard text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Search Student Transcripts</h2>
                <p>Search the university database for a specific student's transcript.</p>
                <p><a href="transcript.php" class="btn btn-primary px-4 rounded-0">Search Transcripts</a></p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            <div class="feature-1 border">
              <div class="icon-wrapper bg-primary">
                <span class="flaticon-school-material text-white"></span>
              </div>
              <div class="feature-1-content">
                <h2>Change Grades</h2>
                <p>View courses and alter student's grades.</p>
                <p><a href="grades.php" class="btn btn-primary px-4 rounded-0">Change Grades</a></p>
              </div>
            </div> 
          </div>
        </div>
<?php
}
?>
      </div>
    </div>
	
	<div class="container">
		<div class="row justify-content-center mt-3 mb-3">
			<a href="reset.php" class="btn btn-primary"> RESET DATABASE </a>
		</div>
	</div>

</body>
</html>

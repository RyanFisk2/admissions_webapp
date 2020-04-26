<!DOCTYPE html>
<html lang="en">

<style>

  .stick-wrapper {
    position: fixed;

  }

</style>

<head>
  <!-- Set the icon in title bar to the logo -->
  <link rel="icon" href="images/icon.svg">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">

  <link rel="stylesheet" href="css/jquery.fancybox.min.css">

  <link rel="stylesheet" href="css/bootstrap-datepicker.css">

  <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

  <link rel="stylesheet" href="css/aos.css">
  <link href="css/jquery.mb.YTPlayer.min.css" media="all" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="css/style.css">

<!-- </head> 

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300"> -->

  <div class="site-wrap">

    <header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">

      <div class="container">
        <div class="d-flex align-items-center">
          <div class="mx-auto">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                <li>
                  <div class="site-logo">
                  <a href="home.php" class="d-block">
                    <img src="images/logo.png" alt="Image" class="img-fluid">
                  </a>
                  </div>
                </li>
                <li>
                  <a href="index.php" class="nav-link text-left">Home</a>
                </li>
			<?php

				if(!(isset($_SESSION))){
					session_start ();
				}
	
				// Switch over perm level to figure out what to display in header	
				if (isset ($_SESSION['p_level'])) {
					switch ($_SESSION['p_level']) {
						case 7:
							echo "<button class='small btn btn-primary px-4 py-2 rounded-0' id='appForm' onclick='loadForm(\"application.php\")'>My Application</button";
							echo "  ";
							break;

						case 4:
							/*echo '
								<li>
								  <a href="account.php" class="nav-link text-left">Account Info</a>
								</li>
								<li>
								  <a href="schedule.php" class="nav-link text-left">Schedule</a>
								</li>
								<li>
								  <a href="grades.php" class="nav-link text-left">Grades</a>
								</li>
							';*/
							echo"<button class='small btn btn-primary px-4 py-2 rounded-0' onclick='loadForm(\"applicants.php\")'>Review Applicants</button>";
							break;
						case 5:
							echo '	
								<li>
								  <a href="account.php" class="nav-link text-left">Account Info</a>
								</li>
								<li>
								  <a href="schedule.php" class="nav-link text-left">Schedule</a>
								</li>
								<li>
								  <a href="courses.php" class="nav-link text-left">Registration</a>
								</li>
								<li>
								  <a href="transcript.php" class="nav-link text-left">Transcript</a>
								</li>			
							';
							break;
						case 2:
							echo '	
								<li>
								  <a href="grades.php" class="nav-link text-left">Grades</a>
								</li>
								<li>
								  <a href="transcript.php" class="nav-link text-left">Transcripts</a>
								</li>
							';
							break;
						case 1:	
							echo '	
								<li>
								  <a href="add_user.php">Add User</a>
								</li>
								<li>
								  <a href="users.php" class="nav-link text-left">View Users</a>
								</li>
                <li>
							';
							break;
					}
				}

                if (!isset($_SESSION['id'])) {
                echo'<li>
                <a href="includes/login.php" class="small btn btn-primary px-4 py-2 rounded-0"><span class="icon-unlock-alt"></span> Log In </a>
                </li>';
                } else {
                  echo'<li>
                  <a href="includes/logout.php" style="Color: #FFFFFF;" class="small btn btn-primary rounded-2 px-4 py-2">Logout</a>
                  </li>';
                }
      ?>
            </nav>

          </div>
        </div>
      </div>

    </header>

  <!-- loader -->
  <div id="loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#51be78"/></svg></div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.fancybox.min.js"></script>
  <script src="js/jquery.sticky.js"></script>
  <script src="js/jquery.mb.YTPlayer.min.js"></script>
  <script src="js/main.js"></script>

<!-- </body> -->
</head>

</html>

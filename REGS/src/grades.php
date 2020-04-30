<!DOCTYPE html>
<html lang="en">

<head>

	<title> Grades - Farm Fresh Regs</title>

  <style>
    .dropbtn:hover, .dropbtn:focus {
      background-color: #3e8e41;
    }

    #myInput {
      box-sizing: border-box;
      background-image: url('searchicon.png');
      background-position: 14px 12px;
      background-repeat: no-repeat;
      font-size: 16px;
      padding: 14px 20px 12px 45px;
      border: none;
      border-bottom: 1px solid #ddd;
    }

    #myInput:focus {outline: 3px solid #ddd;}

    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f6f6f6;
      min-width: 230px;
      overflow: auto;
      border: 1px solid #ddd;
      z-index: 1;
    }

    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown a:hover {background-color: #ddd;}

    .show {display: block;}
  </style>

	<?php
		require_once ('header.php'); 
		session_start();
	
		$id = $_SESSION['id'];

		if (empty ($id)) {
			header ('Location: home.php');
		}

		$plevel = $_SESSION['p_level'];

		if ($plevel == 5) {
			header ('Location: home.php');
		}

		include ('php/connectvars.php');    
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		// Find all classes 
		//$query = 'SELECT department, c_no, title, semester, year FROM catalog';

		// If this is a faculty, need to add WHERE clause to specify only the 
		// classes they are teaching
		if ($plevel == 4) {
			$query = 'SELECT department, c_no, title, semester, year, sem FROM catalog, schedule, courses_taught, semester WHERE courses_taught.f_id="'. $_SESSION['id'] .'" and courses_taught.crn=schedule.crn and schedule.course_id=catalog.c_id and sem=semesterid';
		}else{
      $query = 'SELECT department, c_no, title, semester, year, sem FROM catalog, schedule, semester WHERE schedule.course_id=catalog.c_id and sem=semesterid';
    }
	
		$classes = mysqli_query ($dbc, $query);
    ?>

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="container mt-5 pt-3">
		<div class="row">
			<h2 class="section-title-underline mb-4">
				<span>Change Grades</span>
			</h2>
		</div>

		<div class="row">
			<h4 class="pl-1 font-weight-lighter"><small> 
				Use the dropdown menu to select a specific class.
			</small></h4>  
		</div>	

		<div class="row mt-1 pt-2">
			<div class="dropdown">
				<button onclick="myFunction()" class="btn-primary">
					Select Class
				</button>
				<div id="myDropdown" class="dropdown-content">
					<input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
    <?php
		// Need to add all classes to dropdown menu
		while ($c = mysqli_fetch_array ($classes)) {
			$course = '<b>'. $c['department'] . " " . $c['c_no'] . "</b>: " . $c['title'] . "</br>" . $c['semester'] . $c['year'];
			echo '<a href="grades.php?cno='. $c['c_no'] .'&sem='. $c['sem'] .'"'. $course .'</a>';
			
		} 
	?>
				</div>
			</div>
		</div>

		<div class="row">

	<?php
		if (isset ($_GET['cno'])) {
			require_once ('enrollment.php');
		}
	?> 

		</div>
	</div>

<script>
      /* When the user clicks on the button,
      toggle between hiding and showing the dropdown content */
      function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
      }

      function filterFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdown");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
          txtValue = a[i].textContent || a[i].innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
          } else {
            a[i].style.display = "none";
          }
        }
      }
</script>

</body>
</html>

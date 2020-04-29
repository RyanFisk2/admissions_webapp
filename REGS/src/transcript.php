<!DOCTYPE html>
<html lang="en">

<head>
    <title>Transcript - Farm Fresh Regs</title>
    <?php
      header('Location: ../../ADS/Webpages/transcript.php')
      //require_once ('header.php'); 
		  //session_start();
	  ?>

	<style>
		.dropbtn:hover, .dropbtn:focus {
		  background-color: #3e8e41;
		}

		#myInput {
		  box-sizing: border-box;
		  background-image: url('searchicon.png');
		  background-position: 14px 12px; background-repeat: no-repeat;
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
</head>

<body>

<br><br><br>

    <div class="container">
		<h1 class="text-primary"> Transcript </h1>

        <?php
              include ('php/connectvars.php');		

			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            // If current user is not a student, show a dropdown menu to select a student
            if (isset ($_SESSION['p_level']) && $_SESSION['p_level'] != 5) {
                echo '
                    <div class="row mt-5">
                        <div class="dropdown">
                            <button onclick="myFunction()" class="btn-primary">
								Select Student
                            </button>
                            <div id="myDropdown" class="dropdown-content">
                                <input type="text" placeholder="Search.."
									id="myInput" onkeyup="filterFunction()">
				';

				$query = 'SELECT u_id, fname, lname FROM student;';
				$students = mysqli_query ($dbc, $query);

				while ($s = mysqli_fetch_array($students)) {
					echo '
						<a href="transcript.php?student='. $s["u_id"] .'">'. $s["fname"] . ' ' . $s["lname"] .'</a>
					';
				}
                                
				echo '
							</div>
                        </div>
                    </div>

					<div class="row mt-5">
						<h4>
							<span class="font-weight-bold"> Current Student: </span>
				';
				
				// If user selected a specific student, display the name		
				if (isset ($_GET['student'])) {
					$query = "SELECT fname, lname FROM student WHERE u_id='" . $_GET['student'] . "';";	
					$name = mysqli_fetch_array (mysqli_query ($dbc, $query));
					echo ' ' . $name['fname'] . ' ' . $name['lname'];
				} else { // User has not selected a student yet
					echo ' ---';	
				}
	
				echo '
						</h4>
					</div>
                ';
            }
          
			$id = ""; 
			if (isset ($_GET['student']) && $_SESSION['p_level'] != 5) {
				$id = $_GET['student'];
			}
			else if (isset ($_SESSION['id'])) { 
				$id = $_SESSION['id'];
			}
			
            // Find all semesters this student took classes in
            $query = 'SELECT semester, year ' .
                     'FROM courses_taken, schedule ' .
                     'WHERE u_id="' . $id . '" and courses_taken.crn=schedule.crn ' .
                     'GROUP BY semester, year
					  ORDER BY year ASC, semester ASC;';
            $semesters = mysqli_query ($dbc, $query);

			$empty_transcript = True;

            while ($s = mysqli_fetch_array($semesters)) {

				// There is at least one semester, set flag accordingly
				$empty_transcript = False;

                echo '
                <div class="row">
                    <div class="col d-flex flex-col mt-5">
                        <th class="p-2 mx-2 text-white" scope="col-6"> <h3><span class="text-primary">Semester: ' .
                        $s['semester'] . ' ' . $s['year']
                        . '</span></h3> </th>
                    </div>
                </div>

                <div class="row">
                    <table class=" border-left border-right table">

                    <thead>

                         <tr class="text-center table-light">
                            <th scope="col">Department</th>
                            <th scope="col">Course Number</th>
                            <th scope="col">Course Name</th>
                            <th scope="col">Credit Hours</th>
                            <th scope="col">Instructor</th>
                            <th scope="col">Grade</th>
                        </tr>
                    </thead>
                ';

                // Find all courses this student has taken/is taking
                $query = 'SELECT c_no, department, title, credits, grade, faculty.lname ' .
                          'FROM faculty, schedule, courses_taught, catalog, courses_taken ' .
                          'WHERE u_id="' . $id . '" and faculty.f_id=courses_taught.f_id ' .
                            'and courses_taken.crn=schedule.crn and catalog.c_id=schedule.course_id ' .
							'and courses_taught.crn=courses_taken.crn;';
                $classes = mysqli_query ($dbc, $query);

                while ($c = mysqli_fetch_array($classes)) {
                    echo '
                    <tbody>
                        <tr class="text-center">
                            <td>'. $c['department'] .'</td>
                            <td>'. $c['c_no'] .'</td>
                            <td>'. $c['title'] .'</td>
                            <td>'. $c['credits'] .'</td>
                            <td>'. $c['lname'] .'</td>
                            <td>'. $c['grade'] .'</td>
                        </tr>
                    </tbody>
                    ';
                }

                echo '
                    </table>
                </div>
                ';
            }

			// Check if transcript is empty and this is a student
			if ($empty_transcript && $_SESSION['p_level'] == 5) {
				echo '<div class="container pt-3">
					      <h4 class="pl-1 font-weight-lighter"> <small>
						    You have not taken any classes and are not currently registered for any.
						  </small></h4>
					  </div>';
			} else if($_SESSION['p_level'] != 5){
				echo '<div class="container pt-3">
					      <h4 class="pl-1 font-weight-lighter"> <small>
							Student has not taken any classes and is not currently registered for any.
						  </small></h4>
					  </div>';
			}

        ?>

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

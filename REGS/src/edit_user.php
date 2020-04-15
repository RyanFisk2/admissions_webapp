<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Users - Farm Fresh Regs</title>
    <?php
      require_once ('header.php'); 
		  session_start();
	  ?>

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
</head>

<?php
    if($_SESSION['p_level'] != "Admin"){
        header("Location: home.php");
    }
?>

<body>

<br><br><br>

    <div class="container">
		<h1 class="text-primary"> Edit User </h1>

        <?php
              include ('php/connectvars.php');		

			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            // If current user is not a student, show a dropdown menu to select a student
            if (isset ($_SESSION['p_level']) && strcmp ($_SESSION['p_level'], 'Admin') == 0) {
                echo '
                    <div class="row mt-5">
                        <div class="dropdown">
                            <button onclick="myFunction()" class="btn-primary">
								Select User
                            </button>
                            <div id="myDropdown" class="dropdown-content">
                                <input type="text" placeholder="Search.."
									id="myInput" onkeyup="filterFunction()">
				';

				$query = 'SELECT u_id, fname, lname FROM student;';
				$students = mysqli_query ($dbc, $query);
                $query1 = 'SELECT f_id, fname, lname FROM faculty;';
                $faculty = mysqli_query ($dbc, $query1);

				while ($s = mysqli_fetch_array($students)) {
					echo '<a href="account.php?student='. $s["u_id"] .'">'. $s["fname"] . ' ' . $s["lname"] .'</a>';
				}

                while ($f = mysqli_fetch_array($faculty)){
                    echo '<a href="account.php?faculty='. $f["f_id"] .'">'. $f["fname"] . ' ' . $f["lname"] . '</a>';
                }
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
<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
    require_once ('header.php'); 
    session_start();

    $id = $_SESSION["id"];
    
    if (empty($id)) {
        header("Location: login.php");
    }
    
    include ('php/connectvars.php');		
    
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    $c_no = $_GET["cno"];
    $cid_query = "SELECT crn FROM schedule s, catalog c WHERE c.c_no=$c_no AND s.course_id=c.c_id";
    $cid = mysqli_fetch_array(mysqli_query($dbc, $cid_query));
    $crn  = $cid['crn'];

    $query = "SELECT * from catalog WHERE c_no=$c_no";

    $results = mysqli_query($dbc, $query);
    $data = mysqli_fetch_array($results);

    $title = $data["title"];
    $dept = $data["department"];

    $instructor_query = "SELECT fname, lname FROM faculty a, courses_taught b WHERE a.f_id=b.f_id AND crn=$crn";
    $instructor_data = mysqli_fetch_array(mysqli_query($dbc, $instructor_query));

    $fname = $instructor_data['fname'];
    $lname = $instructor_data['lname'];

    ?>
    
  <title>Course Info</title>

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
    <br><br><br>
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-lg mx-auto align-self-center">
                        <h2 class="section-title-underline mb-5">
                            <span><?php echo $dept ?> <?php echo $c_no ?>: <?php echo $title ?></span>
                        </h2>
                        
                        <p><strong class="text-black d-block">Instructor:</strong><?php echo $fname ?> <?php echo $lname ?></p><br>   

                        <?php
                        
                        $course_query = "SELECT * FROM schedule WHERE crn=$crn";

                        $query_results = mysqli_query($dbc, $course_query);

                            ?>
                            <table class="table">
                                <thead>
                                <h2><strong class="text-black d-block">Sections:</strong></h2>
                            <?php if ($query_results && mysqli_num_rows($query_results) > 0) { ?>
                                    <tr class="text-center">
                                        <th scope="col">Section</th>
                                        <th scope="col">Semester</th>
                                        <th scope="col">Year</th>
                                        <th scope="col">Day</th>
                                        <th scope="col">Start</th>
                                        <th scope="col">End</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                            <tbody id="course_table">
                            <?php
                            while ($query_results && $row = mysqli_fetch_assoc($query_results)) {
                            ?>
                                <tr class="text-center">
                                <?php 

                                $crn = $row["crn"];
                                $section = $row["section_no"];
                                $semester = $row["semester"];
                                $year = $row["year"];
                                $day = $row["day"];
                                $start = $row["start_time"];
                                $end = $row["end_time"];

                                ?>

                                <td> <?php echo $section?> </td>
                                <td> <?php echo $semester?> </td>
                                <td> <?php echo $year?> </td>
                                <td> <?php echo $day?> </td>
                                <td> <?php echo $start?> </td>
                                <td> <?php echo $end?> </td>

                            <?php 

                            $uid =  $_SESSION['id'];
                            $enrollment_query = "SELECT * FROM courses_taken WHERE u_id=$uid AND crn=$crn";
                            $enrollement_results = mysqli_query($dbc, $enrollment_query);
                           
							// Only show an enroll/drop button if this is a student 
							if (strcmp ($_SESSION['p_level'], 5) == 0) {
								if (empty(mysqli_fetch_array($enrollement_results))) { ?>
									<td> <a href="register.php?crn=<?php echo $crn ?>&cno=<?php echo $c_no ?>" class="btn btn-primary btn-sm rounded-2 px-3">Enroll</a> </td>
								<?php } else { ?>
									<td> <a href="drop.php?crn=<?php echo $crn ?>&cno=<?php echo $c_no ?>" class="btn btn-danger btn-sm rounded-2 px-3">Drop</a> </td>
								<?php } ?>
									</tr>
							<?php
								}
							}
                            echo "</tbody>
                            </table>";
                        } else {
                            echo "There are no available sections of this course being offered at this time. Please check again later.";
                        }
                        ?>
                    </div>
            </div>
        </div>
    </div>

  </div>
  <!-- .site-wrap -->

  <!-- loader -->
  <!-- <div id="loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#51be78"/></svg></div> -->

</body>

</html>

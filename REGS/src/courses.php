<!DOCTYPE html>
<html lang="en">

<head>
    <title>Course Catalog</title>
    <?php 
    require_once ('header.php'); 
    session_start();
	?>
</head>

<?php

$id = $_SESSION["id"];

if (empty($id)) {
    header("Location: login.php");
}

include ('php/connectvars.php');		

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

?>


<body>
<br><br>

    <div class="container pt-3">
        <h1 class="text-primary">Course Catalog</h1>
        <h4 class="pl-1 font-weight-lighter"><small>Search the available courses below. Click the highlighted course number to find information regarding reigstration.</small></h4>  
        <br><input class="form-control" id="search_filter" type="text" placeholder="Search..."><br>
        <?php

        // Schedule links to faculty on crn
        // Courses not being taught shouldnt have a proffesoror time alloted to them

        $course_query = 'SELECT s.sem, x.semester, x.year FROM catalog c, schedule s, semester x WHERE c.c_id=s.course_id and s.sem = x.semesterid';
        $sem1 = $i = $done = 0;

        $result1 = mysqli_query($dbc, $course_query);
        
        if (mysqli_num_rows($result1) > 0) {
            while ($row1 = mysqli_fetch_assoc($result1)) {
                if($done == 0){
            	if($i == 0){
            		$sem1 = $row1["sem"];
            		echo 'Schedule for ' . $row1['semester'] . ' ' . $row1['year'] . ':<br></br>';
            		$i = 1;
            		echo '<table class="table ">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">Course</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Credits</th>
                                    <th scope="col">Pre-requisite 1</th>
                                    <th scope="col">Pre-requisite 2</th>
                                </tr>
                                </thead>
                            <tbody id="course_table">';
                    $query = "SELECT c.* FROM catalog c, schedule s WHERE c.c_id=s.course_id and s.sem=$sem1";
            	}else if($row1["sem"] != $sem1){
                    if($row1["sem"] == 12){
                        $done = 1;
                    }
            		$sem1 = $row1["sem"];
                    echo "</tbody>
                        </table>";
            		echo 'Schedule for ' . $row1['semester'] . ' ' . $row1['year'] . ':<br></br>';
            		echo '<table class="table ">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">Course</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Credits</th>
                                    <th scope="col">Pre-requisite 1</th>
                                    <th scope="col">Pre-requisite 2</th>
                                </tr>
                                </thead>
                            <tbody id="course_table">';
                    $query = "SELECT c.* FROM catalog c, schedule s WHERE c.c_id=s.course_id and s.sem=$sem1";
            	}

            $result = mysqli_query($dbc, $query);
        
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr class="text-center">
                <td>
                <?php 

                $cid = $row["c_id"];

                $crn = $row["c_no"];
                $dept = $row["department"];
                $title = $row["title"];
                $credits = $row["credits"];

                $prereq_query = "SELECT prereq1, prereq2 FROM prereqs WHERE course_Id=$cid";
                $query_result = mysqli_query($dbc, $prereq_query);
                $data = mysqli_fetch_assoc($query_result);
                $pre1 = explode(' ', trim($data['prereq1']));
                $pre2 = explode(' ', trim($data['prereq2']));

                ?>

                <a href="course.php?cno=<?php echo $crn ?>&dept=<?php echo $dept?>&sem=<?php echo $sem1?>"> <?php echo $dept ?> <?php echo $crn ?> </a>
                </td>
                <td> <?php echo $title?> </td>
                <td> <?php echo $credits?> </td>

                <?php if (empty($data['prereq1']) && !empty($data['prereq2'])) { ?>
                    <td>None</td>

                    <td>  
                    <a href="course.php?cno=<?php echo $pre2[1] ?>"> <?php echo $pre2[0] ?> <?php echo $pre2[1] ?> </a>
                    </td>
                <?php } else if (!empty($data['prereq1']) &&  empty($data['prereq2'])) { ?>
                    <td> 
                    <a href="course.php?cno=<?php echo $pre1[1] ?>"> <?php echo $pre1[0] ?> <?php echo $pre1[1] ?> </a>
                    </td>
                    <td>None</td>
                 <?php } else if (!empty($data['prereq1']) && !empty($data['prereq2'])) { ?>
                    <td>
                    <a href="course.php?cno=<?php echo $pre1[1] ?>"> <?php echo $pre1[0] ?> <?php echo $pre1[1] ?> </a>
                    </td>
                    
                    <td>
                    <a href="course.php?cno=<?php echo $pre2[1] ?>"> <?php echo $pre2[0] ?> <?php echo $pre2[1] ?> </a>
                    </td>
                <?php  } else { ?>
                    <td>None</td>
                    <td>None</td>
                 <?php } ?>

                </tr>
            <?php }}} ?>
        <?php
            }
            echo "</tbody>
            </table>";
        } else {
            echo "We're sorry, course enrollment is under maintenance";
        }
        ?>
    </div>


    <script>
    $(document).ready(function(){
    $("#search_filter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#course_table tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });
    </script>

</body>

</html> 

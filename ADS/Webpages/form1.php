<?php
  $pg_title = "Form1";
  echo '<head>';
  require_once('header.php');
  echo '</head>';
  $degreeErr = "";
  require_once('connectvars.php');

  if (isset($_SESSION['acc_type'])) {
?>
<br></br>
<div class="site-section">
  <div class="container">
		<div class="row mb-4 justify-content-center text-center">
      <div class="col-lg-4 mb-4">
        <h2 class="section-title-underline mb-4">
        <span>Form 1</span>
        </h2>
      </div>
    </div>
   <form action="form1back.php" method="post" class="card p-5 mt-4">
   <div class="row">
    <div class="col">
      <label for="course1">CourseId 01:</label>
      <input type="number" id="course1" name="course1">
    </div>
    <div class="col">
      <label for="cdept1">Course 01 department:</label>
      <input type="text" id="cdept1" name="cdept1"></br>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="course2">CourseId 02:</label>
      <input type="number" id="course2" name="course2">
    </div>
    <div class="col">
      <label for="cdept2">Course 02 department:</label>
      <input type="text" id="cdept2" name="cdept2"></br>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="course3">CourseId 03:</label>
      <input type="number" id="course3" name="course3">
    </div>
    <div class="col">  
      <label for="cdept3">Course 03 department:</label>
      <input type="text" id="cdept3" name="cdept3"></br>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="course4">CourseId 04:</label>
      <input type="number" id="course4" name="course4">
    </div>
    <div class="col"> 
      <label for="cdept4">Course 04 department:</label>
       <input type="text" id="cdept4" name="cdept4"></br>
       </div>
  </div>

   <div class="row">
    <div class="col">
      <label for="course5">CourseId 05:</label>
      <input type="number" id="course5" name="course5">
    </div>
    <div class="col">
      <label for="cdept5">Course 05 department:</label>
      <input type="text" id="cdept5" name="cdept5"></br>
    </div>
  </div>

   <div class="row">
    <div class="col">
      <label for="course6">CourseId 06:</label>
      <input type="number" id="course6" name="course6">
    </div>
    <div class="col">
      <label for="cdept6">Course 06 department:</label>
      <input type="text" id="cdept6" name="cdept6"></br>
    </div>
  </div>

   <div class="row">
    <div class="col">
      <label for="course7">CourseId 07:</label>
      <input type="number" id="course7" name="course7">
    </div>
    <div class="col">
      <label for="cdept7">Course 07 department:</label>
      <input type="text" id="cdept7" name="cdept7"></br>
    </div>
  </div>

   <div class="row">
    <div class="col">
      <label for="course8">CourseId 08:</label>
      <input type="number" id="course8" name="course8">
    </div>
    <div class="col">
      <label for="cdept8">Course 08 department:</label>
      <input type="text" id="cdept8" name="cdept8"></br>
    </div>
  </div>

   <div class="row">
    <div class="col">
      <label for="course9">CourseId 09:</label>
      <input type="number" id="course9" name="course9">
    </div>
    <div class="col">
      <label for="cdept9">Course 09 department:</label>
      <input type="text" id="cdept9" name="cdept9"></br>
    </div>
  </div>

   <div class="row">
    <div class="col">
      <label for="course10">CourseId 10:</label>
      <input type="number" id="course10" name="course10">
    </div>
    <div class="col">
      <label for="cdept10">Course 10 department:</label>
      <input type="text" id="cdept10" name="cdept10"></br>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="course11">CourseId 11:</label>
      <input type="number" id="course11" name="course11">
    </div>
    <div class="col">
      <label for="cdept11">Course 11 department:</label>
      <input type="text" id="cdept11" name="cdept11"></br>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="course12">CourseId 12:</label>
      <input type="number" id="course12" name="course12">
    </div>
    <div class="col">
      <label for="cdept12">Course 12 department:</label>
      <input type="text" id="cdept12" name="cdept12"></br>
    </div>
  </div>

    <br>
   <input type="submit" class="button" name ="submit" value="Submit">
 </form>

</div>
</div>

<?php
  }
?>

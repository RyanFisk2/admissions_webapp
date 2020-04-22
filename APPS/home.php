  
<!DOCTYPE HTML>

  <!-- Masthead -->
  <?php
  if (empty($_SESSION['role'])) { 
  ?>
  <header class="masthead text-white text-center">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-xl-9 mx-auto">
            <h1 class="mb-5">Sign in to continue your application or create an account to apply!</h1>
        </div>
      </div>
    </div>
  </header>
  <?php
  } else {
  ?>
  <header class="masthead text-white text-center">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-xl-9 mx-auto">
            <h1 class="mb-5">Welcome 
            <?php 
              echo $_SESSION['name'] . "!"; 
            ?>
            </h1>
            <h1 class="mb-5">Your userID is: 
            <?php 
              echo $_SESSION['userID']; 
            ?>
            </h1>
        </div>
      </div>
    </div>
  </header>
  <?php
  }
  ?>

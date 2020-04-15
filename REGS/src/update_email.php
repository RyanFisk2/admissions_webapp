<!DOCTYPE html>
<html lang="en">
<head>
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
</head>

<?php require_once ('header.php'); ?>

<body>
  <!-- Navigation -->
  <!-- Page Content -->
  <div class="site-section">
  <div class="container">
  <div class="row justify-content-center">
                <div class="col-md-5">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="new_email">New email:</label>
                            <input type="text" id="new_email" name="new_email" class="form-control form-control-lg">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="new_email2">Confirm new email:</label>
                            <input type="text" id="new_email2" name="new_email2" class="form-control form-control-lg">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <input type="submit" value="Update email" class="btn btn-success btn-lg px-5" name="submit">
                        </div>
                    </div>
                </form>
                </div>
            </div>
	</div>

</body>
</html>

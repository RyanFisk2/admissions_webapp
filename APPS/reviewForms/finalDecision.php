<!DOCTYPE HTML>

<html>
<head>

        <?php
                session_start();

                require_once('../includes/connectvars.php');
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

                $applicationID = $_GET['applicationID'];
                $query = "SELECT userID FROM application_form WHERE applicationID = '$applicationID'";

                $result = mysqli_query($dbc, $query);
                $info = mysqli_fetch_array($result);

                $applicantID = $info['userID'];

                require_once('../includes/reviewHeader.php');

        ?>

</head>

<body>

        <h2 align="center">Final Decision</h2>
<?php echo"<form method='post' action='./reviewForms/submitDecision.php?applicantID=$applicantID'>"; ?>

                <label for="reject">Reject</label>
                <input type="radio" name="rating" id="reject" value="4">

                <label for="admNoAid">Admit Without Aid</label>
                <input type="radio" name="rating" id="admNoAid" value="2">

                <label for="admWAid">Admit With Aid</label>
                <input type="radio" name="rating" id="admWAid" value="3"><br/>

                <label for="reasons">Recommended Advisor</label>
                <input type="text" name="advisor" id="advisor" required><br/>

		<button name="submit" class="btn btn-primary">Submit</button>
        </form>

</body>

</html>


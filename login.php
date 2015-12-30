<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Coupons::Login</title>

<style>
.jumbotron {
	background-color: lavender;
    padding: 0.2em 0.2em;
    h1 {
        font-size: 2em;
    }
    p {
        font-size: 1.0em;
        .btn {
            padding: 0.2em;
        }
    }
}

body {
    background-color: lavender;
    padding-bottom: 70px;
    padding-top: 40px;
}
</style>



</head>

<body id="main_body" >

<!--	CONTROLLER VARS	-->
<?php
	include_once ('model/session_manager.php');
	include_once ('model/signup_logic.php');


	
	if ($_SERVER["REQUEST_METHOD"] != "POST") {	//case tried to access file in a wrong way
		die ('<script>window.location.assign("index.php")</script>');
	}
	
	$username_err = $password_err = "";
	
	$username = $_POST["username"];
	$password = $_POST["password"];

	$validFields = validFields();
		
	function validFields() {
		global $username_err, $password_err;
		global $username, $password;
		$valid = true;
		if (!array_key_exists('username', $_POST) | $_POST['username'] === "") { $username_err="*Please enter username"; $valid = false;}
		if (username_is_free ($username)) { $username_err=$username_err . " *Username not in the system"; $valid = false;}
		if (!array_key_exists('password', $_POST) | $_POST['password'] === "") { $password_err="*Please enter password"; $valid = false;}
		return $valid;
	}
	
	if ($validFields) {
		$login_success = session_login($username, $password);
		if (!$login_success) $password_err = " *Incorrect password";
	}
		
	$is_logged_in = is_user_connected();
	$connected_user_name = connected_user_name();
		
	if (array_key_exists('section', $_GET))	$section = $_GET["section"];
	if (array_key_exists('section', $_POST)) $section = $_POST["section"];
?>

<!--	JUMBOTRON	-->
<div class="jumbotron">
  <h1 class="text-center">Coupons <small>I wish I had lots of money</small></h1>
</div>

<!--	MAIN SECTION	-->
<div class="container-fluid">
    <!--	NAV BAR	-->
    <?php
		include('views/bottom_nav_bar.php');
		print_bottom_nav_bar($section, $is_logged_in);
	?>
    
    <div class="row">
        <!--LEFT SEARCH BAR-->
        <div class="col-md-2" style="background-color:lavender;">
        </div>
        
        <!--CENTRAL SECTION-->
        <div class="col-md-10" style="background-color:lightsky;">
			<?php
				include_once ('views/login_view.php'); 
				if ($is_logged_in) {
					print_login_success();
				} else {
					print_login_fail();
				}
            ?>
        </div>
    </div>
</div>





</body>
</html>

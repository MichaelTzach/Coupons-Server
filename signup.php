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
<title>Coupons::Signup</title>

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
	include_once ('model/signup_logic.php');
	include_once ('model/session_manager.php');
	
	if ($_SERVER["REQUEST_METHOD"] != "POST" | is_user_connected()) {	//case tried to access file in a wrong way
		die ('<script>window.location.assign("index.php")</script>');
	}
	
	$firstname_err = $lastname_err = $email_err = $phonenumber_err = $date_of_birth_err = $username_err = $password_err = $gender_err = "";
	
	$firstname = $_POST["firstname"];
	$lastname = $_POST["lastname"];
	$email = $_POST["email"];
	$phonenumber = $_POST["phonenumber"];
	$date_of_birth = $_POST["date_of_birth"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	$interests = $_POST["interests"];
	$gender = $_POST["gender"];

	$validFields = validFields();
	
	function validFields() {
		global $firstname_err, $lastname_err, $email_err, $phonenumber_err, $date_of_birth_err, $username_err, $password_err, $gender_err;
		global $firstname, $lastname, $email, $phonenumber, $date_of_birth, $username, $password, $interests, $gender;
		$valid = true;
		if (!array_key_exists('firstname', $_POST) | $_POST['firstname'] === "") {echo 'asd'; $firstname_err="*Mandatory field"; $valid = false; }
		if (!array_key_exists('lastname', $_POST) | $_POST['lastname'] === "") { $lastname_err="*Mandatory field"; $valid = false;}
		if (!array_key_exists('email', $_POST) | $_POST['email'] === "") { $email_err="*Mandatory field"; $valid = false;}
		if (!array_key_exists('phonenumber', $_POST) | $_POST['phonenumber'] === "") { $phonenumber_err="*Mandatory field"; $valid = false;}
		if (!array_key_exists('date_of_birth', $_POST) | $_POST['date_of_birth'] === "") { $date_of_birth_err="*Mandatory field"; $valid = false;}
		if (!array_key_exists('username', $_POST) | $_POST['username'] === "") { $username_err="*Mandatory field"; $valid = false;}
		if (!username_is_free ($username)) { $username_err=$username_err . " *Username is in use"; $valid = false;}
		if (!array_key_exists('password', $_POST) | $_POST['password'] === "") { $password_err="*Mandatory field"; $valid = false;}
		if (!array_key_exists('gender', $_POST) | $_POST['gender'] === "") { $gender_err="*Mandatory field"; $valid = false;}
		return $valid;
	}
	
	if ($validFields) {
		signup_client($firstname, $lastname, $email, $phonenumber, $date_of_birth, $username, $password, $interests, $gender);
		session_login($username, $password);
	}
		
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
				include_once ('views/signup_view.php'); 
				if ($validFields) {
					print_signup_success();
				} else {
					print_signup_fail();
				}
            ?>
        </div>
    </div>
</div>





</body>
</html>

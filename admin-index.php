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
<title>Coupons::Admin Panel</title>

<style>
.jumbotron {
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
    
}
</style>



</head>

<body id="main_body" >

<!--	CONTROLLER VARS	-->
<?php
	//include_once ('./model/coupon_logic.php');
	include_once ('model/session_manager.php');
	include_once ('model/business_logic.php');

	$section = "bussiness"; 
	$sub_section = "edit";
	$item_for_edit = "";

	$feedback_message ="";
	

	$is_logged_in = is_user_connected();
	$connected_user_name = connected_user_name();
	$connected_user_role = connected_user_role();

	if (!$is_logged_in || $connected_user_role != "admin") {	//case tried to access file in a wrong way
		die ('<script>window.location.assign("index.php")</script>');
	}
	
	if (array_key_exists('search_bar_coupon_name', $_GET))	{
		$search_bar_coupon_name = $_GET["search_bar_coupon_name"];
		//$array_of_coupons_after_search = search_coupons_by_name($search_bar_coupon_name);
	} else {
		//$array_of_coupons_after_search = search_all_coupons();
	}
		
			
	if (array_key_exists('section', $_GET))	$section = $_GET["section"];
	if (array_key_exists('sub_section', $_GET))	$sub_section = $_GET["sub_section"];
	if (array_key_exists('item_for_edit', $_GET)) $item_for_edit = $_GET["item_for_edit"];
	
	handle();
?>

<!--	JUMBOTRON	-->
<div class="jumbotron">
  <h1 class="text-center">Coupons <small>I'm the supervisor</small></h1>
</div>

<!--	MAIN SECTION	-->
<div class="container-fluid">
    <!--	NAV BAR	-->
    <?php
		include('views/admin_bottom_nav_bar.php');
		print_bottom_nav_bar($section, $is_logged_in);
	?>
    
    <div class="row">
        <!--LEFT SEARCH BAR-->
        <div class="col-md-2" style="background-color:lavender;">
            <?php
                include('views/admin_left_bar.php');
                print_admin_left_bar($section, $sub_section);
            ?>
        </div>
        
        <!--CENTRAL SECTION-->
        <div class="col-md-10" style="background-color:lightsky;">
			<?php
				include_once ('views/admin_main_panel.php'); 
				if ($section === "bussiness" && $sub_section === "show_add_form") print_add_business(search_all_managers(), $feedback_message);
				if ($section === "bussiness" && $sub_section === "show_edit_gallery") print_business_gallery_to_main_section(search_all_businesses(), $feedback_message);
				if ($section === "bussiness" && $sub_section === "show_edit_item") print_business_edit_to_main_section(search_business_by_id($item_for_edit), $feedback_message);
				
				if ($section === "manager" && $sub_section === "show_add_form") print_add_manager($feedback_message);
				if ($section === "manager" && $sub_section === "show_edit_gallery") print_manager_gallery_to_main_section(search_all_managers(), $feedback_message);
				if ($section === "manager" && $sub_section === "show_item_details") print_manager_details_to_main_section(search_manager_by_manager_username($item_for_edit), $feedback_message);
            ?>
        </div>
    </div>
</div>
</body>
</html>

<?php
function handle(){
	global $section, $sub_section, $feedback_message, $item_for_edit;
	if ($section === "bussiness" &&	$sub_section === "add_action" && $_SERVER["REQUEST_METHOD"] == "POST") {
		include_once ('model/business_logic.php');
		$manager_username = $_POST["manager"];
		$location_latitude = $_POST["latitude"]	;
		$location_longitude = $_POST["longitude"]	;
		$category = $_POST["category"];
		$name = $_POST["business_name"];
		$address = $_POST["business_address"];
		$city = $_POST["business_city"]	;
		if ($manager_username != "" & $location_latitude != "" & $location_longitude != "" & $category != "" & $name != "" & $address != "" & $city != "") {
			add_business($manager_username, $location_latitude, $location_longitude, $category, $name, $address, $city);
			$feedback_message = '<span class="label label-success">'. $name . ' Added Successfully</span>';

		} else {
			$feedback_message = '<span class="label label-warning">All Fields Must Be Entered</span>';
		}
		$sub_section = "show_edit_gallery";
	} 
	
	else if ($section === "manager" &&	$sub_section === "add_action" && $_SERVER["REQUEST_METHOD"] == "POST") {
		include_once ('model/business_logic.php');
		$firstname = $_POST["manager_first_name"];
		$lastname = $_POST["manager_last_name"];
		$email = $_POST["manager_email"];
		$phonenumber = $_POST["manager_phone"];
		$username = $_POST["manager_username"];
		$password = $_POST["manager_password"];
		
		if ($firstname != "" &$lastname != "" &$email != "" &$phonenumber != "" &$username != "" &$password != "") {
			register_manager($firstname, $lastname, $email, $phonenumber, $username, $password);
			$feedback_message = '<span class="label label-success">'. $firstname . " " . $lastname . ' Added Successfully</span>';

		} else {
			$feedback_message = '<span class="label label-warning">All Fields Must Be Entered</span>';
		}
		
		$sub_section = "show_edit_gallery";
	} 
	
	else if ($section === "bussiness" &&	$sub_section === "edit_action" && $_SERVER["REQUEST_METHOD"] == "POST") {
		include_once ('model/business_logic.php');
		$location_latitude = $_POST["latitude"]	;
		$location_longitude = $_POST["longitude"]	;
		$name = $_POST["business_name"];
		$address = $_POST["business_address"];
		$city = $_POST["business_city"]	;
		$old_item = search_business_by_id($item_for_edit);
		if ($location_latitude != "" & $location_longitude != "" & $name != "" & $address != "" & $city != "") {
			edit_business($item_for_edit, $old_item->manager_username, $location_latitude, $location_longitude, $old_item->category, $name, $address, $city);
			$feedback_message = '<span class="label label-success">'. $name . ' Edited Successfully</span>';

		} else {
			$feedback_message = '<span class="label label-warning">All Fields Must Be Entered</span>';
		}
		$sub_section = "show_edit_gallery";


	} 
	
	else if ($section === "bussiness" & $sub_section === "delete_action") {
		include_once ('model/business_logic.php');
		delete_business($item_for_edit);
		$feedback_message = '<span class="label label-success">'. $name . ' Deleted Successfully</span>';
		$item_for_edit =="";
		$sub_section = "show_edit_gallery";
	} 
	
		else if ($section === "manager" & $sub_section === "delete_action") {
		include_once ('model/business_logic.php');
		delete_manager($item_for_edit);
		$feedback_message = '<span class="label label-success">'. $name . ' Deleted Successfully</span>';
		$item_for_edit =="";
		$sub_section = "show_edit_gallery";
	} 

}

/*
sub sections:

edit_action - make edit in datebase
add_action - make add in database
delete_action

show_add_form - show add form
show_edit_gallery - show edit form
show_edit_item 

show_item_details

*/

?>
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
<title>Coupons::Manager Panel</title>

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
	include_once ('model/session_manager.php');
	include_once ('model/business_logic.php');

	$section = "bussiness"; 
	$sub_section = "edit";
	$item_for_edit = "";


	$feedback_message ="";
	
	

	$is_logged_in = is_user_connected();
	$connected_user_name = connected_user_name();
	$connected_user_role = connected_user_role();
	


	if (!$is_logged_in || $connected_user_role != "manager") {	//case tried to access file in a wrong way
		die ('<script>window.location.assign("index.php")</script>');
	}
	
			
	if (array_key_exists('section', $_GET))	$section = $_GET["section"];
	if (array_key_exists('sub_section', $_GET))	$sub_section = $_GET["sub_section"];
	if (array_key_exists('item_for_edit', $_GET)) $item_for_edit = $_GET["item_for_edit"];


	handle();
	$my_businesses = search_business_by_manager_user_name($connected_user_name);
?>

<!--	JUMBOTRON	-->
<div class="jumbotron">
  <h1 class="text-center">Coupons <small>I'm the supervisor</small></h1>
</div>

<!--	MAIN SECTION	-->
<div class="container-fluid">
    <!--	NAV BAR	-->
    <?php
		include('views/manager_bottom_nav_bar.php');
		print_bottom_nav_bar($section, $is_logged_in);
	?>
    
    <div class="row">
        <!--LEFT SEARCH BAR-->
        <div class="col-md-2" style="background-color:lavender;">
            <?php
                include('views/manager_left_bar.php');
                print_admin_left_bar($section, $sub_section, $my_businesses);
            ?>
        </div>
        
        <!--CENTRAL SECTION-->
        <div class="col-md-10" style="background-color:lightsky;">
			<?php
				include_once ('views/manager_main_panel.php'); 
				include_once ('model/coupon_logic.php');
				echo $feedback_message;
				if ($section == "bussinesses" && $sub_section == "view") print_view_business(search_business_by_id($item_for_edit), $feedback_message);
				if ($section == "bussinesses" && $sub_section == "add_form") print_add_business(search_manager_by_manager_username($connected_user_name), $feedback_message);
				
				if ($section == "coupons" && $sub_section == "view") print_view_coupons_in_business(search_business_by_id($item_for_edit), search_coupons_by_business_id($item_for_edit));
				if ($section == "coupons" && $sub_section == "add_form") print_add_coupon_form_to_main_section(search_business_by_id($item_for_edit));
				//if ($section === "coupons" && $sub_section === "show_item_details") print_manager_details_to_main_section(search_manager_by_manager_username($item_for_edit), $feedback_message);
            ?>
        </div>
    </div>
</div>
</body>
</html>

<?php
function handle(){
	global $section, $sub_section, $feedback_message, $item_for_edit, $connected_user_name;
	
	/*WORKS!!!!!!!!!!!!!!!!!*/
	if ($section === "bussinesses" &&	$sub_section === "add_action" && $_SERVER["REQUEST_METHOD"] == "POST") {
		include_once ('model/business_logic.php');
		$manager_username = $connected_user_name;
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
	
		/*WORKS!!!!!!!!!!!!!!!!!*/
	else if ($section === "coupons" &&	$sub_section === "add_action" && $_SERVER["REQUEST_METHOD"] == "POST") {
		include_once ('model/coupon_logic.php');
		
		$business_id = $_POST["coupon_business_id"];
		$name = $_POST["coupon_name"];
		$description = $_POST["coupon_description"];
		$category = $_POST["coupon_category"];
		$offered_quantity = $_POST["coupon_offered_quantity"];
		$discount_price = $_POST["coupon_discount_price"];
		$original_price = $_POST["coupon_original_price"];
		$expiration_date = $_POST["coupon_expiration_date"];
		
		if ($business_id != "" & $name != "" & $description != "" & $category != "" & $offered_quantity != "" & $discount_price != "" & $original_price != "" & $expiration_date != "" ) {
			add_coupon($business_id, $name, $description, $category, $offered_quantity, $discount_price, $original_price, $expiration_date);
			$feedback_message = '<span class="label label-success">'. $firstname . " " . $lastname . ' Added Successfully</span>';

		} else {
			$feedback_message = '<span class="label label-warning">All Fields Must Be Entered</span>';
		}
		
		$sub_section = "view";
	} 
	
	else if ($section === "coupons" &&	$sub_section === "delete_action") {
		include_once ('model/coupon_logic.php');
		delete_coupon($item_for_edit);
		$feedback_message = '<span class="label label-success">'. $name . ' Deleted Successfully</span>';
		$sub_section = "";
		$item_for_edit =="";
	} 
	
	
	/*WORKS!!!!!!!!!!!!*/
	else if ($section === "bussinesses" & $sub_section === "delete_action") {
		include_once ('model/business_logic.php');
		include_once ('model/coupon_logic.php');
		delete_business($item_for_edit);
		$feedback_message = '<span class="label label-success">'. $name . ' Deleted Successfully</span>';
		$sub_section = "";
		$item_for_edit =="";
	} 
	
		else if ($section === "manager" & $sub_section === "delete_action") {
		include_once ('model/business_logic.php');
		delete_manager($item_for_edit);
		$feedback_message = '<span class="label label-success">'. $name . ' Deleted Successfully</span>';
		$item_for_edit =="";
		$sub_section = "";
	} 

}

/*
sub sections:



*/

?>
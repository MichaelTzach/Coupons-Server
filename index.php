<!--	CONTROLLER VARS	-->
<?php
	include_once ('model/coupon_logic.php');
	include_once ('model/session_manager.php');

	$section = "home"; 
	$sub_section = "";
	$item = "";
	
	$is_logged_in = is_user_connected();
	$connected_user_name = connected_user_name();
	$connected_user_role = connected_user_role();

	$array_of_coupons_after_search = search_all_coupons();
		
	$search_bar_coupon_name = "Search By Coupon Name"; $search_bar_coupon_name_bool = false;
	$search_bar_coupon_category_bool = false;
	$search_bar_coupon_distance = 55; $search_bar_coupon_distance_bool = false;
	$user_location_longitude = -1;
	$user_location_latitude = -1;

	
	if (array_key_exists('search_bar_coupon_name', $_GET))	{
		$search_bar_coupon_name = $_GET["search_bar_coupon_name"]; 
		if ($search_bar_coupon_name !== "") $search_bar_coupon_name_bool = true;
		else $search_bar_coupon_name = "Search By Coupon Name";
	}
	if (array_key_exists('search_bar_coupon_category', $_GET))	{
		$search_bar_coupon_category = $_GET["search_bar_coupon_category"]; 
		if ($search_bar_coupon_category != "all") $search_bar_coupon_category_bool = true;
	}
	if (array_key_exists('search_bar_coupon_distance', $_GET))	{ 
		$search_bar_coupon_distance = $_GET["search_bar_coupon_distance"];
		if ($search_bar_coupon_distance != "55") $search_bar_coupon_distance_bool = true;
	}
	if ($search_bar_coupon_distance_bool && array_key_exists('user_location_latitude', $_GET))	{ 
		$user_location_latitude = $_GET["user_location_latitude"];
		$user_location_latitude_bool = true;
	}
	if ($search_bar_coupon_distance_bool && array_key_exists('user_location_longitude', $_GET))	{ 
		$user_location_longitude = $_GET["user_location_longitude"];
		$user_location_longitude_bool = true;
	}
	
	if (array_key_exists('login_username', $_POST))	{	
		session_login($_POST[login_username], $_POST[login_password]);
	
	}
			
	if (array_key_exists('user_location_longitude', $_POST))	$user_location_longitude = $_POST["user_location_longitude"];
	if (array_key_exists('user_location_latitude', $_POST))	$user_location_latitude = $_POST["user_location_latitude"];
			
	if (array_key_exists('section', $_GET))	$section = $_GET["section"];
	if (array_key_exists('sub_section', $_GET))	$sub_section = $_GET["sub_section"];
	if (array_key_exists('item', $_GET)) $item = $_GET["item"];
	
	handle();
?>


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



<meta name="viewport" content="width=device-width, initial-scale=0.9">
	
<title>Coupons::Hello <?php
	if ($is_logged_in) echo $connected_user_name;
	else echo 'guest';
	?>
	</title>


<style>
.jumbotron {
	background-color: lavender;
    padding: 0.9em 0.9em;
    h1 {
        font-size: 3em;
    }
    p {
        font-size: 0.8em;
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



<!--	JUMBOTRON	-->
<div class="jumbotron">
  <h2 class="text-center">Coupons<p> By: Michael T, Gal B, Gal S & Elad M</p></h2>
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
            <?php
                include('views/search_bar_coupons.php');
                print_search_bar($section);
            ?>
        </div>
        
        <!--CENTRAL SECTION-->
        <div class="col-md-10" style="background-color:lightsky;">
			<?php
				include_once ('views/coupon_gallery.php'); 
				if ($section === "home") print_coupon_gallery(3, $array_of_coupons_after_search, 0, $is_logged_in, false);
				if ($section === "MyCoupons") {
					print_deal_gallery(4, $array_of_coupons_after_search, 0); 
					}
				if ($section === "purchase_confirmation") {
					print_single_coupon_in_galley($array_of_coupons_after_search);	
				}
            ?>
        </div>
    </div>
</div>
</body>
</html>

<?php
	function handle() {
		include_once ('model/coupon_logic.php');
		global $search_bar_coupon_name, $search_bar_coupon_category, $search_bar_coupon_name_bool, $search_bar_coupon_category_bool, $array_of_coupons_after_search;
		global $section, $sub_section, $item;
		global $search_bar_coupon_distance, $search_bar_coupon_distance_bool;
		global $user_location_longitude;
		global $user_location_latitude;
		global $connected_user_name;

		if ($section == "home" && $sub_section == "search") {
			if ($search_bar_coupon_name_bool) {
				$array_of_coupons_after_search = search_coupons_by_name($search_bar_coupon_name);
				if ($search_bar_coupon_category_bool) {
					$array_of_coupons_after_search = array_values(array_filter($array_of_coupons_after_search, "filter_by_cat"));
				}
				if ($search_bar_coupon_distance_bool) {
					$array_of_coupons_after_search = array_values(array_filter($array_of_coupons_after_search, "filter_by_loc"));
				}
			} else if ($search_bar_coupon_category_bool) {
				$array_of_coupons_after_search = search_coupons_by_category($search_bar_coupon_category);
				if ($search_bar_coupon_distance_bool) {
					$array_of_coupons_after_search = array_values(array_filter($array_of_coupons_after_search, "filter_by_loc"));
				}
			} else if ($search_bar_coupon_distance_bool) {
				$array_of_coupons_after_search = array_values(array_filter($array_of_coupons_after_search, "filter_by_loc"));
			}
		} else if ($section == "purchase_confirmation" && $item != "") {
			client_buy_coupon($connected_user_name, $item);			
		} else if ($section == "MyCoupons") {
			if ($sub_section == "unused") {
				$array_of_coupons_after_search = search_coupons_by_user_unused($connected_user_name);
			} else {
				$array_of_coupons_after_search = search_coupons_by_user($connected_user_name);
			}
		}
	}
	
	
	function filter_by_cat($coupon_info) {
		global $search_bar_coupon_category, $search_bar_coupon_category_bool;
		if (!$search_bar_coupon_category_bool) return true; 
		if ($coupon_info->category == $search_bar_coupon_category) return true;
		return false;
	}
	
	function filter_by_loc($coupon_info) {
		global $search_bar_coupon_distance, $search_bar_coupon_distance_bool;
		global $user_location_longitude;
		global $user_location_latitude;
		
		if (!$search_bar_coupon_distance_bool) return true; 
		if (distance($user_location_latitude, $user_location_longitude, $coupon_info->latitude, $coupon_info->longitude) <= $search_bar_coupon_distance) return true;
		return false;
	}
?>


<?php

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at http://www.geodatasource.com                          :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: http://www.geodatasource.com                        :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2015		   		     :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
function distance($lat1, $lon1, $lat2, $lon2) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
	return ($miles * 1.609344);
}


?>
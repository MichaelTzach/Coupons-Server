<?php
include_once ('./model/coupon_logic.php');
include_once ('./model/business_logic.php');

//coupon searches
/*print_r(search_coupons_by_name('a'));
print_r(search_coupon_by_id(1));
print_r(search_all_coupons());
print_r(search_coupons_by_user('m'));
print_r(search_coupons_by_business_name(''));
print_r(search_coupons_by_business_id(12));
print_r(search_coupons_by_category('sdkfjhdkjfsh'));*/

//coupon add/delete/edit/buy
/*add_coupon(13, 'gal', 'bar', 'the man', 8, 75, 100, date("m.d.y"));
print_r(search_coupon_by_id(9));
edit_coupon(9, 'galbar', 'bargal', 7, 70, 100, date("m.d.y"));
print_r(search_coupon_by_id(9));
delete_coupon(9);
client_buy_coupon('tzachm', 1);
echo check_quantity_left(1);*/

//business
/*add_business('dfghdfgh435', 333, 666, 'sports', 'maccabi', 'bloemfield', 'tel-aviv');
print_r(search_business_by_name('maccabi'));
print_r(search_business_by_id(8));
print_r(search_all_businesses());
print_r(search_business_by_category('sports'));
edit_business(8, 'dfghdfgh435', 444, 555, 'sports', 'maccabi', 'ramat-gan', 'tel-aviv');
delete_business(8);*/

//manager
/*print_r(search_all_managers());
register_manager('gal', 'bar', 'galbar88@gmail.com', '0000', 'galb', '1111');*/

//print_r(search_coupons_by_location(101, 10));
//print_r(search_manager_by_manager_username('sadfsadfsadf'));
client_rate_coupon(1, 5);

?>
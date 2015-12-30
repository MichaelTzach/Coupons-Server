<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Coupons::Loging out...</title>
</head>

<body id="main_body" >

<!--	CONTROLLER VARS	-->
<?php
	include_once ('model/session_manager.php');
	logout();
	die ('<script>window.location.assign("index.php")</script>');
	?>
	</body>
</html>

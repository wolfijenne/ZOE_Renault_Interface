<?php
if (isset($_COOKIE['user_stored'])) {
	$user=openssl_decrypt($_COOKIE['user_stored'],"AES-128-ECB",$openssl_key);$_COOKIE['user_stored'];
} else {
	$user='';
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
		<title>ZOE</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
		<script src="js/jquery_min.js"></script>
		<script src="js/myjscode.js"></script>
<!-- 		<script language="javascript" type="text/javascript" src="js/dist/jquery.jqplot.min.js"></script> -->
<!-- 		<script language="javascript" type="text/javascript" src="js/cavas/jquery.cavas.min.js"></script> -->
		<script language="javascript" type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
		<script language="javascript" type="text/javascript" src="js/flot/jquery.flot.time.js"></script>
		<link rel="stylesheet" type="text/css" href="js/morris.js-0.5.1/morris.css" />
<!-- 		<link rel="stylesheet" type="text/css" href="js/dist/jquery.jqplot.css" /> -->
		<link rel="stylesheet" type="text/css" href="styles_screen.css">
		<link rel="stylesheet" type="text/css" media="screen and (max-width: 750px)" href="styles_mobile.css">
	</head>
	<body>
		<div class="wrapper">
			<div class="login_wrapper">
			<form name="login" action="index.php" method="post">
				<input type="hidden" value="true" name="loginrequest"/>
<?php
	echo '<input name="username" type="text" value="'.$user_name.'" placeholder="user@name.com"/>';
	echo '<input name="password" type="password" value="" placeholder="passwort"/>';
	echo '<input name="submit" type="submit" />';
?>
			</form>
			</div>
		</div>
	</body>
</html>


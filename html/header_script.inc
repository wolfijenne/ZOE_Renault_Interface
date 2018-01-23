<?PHP
header('Content-Type: text/html; charset=utf-8');
ini_set("display_errors", 0); 
error_reporting(E_ALL ^ E_WARNING ^E_NOTICE);
error_reporting(0);
$seitenlink="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
include_once('zoe_lib.inc');
if (isset($_COOKIE['user_logged_in'])) {
	$user=$_COOKIE['user_logged_in'];
	$benutzer=get_ze_user($user);
	if ($benutzer['ze_username']<>$_COOKIE['user_stored']) {
		setcookie("user_stored", '', time(),'/');
		setcookie("user_logged_in", '', time(),'/');
		$user=false;
	}
} else {
	$user=false;
	if ($_POST['loginrequest']=='true') {
		$user=openssl_encrypt($_POST['username'],"AES-128-ECB",$openssl_key);
		$pw=openssl_encrypt($_POST['password'],"AES-128-ECB",$openssl_key);
		$user=get_user_password($user,$pw);
		if (count($user)>0) {
			setcookie("user_logged_in", $user['id'], time()+24*3600*30,'/');
			setcookie("user_stored", $user['ze_username'], time()+24*3600*400,'/');
			$user=$user['id'];
		} else {
			$user=false;
			setcookie("user_stored", $user, time()+24*3600*400,'/');
		}
	}
}
if (!$user) {
	include_once('login_screen.php');
	die();
} 
?>
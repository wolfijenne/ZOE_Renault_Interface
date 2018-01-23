<?
include_once('zoe_lib.inc');
if ($_POST['command']<>'') {
if (($_GET['vid']<>'') && (is_numeric($_GET['vid']))) {
	$vehicles=get_vehicle_vid($_GET['vid']);	
} else {
	$vehicles=get_all_vehicles();
}
foreach ($vehicles as $vehicle) {
	if ($_POST['post']=='ja') {
		$result = json_decode(submit_request_post('https://www.services.renault-ze.com/api/vehicle/'.$vehicle['ze_vin'].'/'.$_POST['command'],$vehicle['ze_token']),true);
	} else {
		$result = json_decode(submit_request('https://www.services.renault-ze.com/api/vehicle/'.$vehicle['ze_vin'].'/'.$_POST['command'],$vehicle['ze_token']),true);
	}
	
	if (!is_array($battcond) || ($battcond['code']<>'')) {
		$data['username']=openssl_decrypt($vehicle['ze_username'],"AES-128-ECB",$openssl_key);
		$data['password']=openssl_decrypt($vehicle['ze_password'],"AES-128-ECB",$openssl_key);
		$accessdata = submit_request_login('https://www.services.renault-ze.com/api/user/login',$data);
		$accdata=json_decode($accessdata,true);
		if (count($accdata)>0) {
			$id_user=update_ze_user($accdata,$vehicle['zuid']);
			$id_vehicle=update_vehicle($accdata,$vehicle['zuid']);
		}
		if ($_POST['post']=='ja') {
			$result = json_decode(submit_request_post('https://www.services.renault-ze.com/api/vehicle/'.$vehicle['ze_vin'].'/'.$_POST['command'],$vehicle['ze_token']),true);
		} else {
			$result = json_decode(submit_request('https://www.services.renault-ze.com/api/vehicle/'.$vehicle['ze_vin'].'/'.$_POST['command'],$vehicle['ze_token']),true);
		}
	}


}
}
echo '<form name="explorer" action="explorer.php" method="POST">';
echo 'Command: <input type="text" name="command" value="'.$_POST['command'].'">';
if ($_POST['post']=='ja') {
	$checked='checked';
} else {
	$checked='';
}
echo 'Post: <input type="checkbox" name="post" value="ja" />';
echo '<input type="submit" value="submit" '.$checked.'/>';
echo '</form>'; 

echo '<br><br>'.$_POST['command'];
echo '<br>'.$_POST['post'];
echo '<br>';
	echo "<pre>"; 
		print_r($result); 
	echo "/<pre>"; 

?>
<?
include_once('zoe_lib.inc');
if (($_GET['vid']<>'') && (is_numeric($_GET['vid']))) {
	$vehicles=get_vehicle_vid($_GET['vid']);	
} else {
	$vehicles=get_all_vehicles();
}
foreach ($vehicles as $vehicle) {	
	$battcond = json_decode(submit_request('https://www.services.renault-ze.com/api/vehicle/'.$vehicle['ze_vin'].'/battery',$vehicle['ze_token']),true);
	if (!is_array($battcond) || ($battcond['code']<>'')) {
		$data['username']=openssl_decrypt($vehicle['ze_username'],"AES-128-ECB",$openssl_key);
		$data['password']=openssl_decrypt($vehicle['ze_password'],"AES-128-ECB",$openssl_key);
		$accessdata = submit_request_login('https://www.services.renault-ze.com/api/user/login',$data);
		$accdata=json_decode($accessdata,true);
		if (count($accdata)>0) {
			$id_user=update_ze_user($accdata,$vehicle['zuid']);
			$id_vehicle=update_vehicle($accdata,$vehicle['zuid']);
		}
		$battcond = json_decode(submit_request('https://www.services.renault-ze.com/api/vehicle/'.$vehicle['ze_vin'].'/battery',$accdata['token']),true);
	}
	update_battcondition($battcond,$vehicle['vid']);
	if (($battcond['charging']==1) && (time()-($battcond['last_update']/1000)<1800)) {
		$vehicle_single=get_vehicle_vid($vehicle['vid']);
		submit_request_post('https://www.services.renault-ze.com/api/vehicle/'.$vehicle_single[0]['ze_vin'].'/charge',$vehicle_single[0]['ze_token']);
	}

/*
	echo "<pre>"; 
		print_r($battcond); 
	echo "/<pre>"; 
*/

	
}
if (($_GET['vid']<>'') && (is_numeric($_GET['vid']))) {
	$res['charge_level']=$battcond['charge_level'];
	$res['last_update_string']='<span><br>Messung vom '.date('d.m.Y H:i',($battcond['last_update']/1000)).'</span>';
	$res['reichweite']=$battcond['remaining_range'];
	$res['reichweite100']=round($battcond['remaining_range']*100/$battcond['charge_level']);
	switch ($battcond['charging']) {
		case 1 : {
			$charging ='ja';
			break;
		}
		default : {
			$charging = ' - ';
			break;
		}
	}
	switch ($battcond['plugged']) {
		case 1 : {
			$plugged = 'ja';
			break;
		}
		default : {
			$plugged = ' - ';
			break;
		}
	}
	$res['plugged']=$plugged;
	$res['charging']=$charging;
	$res['charging_point']=$battcond['charging_point'];
	$res['remaining_time']=$battcond['remaining_time'];
	echo json_encode($res);
}
?>
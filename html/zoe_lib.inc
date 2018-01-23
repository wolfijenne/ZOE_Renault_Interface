<?
include('queries.inc');
function submit_request_login($url,$data_array) {
	$data=json_encode($data_array);
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json','Content-Length: ' . strlen($data)));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    $httpCode = curl_exec($ch);  
    curl_close($ch); 
	return $httpCode;
}
function submit_request($url,$token) {
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,array('Authorization : Bearer '.$token));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    $httpCode = curl_exec($ch);  
    curl_close($ch); 
	return $httpCode;
}
function submit_request_post($url,$token) {
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,array('Authorization : Bearer '.$token));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    $httpCode = curl_exec($ch);  
    curl_close($ch); 
	return $httpCode;
}
function get_ze_user($id) {
	$text="SELECT * FROM ze_users WHERE id='".$id."'";
	$res=make_query($text);
	return map_single($res);
}
function get_user_password($user,$pw) {
	$text="SELECT * FROM ze_users WHERE ze_username='".$user."' AND ze_password='".$pw."'";
	$res=make_query($text);
	return map_single($res);
}
function get_vehicle_vin($id) {
	$text="SELECT * FROM vehicles WHERE ze_vin='".$id."'";
	$res=make_query($text);
	return map_single($res);
}
function get_meas_data_timestamp($id_vehicle,$timestamp) {
	$text="SELECT * FROM vehicle_measurements WHERE id_vehicle='".$id_vehicle."' AND ze_time_stamp='".$timestamp."'";
	$res=make_query($text);
	return map_single($res);
}
function get_meas_data_timeframe($id_vehicle,$start,$end,$sort) {
	$text="SELECT * FROM vehicle_measurements WHERE id_vehicle='".$id_vehicle."' AND ze_time_stamp>".$start." AND ze_time_stamp<".$end." ORDER BY ze_time_stamp ".$sort;
	$res=make_query($text);
	return map_list($res);
}
function get_all_vehicles() {
	$text="SELECT v.id vid,v.ze_vin,zu.ze_username,zu.ze_password,zu.ze_token,zu.id zuid  
			FROM vehicles v
			LEFT JOIN ze_users zu ON zu.id=v.id_renault_user
			WHERE v.active<>'nein' AND zu.active<>'nein'";
	$res=make_query($text);
	return map_list($res);
}
function get_all_vehicles_uid($uid) {
	$text="SELECT v.id vid,v.ze_vin,zu.ze_username,zu.ze_password,zu.ze_token,zu.id zuid  
			FROM vehicles v
			LEFT JOIN ze_users zu ON zu.id=v.id_renault_user
			WHERE v.active<>'nein' AND zu.active<>'nein' AND zu.id='".$uid."'";
	$res=make_query($text);
	return map_list($res);
}
function get_vehicle_vid($vid) {
	$text="SELECT v.id vid,v.ze_vin,zu.ze_username,zu.ze_password,zu.ze_token,zu.id zuid,v.nom_capacity,v.soh  
			FROM vehicles v
			LEFT JOIN ze_users zu ON zu.id=v.id_renault_user
			WHERE v.active<>'nein' AND zu.active<>'nein' AND v.id='".$vid."'";
	$res=make_query($text);
	return map_list($res);
}
function update_ze_user($data,$id) {
	$text="UPDATE ze_users SET 
	ze_id='".$data['user']['id']."',
	ze_locale='".$data['user']['locale']."',
	ze_country='".$data['user']['country']."',
	ze_timezone='".$data['user']['timezone']."',
	ze_email='".$data['user']['email']."',
	ze_first_name='".$data['user']['first_name']."',
	ze_last_name='".$data['user']['last_name']."',
	ze_phone_number='".$data['user']['phone_number']."',
	ze_token='".$data['token']."',
	ze_refresh_token='".$data['refresh_token']."'
	WHERE id='".$id."'";
	$res=make_query($text);
	return $orig['id'];
}
function update_vehicle($data,$id){
	$vehicle=get_vehicle_vin($data['user']['vehicle_details']['VIN']);
	$text="ze_vin='".$data['user']['vehicle_details']['VIN']."',";
	$text.="ze_assoc_user='".$data['user']['id']."',";
	$text.="ze_activation_code='".$data['user']['vehicle_details']['activation_code']."',";
	$text.="ze_phone_number='".$data['user']['vehicle_details']['phone_number']."',";
	$text.="id_renault_user='".$id."'";
	if (count($vehicle)>0) {
		$text="UPDATE vehicles SET ".$text." WHERE id=".$vehicle['id'];
	} else {
		$text="INSERT INTO vehicles SET ".$text;
	}
	$res=make_query_i($text);
	$vehicle=get_vehicle_vin($data['user']['vehicle_details']['VIN']);
	return $vehicle['id'];
}
function update_battcondition($battcond,$id_vehicle) {
	$data=get_meas_data_timestamp($id_vehicle,round($battcond['last_update']/1000));
	if ((count($data)==0) && ($battcond['charge_level']>0))  {
		if ($battcond['plugged']==1) { $plugged='ja';} else { $plugged= 'nein';}
		if ($battcond['charging']==1) { $charging='ja';} else { $charging= 'nein';}
		$text="INSERT INTO vehicle_measurements SET ";
		$text.="id_vehicle='".$id_vehicle."',";
		$text.="ze_time_stamp='".round($battcond['last_update']/1000)."',";
		$text.="ze_plugged='".$plugged."',";
		$text.="ze_charging='".$charging."',";
		$text.="ze_charge_level='".$battcond['charge_level']."',";
		$text.="ze_remaining_range='".$battcond['remaining_range']."',";
		$text.="time_inserted='".time()."',";
		$text.="ze_charging_point='".$battcond['charging_point']."',";
		$text.="ze_remaining_time='".$battcond['remaining_time']."'";
		$res=make_query($text);
	}
}
function insert_soc_history($tage,$vid) {
	$start=time()-(3600*24*$tage);
	$end=$time();
	$werte=get_meas_data_timeframe($vid,$start,$end);	
}
?>
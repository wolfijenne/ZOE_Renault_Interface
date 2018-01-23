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
}
if (($_GET['vid']<>'') && (is_numeric($_GET['vid']))) {
	$end=time();
	if ($battcond['charging']==1) {
		$start=$end-(3*3600);
	} else {
		$start=$end-(3*24*3600);
	}
// History List immer die grosse Liste
	$start=$end-(14*24*3600);
	$data=get_meas_data_timeframe($_GET['vid'],$start,$end,'DESC');
	$t.='<ul class="liste">';
	$t.='<li class="titel"><div>Messzeit</div><div>DB Insert</div><div>SOC[%]</div><div>Rest[km]</div><div>Verbrauch</div><div>Ladestecker</div><div>Laden</div></li>';
	foreach ($data as $dat) {
		$t.='<li>';
		$t.='<div>'.date('d.m.Y H:i',$dat['ze_time_stamp']).'</div>';
		$t.='<div>'.date('d.m.Y H:i',$dat['time_inserted']).'</div>';
		$t.='<div>'.$dat['ze_charge_level'].' %</div>';
		$t.='<div>'.$dat['ze_remaining_range'].' km</div>';
		$t.='<div>'.round((($dat['ze_charge_level']*$vehicles[0]['nom_capacity']*$vehicles[0]['soh']/10000)/$dat['ze_remaining_range'])*100,1).' kWh/100km</div>';
		$t.='<div>'.$dat['ze_plugged'].'</div>';
		$t.='<div>'.$dat['ze_charging'].'</div>';
		$t.='<li>';
	}
	$t.='</ul>';
	$res['history_liste']=$t;

// Einzelwerte	
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
// Include Graphic
	include_once('ajax_get_soc_history.php');
	echo json_encode(array_merge($res,$history));
}
?>
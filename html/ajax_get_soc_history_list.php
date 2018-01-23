<?
include_once('zoe_lib.inc');
$end=time();
$start=$end-(14*24*3600);
if (($_GET['vid']<>'') && (is_numeric($_GET['vid']))) {
	$vehicle=get_vehicle_vid($_GET['vid']);
	$data=get_meas_data_timeframe($_GET['vid'],$start,$end,'DESC');

/*
	echo "<pre>"; 
		print_r($vehicle); 
	echo "</pre>"; 
*/
	
	$t.='<ul class="liste">';
	$t.='<li class="titel"><div>Messzeit</div><div>DB Insert</div><div>SOC[%]</div><div>Rest[km]</div><div>Verbrauch</div><div>Ladestecker</div><div>Laden</div></li>';
	foreach ($data as $dat) {
		$t.='<li>';
		$t.='<div>'.date('d.m.Y H:i',$dat['ze_time_stamp']).'</div>';
		$t.='<div>'.date('d.m.Y H:i',$dat['time_inserted']).'</div>';
		$t.='<div>'.$dat['ze_charge_level'].' %</div>';
		$t.='<div>'.$dat['ze_remaining_range'].' km</div>';
		$t.='<div>'.round((($dat['ze_charge_level']*$vehicle[0]['nom_capacity']*$vehicle[0]['soh']/10000)/$dat['ze_remaining_range'])*100,1).' kWh/100km</div>';
		$t.='<div>'.$dat['ze_plugged'].'</div>';
		$t.='<div>'.$dat['ze_charging'].'</div>';
		$t.='<li>';
	}
	$t.='</ul>';
}
	echo $t;
?>
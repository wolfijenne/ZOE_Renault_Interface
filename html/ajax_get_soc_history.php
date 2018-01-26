<?
include_once('zoe_lib.inc');
if (($_GET['vid']<>'') && (is_numeric($_GET['vid']))) {
	$vehicle=get_vehicle_vid($_GET['vid']);
	if (($_GET['start']>0) && (is_numeric($_GET['start'])) && ($_GET['end']>0) && (is_numeric($_GET['end']))) {
		$start=$_GET['start'];
		$end=$_GET['end'];
	} else {
		$end=time();
		if (($_GET['charging']==1) || ($charging=='ja')) {
			$start=$end-(3*3600);
		} else {
			$start=10;
		}
	}
	$data=get_meas_data_timeframe($_GET['vid'],$start,$end,'ASC');
/*
	echo "<pre>"; 
		print_r($data); 
	echo "</pre>"; 
*/
	$i=0;
	$j=0;
	$first=array();
	$second=array();
	foreach ($data as $dat) {
		$res['soc'][$i]=array((($dat['ze_time_stamp']+3600)*1000),$dat['ze_charge_level']);
		$i++;
		if ($dat['ze_charging']=='ja') {
			if (($first['ze_charge_level']>0) && ($second['ze_charge_level']>0)) {
				$res['power'][$j]=array((($dat['ze_time_stamp']+3600)*1000),((($dat['ze_charge_level']-$second['ze_charge_level'])*$vehicle[0]['nom_capacity']*$vehicle[0]['soh']/10000)/(($dat['ze_time_stamp']-$second['ze_time_stamp'])/3600)));
			} else {
				$res['power'][$j]=null;
				if ($first['ze_charge_level']>0) {
					$second=$dat;
				} else {
					$first=$dat;
				}
			}
			$j++;
		} else {
			$first=array();
			$second=array();
		}
	}
	$data=get_meas_data_timeframe($_GET['vid'],0,time(),'ASC');
	if (count($data)>0) {
		$prev=array();
		$i=0;
		$ladungen_html='<select name="ladungen">';
		$ladungen_html.='<option>Ladung auswählen</option>';
		for ($j=0;$j<count($data);$j++) {
			if (($data[$j]['ze_charging']=='ja') && ($data[$j-1]['ze_charging']<>'ja')) {
				$ladungen[$i]['start']=$data[$j]['ze_time_stamp'];
				$soc_start=$data[$j]['ze_charge_level'];
			}
			if (($data[$j]['ze_charging']<>'ja') && ($data[$j-1]['ze_charging']=='ja')) {
				$ladungen[$i]['end']=$data[$j]['ze_time_stamp'];
				$soc_end=$data[$j]['ze_charge_level'];
				if ($start==$ladungen[$i]['start']) {
					$selected=" selected";
				} else {
					$selected="";
				}
				$ladungen_html.='<option value="'.$i.'" start="'.$ladungen[$i]['start'].'" end="'.$ladungen[$i]['end'].'"'.$selected.'>'.date('d.m.Y H:i',$ladungen[$i]['start']).' : '.$soc_start.'% - '.$soc_end.'%</option>';
				$i++;
			}
		}
		$ladungen_html.='</select>';
		$res['ladungen']=$ladungen_html;
	}
}
	if (basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING'])=='ajax_get_soc_history.php') {
		echo json_encode($res);
	} else {
		$history = $res;
 	}
?>
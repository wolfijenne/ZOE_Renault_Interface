<?
include_once('zoe_lib.inc');
if (($_GET['vid']<>'') && (is_numeric($_GET['vid'])) && ($_GET['action']<>'')) {
	$vehicle=get_vehicle_vid($_GET['vid']);
	if ((count($vehicle[0])>0)) {
		switch ($_GET['action']) {
			case 'updatesoc': {
				submit_request_post('https://www.services.renault-ze.com/api/vehicle/'.$vehicle[0]['ze_vin'].'/battery',$vehicle[0]['ze_token']);
				echo 'SOC Request gesendet. Es wird aber nur eine Antwort kommen wenn das Fahrzeug lädt';
				break;
			}
			case 'chargebat': {
				submit_request_post('https://www.services.renault-ze.com/api/vehicle/'.$vehicle[0]['ze_vin'].'/charge',$vehicle[0]['ze_token']);
				echo 'Laderequest gesendet, wenn das Fahrzeug nicht lädt, wird es geweckt und der SOC abgefragt';
				break;
			}
			case 'aircon': {
				submit_request_post('https://www.services.renault-ze.com/api/vehicle/'.$vehicle[0]['ze_vin'].'/air-conditioning',$vehicle[0]['ze_token']);
				echo 'Klimarequest gesendet. Ist die ZOE in Reichweite und SOC>25%, wird mit der Vorklimatisierung begonnen';
				break;
			}
		}
	}
}
?>
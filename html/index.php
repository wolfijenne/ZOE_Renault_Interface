<?
include_once('header_script.inc');
include_once('header_html.inc');
$vehicles=get_all_vehicles_uid($user);
/*
echo "<pre>"; 
 print_r($vehicles); 
echo "/<pre>"; 
*/

foreach ($vehicles as $vehicle) {	
echo '<div class="vehicle" id="'.$vehicle['vid'].'">';
echo '<div class="message"></div>';
echo '<div class="battery"><div class="soc" style="width:0%;"></div>';
echo '<div class="text"></div></div>';
echo '<ul class="battinfo">';
echo '<li class="line reichweite">';
echo '<div class="titel">Restreichweite: </div><div class="wert"></div>';
echo '</li><li class="line reichweite100">';
echo '<div class="titel">Restreichweite bei 100% SOC: </div><div class="wert"></div>';
echo '</li><li class="line plugged">';
echo '<div class="titel">Ladestecker: </div><div class="wert"></div>';
echo '</li>';
echo '<li class="line charging">';
echo '<div class="titel">Lädt: </div><div class="wert"></div>';
echo '</li>';
echo '<li class="line charging_point">';
echo '<div class="titel hidden">Ladepunkt: </div><div class="wert hidden"></div>';
echo '</li>';
echo '<li class="line remaining_time ">';
echo '<div class="titel hidden">Ladedauer: </div><div class="wert hidden"></div>';
echo '</li>';
echo '</ul>';
echo '<ul class="buttons">';
echo '<li class="button" action="forceupdate" vid="'.$vehicle['vid'].'">Force Update</li>';
echo '<li class="button" action="chargebat" vid="'.$vehicle['vid'].'">Start Ladung</li>';
echo '<li class="button" action="aircon" vid="'.$vehicle['vid'].'">Start Klima</li>';
echo '</ul>';
echo '<div id="soc_plot"></div>';
echo '<div id="ladungen">Ladungen: <span class="pulldown"></span>&nbsp;&nbsp;<span class="button get_soc">Zur aktuellen Grafik</span></div>';
echo '<div id="ergebnisliste"></div>';
echo '</div>';
}

?>
		</div>	
	</body>
</html>

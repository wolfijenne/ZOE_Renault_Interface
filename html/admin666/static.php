<?php
error_reporting(255);
ini_set("display_errors", 1);
//
//

$db='spider_zoe';
$dbhost='localhost';	
$dbuser='zoe_user';
$dbpass='d5Kmz77?';

//
// Filetypes für Bilder
$f_types=array('gif','jpg','png','jpeg','pdf','doc','xls','txt','JPG','JPEG','GIF','V11','PNG','PDF','svg','SVG','vwx','VWX','mp3','MP3');
// Tabelle für Bilder
$f_table="images";
// Directory für Bilder
$f_directory='bilder/';
// Maximale Dateigrøsse
$f_max_size=5000000;
// Maximale Bilddimensionen
$f_max_size_x=400;
$f_max_size_y=400;
// Ordner für Dateien (Save)
$file_place='../downloads/';
// Extensions
$f_extensions=array('','gif','jpg','png','swf','pdf','xls','txt','doc','JPG','JPEG','GIF','V11','PNG','PDF','svg','SVG','vwx','VWX','mp3','MP3');
// ENDE KONFIGURATIONEN
$f_types_resizable=array('gif','jpg','png','pdf','GIF','JPG','JPEG','jpeg','PNG');
error_reporting(7);
$salt='abc';
$salt_app='XcRl2a';
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $db);
$mysqli->set_charset("utf8");
$openssl_key="2345gneekc6t3FRum43d%Dueb4icdtheRDCjrmfz3765jetckf";
$openssl_vector="7548372";
?>
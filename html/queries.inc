<?php
include("admin666/static.php");
// include_once("admin/form_lib.inc");
function cleanup($string) {
	parse_str($string,$arr);
	$str=http_build_query($arr);
	return $str;
}
function remove_attr_query_string($str,$param) {
	$parts = parse_url($str);
	$queryParams = array();
	parse_str($parts['query'], $queryParams);
	unset($queryParams[$param]);
	$queryString = http_build_query($queryParams);
	return $queryString;
}
function get_file_size($file) {
	$bild=get_image($file);
	$size=round($bild['size_bytes']/1024);
	$end=' kB';
	if ($size>1024) {
		$size=round($size/1024);
		$end=' MB';
	}
	if ($size>1024) {
		$size=round($size/1024);
		$end=' GB';
	}
	return $size.$end;
}
function echo_array($array) {
	reset($array);
	while (list($key,$val) = each($array)) {
		if (is_array($val)) {
			$t.= $key.' ';
			$t.=echo_array($val);
			$t.= '<br>';
		} else {
			$t.= $key.' '.$val.'<br>';
		}
	}
	return $t;
}
function insert_image($image,$text) {
	global $var;
	$bild=get_image($image);
	if (count($bild)>0) {
		if ($bild['size_x']<1) { $sizex='';} else { $sizex='  width="'.$bild['size_x'].'"';}
		if ($bild['size_y']<1) { $sizey='';} else { $sizey=' height="'.$bild['size_y'].'"';}
		if ($align<>"") {$a=' align="'.$align.'"'; } else {$a="";}
		return '<img src="'.$var['bilder_pfad'].$image.'"'.$sizex.$sizey.' border="0" alt="'.$text.'"  title="'.$text.'" '.$a.'/>';
	} else {
		return '';
	}
}	
function check_email_address($email) {
	if (!preg_match("/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/", $email)) {
		return false;
	} else {
		return true;
	}
}
function insert_image_name($image,$align,$name) {
	global $var;
	$bild=get_image($image);
   	if ($bild['size_x']>0) { $width=' width="'.$bild['size_x'].'"';} else {$width="";}
   	if ($bild['size_y']>0) { $height=' height="'.$bild['size_y'].'"';} else {$height="";}
	return '<img src="'.$var['bilder_pfad'].$image.'" name="'.$name.'" '.$width.$height.'" border="0" alt="'.$bild['org_name'].'" align="'.$align.'"/>';
}
function insert_image_hover($image,$image_hover,$alt,$index) {
	global $var;
	$t='<img number="'.$index.'" src="'.$var['bilder_pfad'].$image.'" border="0" alt="'.$alt.'" align="center" data-hover="'.$var['bilder_pfad'].$image_hover.'"/>';
	return $t;
}
function insert_object($image,$text) {
	global $var;
	if ($image<>'') {
		$bild=get_image($image);
		$size=round($bild['size_bytes']/1024);
		$ext=strtolower($bild['ext']);
		if ((((($ext<>'jpeg') and ($ext<>'jpg') and ($ext<>'gif'))) and ($ext<>'jpeg')) and ($ext<>'png')) {
			echo "\n";
			$result.=insert_file_download($image,$text);
			$result.="\n";
		    } else {
		    	if ($text<>'') {
		    		$alt=out($text);
		    	} else {
		    		$alt=$bild['org_name'];
		    	}
		    	if ($bild['size_x']>0) { $width=' width="'.$bild['size_x'].'"';} else {$width="";}
		    	if ($bild['size_y']>0) { $height=' height="'.$bild['size_y'].'"';} else {$height="";}
				$result='<img src="'.$var['bilder_pfad'].$image.'" '.$width.$height.' border="0" alt="'.$alt.'"/>';
				$result.="\n";
		    }
	} else {
		$result='';
	}
		return $result;
}
function insert_downloadable_object($image,$y,$x,$text) {
	global $var;
	global $static;
		$bild=get_image($image);
		$size=round($bild['size_bytes']/1024);
		if (((strtolower($bild['ext'])<>'jpeg') and (strtolower($bild['ext'])<>'gif') and (strtolower($bild['ext'])<>'jpg')) and (strtolower($bild['ext'])<>'png')) {
			echo "\n";
//			$result=insert_file($image,$text);
			$result=insert_file_download($image,$bild['org_name']);
			$result.="\n";
		    } else {
		    	if (($bild['size_x']/$bild['size_y'])>2) { $si='width="'.$x.'"';} else { $si='height="'.$y.'"';}
		    	$result='<div class="download_pic" style="text-align:center;"><a href="download.php?file='.$image.'">';
				$result.='<img src="'.$var['bilder_pfad'].$image.'" '.$si.' border="0" alt="'.$bild['org_name'].'" align="'.$align.'"/></a>';
				$result.='<br>'.$bild['org_name'];
				if ($text<>'') {
					$result.='<div class="press_layer">'.out($text).'</div>';
				}
				$result.='</div>';
				$result.="\n";
		    }
		return $result;
}
function insert_file_download($fil,$text) {
	$file=get_image($fil);
	$size=round($file['size_bytes']/1024);
	$icon='images/but_'.$file['ext'].'.gif';
	if (!file_exists($icon)) {
		$icon='images/but_file.gif';
	}
	if ($text=="") {$text=$file['org_name'];}
	$string.='<a href="download.php?file='.$fil.'"  class="link_file">'.$text.' ('.$size.'&nbsp;kB)</a>'."\n";
	return $string;
}
function insert_file($fil,$text) {
	global $var;
	$file=get_image($fil);
	$size=round($file['size_bytes']/1024);
	$icon='images/but_'.$file['ext'].'.gif';
	if (!file_exists($icon)) {
		$icon='images/but_file.gif';
	}
	if ($text=="") {$text=$file['org_name'];}
	$string='&nbsp;<a href="'.$var['bilder_pfad'].$fil.'" target="_blank" class="link_file"><img src="'.$icon.'" border="0" align="top"/></a>';
	$string.='<a href="bilder/'.$fil.'" target="_blank" class="link_file">'.$text.'&nbsp;('.$size.'&nbsp;kB)</a>';
	return $string;
}
function insert_link($link,$text,$class) {
	if (($link<>"") AND ($text=="")) { $text=$link;}
	if (strstr($link,'http://') || (strstr($link,'https://'))) {
		$target='_blank';
	} else {
		$target='_self';
	}
	if ($class<>"") {
		$klasse=' class="'.$class.'"';
	} else {
		$klasse="";
	}
	if (($link=="") AND ($text=="")) {
		$result="&nbsp;";
	} else {
		if (($link=="") AND ($text<>"")) {
			$result=$text;
		} else {
			$result='<a href="'.$link.'" target="'.$target.'" class="'.$class.'">'.$text.'</a>';
		}
	}
	return $result;
}

function get_image($bild) {
	$id=substr($bild,0,strrpos($bild,'.'));
	$text="select * from wapp_images where id='".$id."'";
	$resultat=make_query($text);
	return map_single($resultat);
}
function get_pfad($gets) {
	reset($gets);
    $i=0;
    $vars='?';
	while (list($key,$val) = each($gets)) {
    if ($i>0) {$vars.='&';}
		$vars.=$key.'='.$val;
	    $i++;
	}
    return $vars;
}
function get_pfad_except($gets,$var) {
	reset($gets);
    $i=0;
    $vars='?';
	while (list($key,$val) = each($gets)) {
    if ($i>0) {$vars.='&';}
		if ($key<>$var) {
			$vars.=$key.'='.$val;
		}
	    $i++;
	}
    return $vars;
}
function build_url_pars($var) {
	reset($var);
         $i=0;
	while (list($key,$val) = each($var)) {
                  if ($i>0) {$vars.='@';}
		$vars.=$key.'$'.$val;
                  $i++;
	}
         return $vars;
}

function parse_url_str($var) {
        $arr=explode('@',$var);
        $link='?';
        for ($i=0;$i<count($arr);$i++) {
             $arr1=explode('$',$arr[$i]);
             if ($i>0) { $link.='&'; }
             $link.=$arr1[0].'='.$arr1[1];
       }
       return $link;
}
function out($text1) {
	$text1=str_replace("\n/*",'<li>',$text1);
	$text1=str_replace("/*",'<li>',$text1);
	$text1=str_replace("*/\n",'</li>',$text1);
	$text1=str_replace("*/",'</li>',$text1);
	$text1=str_replace("&gt;",'>',$text1);
	$text1=str_replace("&lt;",'<',$text1);
	$text1=str_replace("<br>","",$text1);
	$text1=str_replace("<br />","",$text1);
	$text1=str_replace("\n","<br>\n",$text1);
	$text1=trim($text1);
	return $text1;
}
function list_vars($var) {
	reset($var);
	while (list($key,$val) = each($var)) {
		echo $key.': '.$val.'<br>';
	}
}
function get_parameter($get,$exclusion) {
	reset($get);
	$first=true;
	while (list($key,$val) = each($get)) {
		$gut=true;
		for ($i=0;$i<count($exclusion);$i++) {
			if ($key==$exclusion[$i]) { $gut=false;}
		}
		if ($gut) {
			if (!$first) {$par.='&';}
			$par.=$key.'='.$val;
			$first=false;
		}
	}
	return $par;
}
///////////////////////////////
//////Applikations Queries/////
///////////////////////////////

//////////////////////////////////////
//////SYSTEM FUNKTIONEN///////////////
//////////////////////////////////////
function map_list($resultat) {
	if ($resultat) {
		$resultat->data_seek(0);
		$i=0;
		while ($row = $resultat->fetch_assoc()) {
		    $res[$i]=$row;
		    $i++;
		}
		return $res;
	} else {
		return null;
	}
}
function map_single($res) {
	if ($res) {
		$res->data_seek(0);
		$row = $res->fetch_assoc();
		return $row;
	} else {
		return null;
	}
}
function get_vars() {
	$text="select * from wapp_vars";
	$resultat=make_query($text);
	if ($resultat) {
		$resultat->data_seek(0);
		while ($row = $resultat->fetch_assoc()) {
		    $res[$row['name']]=$row['value'];
		}
		return $res;
	} else {
		return false;
	}
}
function delete_from_db($id,$table) {
	$text='delete from '.$table.' where id='.$id;
	$result=make_query($text);
}
function make_query($query) {
	global $db,$dbpass,$dbuser,$dbhost;
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $db);
 	$mysqli->set_charset("utf8");
	if ($mysqli->connect_errno) {
	    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	
	$res = $mysqli->query($query);
	return $res;
}
function make_query_old($query) {
	global $db_old,$dbpass_old,$dbuser_old,$dbhost_old;
	$mysqli = new mysqli($dbhost_old, $dbuser_old, $dbpass_old, $db_old);
 	$mysqli->set_charset("utf8");
	if ($mysqli->connect_errno) {
	    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	
	$res = $mysqli->query($query);
	return $res;
}
function make_query_i($query) {
	global $db,$dbpass,$dbuser,$dbhost;
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $db);
 	$mysqli->set_charset("utf8");
	if ($mysqli->connect_errno) {
	    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	$res = $mysqli->query($query);
	return $mysqli->insert_id;
}
function check_user($user) {
	$text="select user from wsys_users where user='".$user."'";
	$resultat=make_query($text);
	if (mysql_num_rows($resultat)>0) {$erg=false;} else {$erg=true;}
	return $erg;
}
function get_statics($lan,$search) {
	$text="select ".$lan.
			",text_id from wapp_static where page='".$search."'";
	$resultat=make_query($text);
	$resultat->data_seek(0);
	while ($row = $resultat->fetch_assoc()) {
	    $res[$row['text_id']]=$row[$lan];
	}
	return $res;
}
?>
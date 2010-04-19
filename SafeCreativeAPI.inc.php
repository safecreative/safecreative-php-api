<?php
/*
	Copyright 2010 Safe Creative

	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

define("MANAGE_ENDPOINT","/api-ui/authkey.edit?");
define("ACCESS_LEVEL_GET","GET"); //Read only access
define("ACCESS_LEVEL_ADD","ADD"); //R/W Access
define("ACCESS_LEVEL_MANAGE","MANAGE"); //Full access

if(!defined("API_SEARCH_URL") && defined("API_URL")) {
	define("API_SEARCH_URL",API_URL);
}

function msg($msg) {
	echo "$msg<br>\n";
}

function debug($msg) {
	if(defined("DEBUG")) {
		if(is_string($msg)) {
			msg($msg);
		} else {
			echo "<pre>";
			print_r($msg);
			echo "</pre>";
		}
	}
}

function equals($s1,$s2) {
	return strcmp($s1,$s2) == 0;
}

function debugCall($url,$title=NULL) {
	if(empty($title)) {
		$title = $url;
	}
	debug("apiCall(<a href=\"$url\">$title</a>)");
}

function isAuthorized($authKey=AUTH_KEY) {
	return "true" == getAuthKeyState($authKey)->authorized;
}

function getManageAuthkeyUrl($authKey,$privateKey,$authKeyLevel = ACCESS_LEVEL_MANAGE) {
	$params = array(
		"level" => $authKeyLevel,
		"authkey" => $authKey ,
		"sharedkey" => SHARED_KEY,
		"ztime" => getZTime()
	);
	return API_URL . MANAGE_ENDPOINT . signParams($params,$privateKey);
}

function getNonceKey($authKey=AUTH_KEY) {
	return getAuthKeyState($authKey)->noncekey;
}

function getAuthKeyState($authKey=AUTH_KEY) {
	$params = array("component" => "authkey.state","authkey" => $authKey , "sharedkey" => SHARED_KEY);
	return callSigned($params,true);
}

function signParams($params,$privateKey) {
	ksort($params);
	$unencoded=$encoded="";
	foreach($params as $param => $value) {
		if(!equals("debug-component",$param)) {
			$unencoded .= "&".$param."=".$value;
		}
		$encoded .= "&".$param."=".rawurlencode($value);
	}
	$unencoded=substr($unencoded,1);
	$encoded=substr($encoded,1);
	return $encoded . "&signature=" .sha1($privateKey."&".$unencoded);
}

function callSigned($params,$ztime=false,$nonceKey=false,$privateKey=PRIVATE_KEY,$apiUrl=API_URL) {
	if($ztime) {
		$params["ztime"] = getZTime();
	}
	if($nonceKey) {
		$params["noncekey"] = getNonceKey();
	}
	return call(signParams($params,$privateKey),$apiUrl);
}

function getZTime() {
	return (string)call(array("component" => "ztime") );
}

function search($params=array(),$apiUrl=API_SEARCH_URL) {
	return call($params,$apiUrl);
}

function call($params=array(),$apiUrl=API_URL) {
	$apiUrl.= "/v2/?";
	$reqParams="";

	if($params && is_array($params)) {
		foreach($params as $param => $value) {
			$reqParams .= "&".$param."=".rawurlencode($value);
		}
		$reqParams=substr($reqParams,1);
	}else {
		$reqParams=$params;
	}
	$url=$apiUrl.$reqParams;
	debugCall($url,$reqParams);
	$result = @file_get_contents($url) or die("Can not connect or read failed");
	$result = simplexml_load_string($result);
	return  $result;
}
?>

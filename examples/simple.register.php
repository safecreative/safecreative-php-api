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

//Arena config to start playing with the api
include("../SafeCreativeAPI.config.arena.php");
//Production config (Api key must be approved by Safe Creative to become active!)
//include("../SafeCreativeAPI.config.production.php");

include("../SafeCreativeAPI.inc.php");

function createAuthKeysAndShowAuthURL() {
	//Create auth keys
	$params = array(
		"component" => "authkey.create",
		"sharedkey" => SHARED_KEY
	);
	$result = callSigned($params,true,false);
	msg("This auth key and private key have just been generated");
	msg("You should authorize them by following the link");
	msg("and copy its values in SafeCreativeAPI.config.arena.php for this sample to work");
	msg("AUTH_KEY=" . $result->authkey);
	msg("AUTH_PRIVATE_KEY=" . $result->privatekey);
	//and build auth url to authorize them
	$url = getManageAuthkeyUrl($result->authkey,$result->privatekey,ACCESS_LEVEL_ADD);
	msg("<a href=\"$url\" target=\"_new\">Authorize</a>");
}


//Simple register example:
if(isAuthorized()) {
	//Simple text register
	$params = array(
		"component" => "work.register",
		"authkey" => AUTH_KEY,
		"title" => "El Quijote",
		"text" => "En un lugar de la mancha..",
		"worktype" => "article"
	);
	$result = callSigned($params,true,true,AUTH_PRIVATE_KEY);
	if($result->code) {
		die("Work registered with code ".$result->code);
	} else {
		die("Failed registration: ".$result->errorMessage);
	}
} else {
	createAuthKeysAndShowAuthURL();
}

?>

<?php
/*
	Copyright 2010-2011 Safe Creative

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

//User link example:
//Warn this example requires PARTNER level!!
$params = array(
	"component" => "user.link",
	"sharedkey" => SHARED_KEY,
	"level" => ACCESS_LEVEL_MANAGE,
	"mail" => "user@mailhost.com",
	"firstName" => "User first name",
	"lastName" => "User last name"
);
$result = callSigned($params,true);
if($result->usercode) {
	msg("user auth key:".$result->authkey);
	msg("user auth private key:".$result->privatekey);
	die("User registered with code ".$result->usercode);
} else {
	debug($result);
	die("Failed: ".$result->errorMessage);
}
?>

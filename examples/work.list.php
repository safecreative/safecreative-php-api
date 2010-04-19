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
//Production config (you can enable this, no key neeeded to search!)
//include("../SafeCreativeAPI.config.production.php");

include("../SafeCreativeAPI.inc.php");

define("DEBUG",true);

function showWorks($paginatedResults) {
	//debug($paginatedResults);
	if($paginatedResults && $paginatedResults->recordtotal) {
		msg("Total pages: ".$paginatedResults->pagetotal);
		msg("Total results: ".$paginatedResults->recordtotal);
		foreach($paginatedResults->list->work as $work) {
			$workUrl = $work->{'human-url'};
			$thumbnail = $work->thumbnail;
			msg("<a href=\"$workUrl\"><img src=\"$thumbnail\" title=\"".$work->title."\" align=\"top\"></a> <a href=\"$workUrl\">".$work->title."</a>");
		}
	} else {
		msg("No work results available");
	}
}

//List own works:
$params = array(
	"component" => "work.list",
	"authkey" => AUTH_KEY
	//,"page" => 1
);

$results = callSigned($params,true,false,AUTH_PRIVATE_KEY);
showWorks($results);
?>

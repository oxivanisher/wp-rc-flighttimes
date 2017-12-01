<?php
// Herzogenbuchsee
// Breite 47.1881119
// Laenge 7.7009241

// Settings
$GLOBALS['lat'] = 47.1881119;
$GLOBALS['long'] = 7.7009241;
$GLOBALS['zenith'] = 90+50/60;
$GLOBALS['offset'] = 2;

// Functions
function getSunData($dstDate) {
	$ret = array();
	array_push($ret, date_sunrise ($dstDate, SUNFUNCS_RET_TIMESTAMP, $GLOBALS['lat'], $GLOBALS['long'], $GLOBALS['zenith'], $GLOBALS['offset']));
	array_push($ret, date_sunset ($dstDate, SUNFUNCS_RET_TIMESTAMP, $GLOBALS['lat'], $GLOBALS['long'], $GLOBALS['zenith'], $GLOBALS['offset']));
	return $ret;
}

function genTime($timestamp) {
	return strftime("%H:%M", $timestamp);
}

function genDuration($duration) {
	$ret = "";
	if ($hour = floor($duration / 3600))
		$ret .= $hour . "h ";
	if ($min = floor((($duration - ($hour * 3600)) / 60)))
		$ret .= $min . "min ";
	//if ($sec = $duration - ($hour * 3600) - ($min * 60))
	//	$ret .= sprintf( '%02d', $sec) . "sec ";
	return $ret;
}

function genOutput($event, $day, $duration, $time) {
	$ret = "";
//	$ret .= "Debug: Now: " . genTime() . "<br />";
	return $ret . "<b>" . $event . "</b> wird es " . $day . " <b>in " .  $duration . "</b> um " . $time . " Uhr.";
}

function renderOutput($myTime) {
	$data = getSunData($myTime);
	if ($myTime < $data[0]) {
		return genOutput ("Hell", "Heute", genDuration($data[0] - $myTime), genTime($data[0]));
	} elseif ($myTime < $data[1]) {
		return genOutput ("Dunkel", "Heute", genDuration($data[1] - $myTime), genTime($data[1]));
	} else {
		$tmpSunData = getSunData($myTime + (24 * 60 * 60));
		return genOutput ("Hell", "Morgen", genDuration($tmpSunData[0] - $myTime), genTime($tmpSunData[0]));
	}
}

echo "<html><head><title>Sun Data</title>";
echo "<style type='text/css'>body { margin: 0px; font-family: sans-serif; } a:link { text-decoration: none; }</style></head><body>";
echo "<a href='sunset2.php' target='_new'>" . renderOutput(time()) . "</a>";
echo "</body></html>";
?>

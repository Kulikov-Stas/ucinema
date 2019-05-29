<?php
require_once("./config.php");

$mode = @$_GET['mode'];
$action = @$_GET['AJAXaction'];

/**
 * Encode windows-1251 string to UTF-8
 * Example: it is working same as 'iconv("windows-1251", "UTF-8", $sInput)'
 *
 * @param mixed $sInput data to convert
 * @return mixed converted to UTF-8 data
 */
function cp1251_utf8($sInput) {
	$sOutput = "";
	for ($i=0;$i<strlen($sInput);++$i) {
		$iAscii = ord($sInput[$i]);
        if ($iAscii >= 192 && $iAscii <= 255)
			$sOutput.= "&#".(1040+($iAscii - 192)).";";
		else if ($iAscii == 168)
			$sOutput.= "&#".(1025).";";
		else if ($iAscii == 184)
			$sOutput.= "&#".(1105).";";
		else
			$sOutput.= $sInput[$i];
	}
	return $sOutput;
}

require_once('modules/'.$mode.'/'.$mode.'.php');
$module = new $mode();
echo $module->AJAX($action);

?>
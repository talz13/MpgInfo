<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/MpgDb.php';

$maxSizeX = 1920;
$maxSizeY = 1080;
$minSizeX = 100;
$minSizeY = 75;
$defaultX = 800;
$defaultY = 600;

function getImgSize($inputArray)
{
	$xSize = $GLOBALS['defaultX'];
	$ySize = $GLOBALS['defaultY'];
	$fontRatio = $xSize / $GLOBALS['defaultX'];
	if (array_key_exists('sizex', $_GET) && array_key_exists('sizey', $_GET))
	{
		$xSize = intval($_GET['sizex']);
		$ySize = intval($_GET['sizey']);
		if ($xSize > $GLOBALS['maxSizeX'] || $xSize < $GLOBALS['minSizeX'])
			$xSize = $GLOBALS['defaultX'];
		if ($ySize > $GLOBALS['maxSizeY'] || $ySize < $GLOBALS['minSizeY'])
			$ySize = $GLOBALS['defaultY'];
		$fontRatio = $xSize / $GLOBALS['defaultX'];
	}
    return array($xSize, $ySize, $fontRatio);
}

function checkLogin()
{
	if (isset($_COOKIE['login']))
	{
		$loginArray = unserialize($_COOKIE['login']);
        $db = new MpgDb();
		$query = sprintf("select password from users where username='%s'", $loginArray[0]);
		$result = $db->runQuery($query);
		if ($db->getRowCount() > 0)
		{
			$row = $db->getRow();
			//foreach($loginArray as $item)
			//{
			//	echo "<br>Item: ".$item;
			//}
			if ($loginArray[1] == $row['password'])
			{
				//echo "<br>return true 1";
				return true;
			}
			else
			{
				//echo "<br>return false 1";
				return false;
			}
		}
		else
		{
			//echo "<br>return false 2";
			return false;
		}
	}
	else
	{
		//echo "<br>return false 3";
		return false;
	}
}
function getUsernameFromCookie()
{
	if (isset($_COOKIE['login']))
	{
		$loginArray = unserialize($_COOKIE['login']);
		return $loginArray[0];
	}
	else
	{
		return "";
	}
}

function buildLocalPath($rootRelativePath) {
    if ($rootRelativePath != '' and (strpos($rootRelativePath, '/') != 0 or strpos($rootRelativePath, '\\') === 0)) {
        $rootRelativePath = substr($rootRelativePath, 1, strlen($rootRelativePath) - 1);
    }
    if(isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else {
        $protocol = 'http';
    }
    return sprintf('%s://%s%s', $protocol, Config::getBaseUrl(), $rootRelativePath);
}
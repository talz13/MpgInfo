<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/MpgDb.php';

$maxSizeX = 1920;
$maxSizeY = 1080;
$minSizeX = 100;
$minSizeY = 75;
$defaultX = 800;
$defaultY = 600;

function getImgSize($inputArray) {
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

function checkLogin() {
    return isset($_SESSION['userId']) ? true : false;
}

function displayLoginLink() {
    printf('<table><tr><td><h2>Please <a href="%s">log in</a></h2></td></tr></table>', buildLocalPath('/login.php'));
}

function getUserId() {
	if (isset($_SESSION['userId']))	{
        return $_SESSION['userId'];
	} else {
		return -1;
	}
}

function setUserId($id) {
    $_SESSION['userId'] = $id;
}

function filterPost($key) {

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

function startMpgSession() {
    if (!isset($_SESSION)) {
        session_start();
    }
}
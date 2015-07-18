<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';

startMpgSession();

include $_SERVER['DOCUMENT_ROOT'] . '/lib/header.php';

if (checkLogin())
{
	$con = mysql_connect($dbHost, $dbUser, $dbPass);
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($db, $con);

}
include $_SERVER['DOCUMENT_ROOT'] . '/lib/footer.php';
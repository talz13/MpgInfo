<?php
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/globals.php';

startMpgSession();

include filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/header.php';

if (checkLogin())
{
	$con = mysql_connect($dbHost, $dbUser, $dbPass);
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($db, $con);

}
include filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/footer.php';

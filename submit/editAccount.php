<?php
include "../lib/globals.php";
include "../lib/header.php";
include "../lib/funcs.php";

if (checkLogin())
{
	$con = mysql_connect($dbHost, $dbUser, $dbPass);
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($db, $con);

}
include "../lib/footer.php";
?>


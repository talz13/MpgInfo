<?php
include "globals.php";

$maxSizeX = 1920;
$maxSizeY = 1080;
$minSizeX = 100;
$minSizeY = 75;
$defaultX = 800;
$defaultY = 600;

function getConn()
{
	$mysqli = new mysqli($GLOBALS['dbHost'], $GLOBALS['dbUser'], $GLOBALS['dbPass'], $GLOBALS['db']);
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	return $mysqli;
}

function getImgSize($inputArray)
{
	$returnArray = array();
	$sizex = $GLOBALS['defaultX'];
	$sizey = $GLOBALS['defaultY'];
	$fontRatio = 1;
	// Get size from GET:
	if (array_key_exists('sizex', $_GET) && array_key_exists('sizey', $_GET))
	{
		$sizex = intval($_GET['sizex']);
		$sizey = intval($_GET['sizey']);
		if ($sizex > $GLOBALS['maxSizeX'] || $sizex < $GLOBALS['minSizeX'])
			$sizex = $GLOBALS['defaultX'];
		if ($sizey > $GLOBALS['maxSizeY'] || $sizey < $GLOBALS['minSizeY'])
			$sizey = $GLOBALS['defaultY'];
		$fontRatio = $sizex / $GLOBALS['defaultX'];
		$returnArray = array($sizex, $sizey, $fontRatio);
	}
	else
	{
		$sizex = $GLOBALS['defaultX'];
		$sizey = $GLOBALS['defaultY'];
		$fontRatio = $sizex / $GLOBALS['defaultX'];
		$returnArray = array($sizex, $sizey, $fontRatio);
	}
	return $returnArray;
}
function getGraphType($inputArray)
{
	$type = 'line';
	// Get type from GET:
	if (array_key_exists('type', $_GET))
	{
		if ($_GET['type'] == 'line')
			$type = 'line';
		elseif ($_GET['type'] == 'bar')
			$type = 'bar';
		elseif ($_GET['type'] != 'line' && $_GET['type'] != 'bar')
			$type = 'line'; 
	}
	else
	{
		$type = 'line';
	}
	return $type;
}
function getGraphProcName($con, $inputArray)
{
	$graphName = '';
	// Get graph name from GET:
	if (array_key_exists('graphName', $_GET))
	{
		$tempGraphName = ucwords($_GET['graphName']);
		$result = mysqli_query($con, "select routine_name from `information_schema`.`routines`");
		while ($row = mysqli_fetch_assoc($result))
		{
			if ($row['routine_name'] == "sp_graph".$tempGraphName)
			{
				$graphName = ucwords($row['routine_name']);
				break;
			}
		}
		//$graphName = ucwords(mysqli_real_escape_string($con, $_GET['graphName']));
	}

	return $graphName;
}

function checkLogin()
{
	$con = mysql_connect($dbHost, $dbUser, $dbPass);
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($db, $con);
	//foreach ($_COOKIE as $cookie )
	//	echo "cookie: ".$cookie."<br>";
	if (isset($_COOKIE['login']))
	{
		//echo "login cookie exists";
		$loginArray = unserialize($_COOKIE['login']);
		$sql = "select password from users where username='".mysql_real_escape_string($loginArray[0])."'";
		//echo "<br>sql: ".$sql."<br>";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0)
		{
			//echo "<br>session found";
			$row = mysql_fetch_assoc($result);
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
?>

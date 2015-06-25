<?php
include $_SERVER["DOCUMENT_ROOT"]."/lib/globals.php";
include $_SERVER["DOCUMENT_ROOT"]."/lib/header.php";

if (array_key_exists('submit', $_POST))
{
	$con = mysql_connect($dbHost, $dbUser, $dbPass);
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	
	mysql_select_db($db, $con);

	$model_year = $make = $model = $trim = $color = $date_purchased = $date_sold = $mileage_current = $mileage_purchased = $mileage_sold = $price_purchased = $price_sold = $vehicle_index = $updateDS = $createDS = 0;
	
	$i = 0;
	$inputArray = array();
	$getArray = array_unique($_GET);
	foreach ($getArray as $value)
	{
		//echo "<br>value: ".$value;
		echo "<br>";
		echo "value: ".$value;
		$tempArray = explode(',', $value);
		echo "\tlength of exploded: ".sizeof($tempArray);
		echo "\ttempArray: ";
		for($i = 0; $i < sizeof($tempArray); $i++)
		{
			echo ', '.$tempArray[$i];
		}
		//array_push($inputArray, $tempArray);
		$i++;
	}

	$model_year = $_POST['model_year'];
	$make = $_POST['make'];
	$model = $_POST['model'];
	$trim = $_POST['trim'];
	$color= $_POST['color'];
	$month_purchased = $_POST['month_purchased'];
	$day_purchased = $_POST['day_purchased'];
	$year_purchased = $_POST['year_purchased'];
	//$date_purchased = $_POST['date_purchased'];
	$mileage_current = $_POST['mileage_current'];
	$mileage_purchased = $_POST['mileage_purchased'];
	$price_purchased = $_POST['price_purchased'];
	
	
	$insertQueryString = "INSERT INTO vehicles(model_year, make, model, trim, color, date_purchased, mileage_current, mileage_purchased, price_purchased, updateDS, createDS) VALUES ($model_year, '$make', '$model', '$trim', '$color', '$year_purchased-$month_purchased-$day_purchased', $mileage_current, $mileage_purchased, $price_purchased, NOW(), NOW())";
	echo "<br>" . $insertQueryString;
	mysql_query($insertQueryString);
	mysql_query("COMMIT");
	
	mysql_close($con);
}
	
function getDateFromInput($input)
{
	$explodedString = explode('/',$input);
	$dbDate = $explodedString[2] . "-" . $explodedString[0] . "-" . $explodedString[1];
	return $dbDate;
}

if (!array_key_exists('submit', $_POST))
{
?>
		<form name="submitVehicle" action="addVehicle.php" method="POST">
			<input type="hidden" name="submit" value="true">
			<table border='1'>
				<tr>
					<td>Model Year:</td><td><input type="text" name="model_year"></td>
				</tr>
				<tr>
					<td>Make:</td><td><input type="text" name="make"></td>
				</tr>
				<tr>
					<td>Model:</td><td><input type="text" name="model"></td>
				</tr>
				<tr>
					<td>Trim:</td><td><input type="text" name="trim"></td>
				</tr>
				<tr>
					<td>Color:</td><td><input type="text" name="color"></td>
				</tr>
				<tr>
					<td>Date Purchased:</td><td><input type="text" name="month_purchased" value="MM" maxlength="2"><input type="text" name="day_purchased" value="DD" maxlength="2"><input type="text" name="year_purchased" value="YYYY" maxlength="4"></td>
				</tr>
				<tr>
					<td>Current Mileage:</td><td><input type="text" name="mileage_current"></td>
				</tr>
				<tr>
					<td>Mileage when Purchased:</td><td><input type="text" name="mileage_purchased"></td>
				</tr>
				<tr>
					<td>Purchase Price:</td><td><input type="text" name="price_purchased"></td>
				</tr>
				<tr>
					<td><input type="submit" value="Submit"></td>
				</tr>
			</table>
		</form>
<?
}
include $_SERVER["DOCUMENT_ROOT"]."/lib/footer.php";
?>

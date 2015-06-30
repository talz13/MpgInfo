<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/MpgDb.php';
include $_SERVER['DOCUMENT_ROOT'] . '/lib/header.php';
				  
if (array_key_exists('submit', $_POST))
{
	$model_year = $make = $model = $trim = $color = $date_purchased = $date_sold = $mileage_current = $mileage_purchased = $mileage_sold = $price_purchased = $price_sold = $vehicle_index = $updateDS = $createDS = 0;
	
	$i = 0;
	$inputArray = array();
	$getArray = array_unique($_GET);
	foreach ($getArray as $value)
	{
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
    $db = new MpgDb();

	$model_year = $db->escapeString($_POST['model_year']);
	$make = $db->escapeString($_POST['make']);
	$model = $db->escapeString($_POST['model']);
	$trim = $db->escapeString($_POST['trim']);
	$color= $db->escapeString($_POST['color']);
	$month_purchased = $db->escapeString($_POST['month_purchased']);
	$day_purchased = $db->escapeString($_POST['day_purchased']);
	$year_purchased = $db->escapeString($_POST['year_purchased']);
	//$date_purchased = $db->escapeString($_POST['date_purchased']);
	$mileage_current = $db->escapeString($_POST['mileage_current']);
	$mileage_purchased = $db->escapeString($_POST['mileage_purchased']);
	$price_purchased = $db->escapeString($_POST['price_purchased']);
	
	
	$updateQueryString = "update vehicles set model_year=$model_year, make='$make', model='$model', trim='$trim', color='$color', date_purchased='$year_purchased-$month_purchased-$day_purchased', mileage_current=$mileage_current, mileage_purchased=$mileage_purchased, price_purchased=$price_purchased, updateDS=NOW() where vehicle_index=$vehicle_index and username='$username'";
	echo "<br>" . $updateQueryString;
	mysql_query($updateQueryString);
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
<?php
}
include $_SERVER['DOCUMENT_ROOT'] . '/lib/footer.php';
?>

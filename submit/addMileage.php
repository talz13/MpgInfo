<?php
include "../lib/globals.php";
include "../lib/header.php";
include "../lib/funcs.php";

if (checkLogin())
{
    $mysqli = getConn();
    if (array_key_exists('submit', $_POST))
    {
        $date = $miles = $gallons = $mpg = $priceGallon = $totalPrice = $vehicle = $comment = 0;

        $i = 0;
        $inputArray = array();

        $month = $mysqli->real_escape_string($_POST['month']);
        $day = $mysqli->real_escape_string($_POST['day']);
        $year = $mysqli->real_escape_string($_POST['year']);
        $miles = $mysqli->real_escape_string($_POST['miles']);
        $gallons = $mysqli->real_escape_string($_POST['gallons']);
        $mpg = $miles/$gallons;
        $priceGallon = $_POST['priceGallon'];
        $totalPrice = number_format($gallons * $priceGallon, 2, '.', '');
        $vehicle = $mysqli->real_escape_string($_POST['vehicle']);
        if (strlen($_POST['comment']) > 0) {
            $comment = $mysqli->real_escape_string($_POST['comment']);
        } else {
            $comment = '';
        }
        $insertQueryString = "INSERT INTO mileage(date, miles, gallons, mpg, priceGallon, totalPrice, vehicle, comment) VALUES ('$year-$month-$day', $miles, $gallons, $mpg, $priceGallon, $totalPrice, $vehicle, '$comment')";
        $mysqli->query($insertQueryString);
        $mysqli->commit();
    }
		
    function getDateFromInput($input)
    {
            $explodedString = explode('/',$input);
            $dbDate = $explodedString[2] . "-" . $explodedString[0] . "-" . $explodedString[1];
            return $dbDate;
    }

    //if (!array_key_exists('submit', $_POST))
    //{

    // Get vehicle list:
    $vehicles = array();
    $getVehiclesQueryString = 'SELECT vehicle_index, model_year, model, default_vehicle from vehicles where username="'.$mysqli->real_escape_string(getUsernameFromCookie()).'" order by model_year desc';
    $result = $mysqli->query($getVehiclesQueryString);
    while ($row = $result->fetch_assoc())
    {
            $tempArray = array($row['vehicle_index'], $row['model_year']." ".$row['model'], $row['default_vehicle']);
            array_push($vehicles, $tempArray);
    }
//	mysql_close($con);
    $mysqli->close();
    ?>
                    <form name="submitMileage" action="addMileage.php" method="POST">
                            <table border='1'>
                                    <tr>
                                            <td>Date:</td><td><input type="text" name="month" value="<?=date("m")?>" maxlength="2" size="2"><input type="text" name="day" value="<?=date("d")?>" maxlength="2" size="2"><input type="text" name="year" value="<?=date("Y")?>" maxlength="4" size=4><input type="hidden" name="submit" value="true"><input type="hidden" name="vehicle" value="2"></td>
                                    </tr>
                                    <tr>
                                            <td>Vehicle:</td><td><select name="vehicle"><?foreach ($vehicles as $vehicle){echo '<option value="'.$vehicle[0].'"'; if ($vehicle[2]){ echo 'SELECTED';} echo '>'.$vehicle[1].'</option>';}?></select></td>
                                    </tr>
                                    <tr>
                                            <td>Miles:</td><td><input type="number" step="any" name="miles"></td>
                                    </tr>
                                    <tr>
                                            <td>Price:</td><td><input type="number" step="any" name="priceGallon"></td>
                                    </tr>
                                    <tr>
                                            <td>Gallons:</td><td><input type="number" step="any" name="gallons"></td>
                                    </tr>
                                    <tr>
                                            <td>Comment:</td><td><input type="text" name="comment"></td>
                                    </tr>
                                    <tr>
                                            <td><input type="submit" value="Submit"></td>
                                    </tr>
                            </table>
                    </form>
	<?
}
else
{
?>			<table>
				<tr>
					<td><h2>Please <a href="/login.php?redirect=<?=urlencode($_SERVER["REQUEST_URI"])?>">log in</a></h2></td>
				</tr>
			</table><?
}
include "../lib/footer.php";
?>

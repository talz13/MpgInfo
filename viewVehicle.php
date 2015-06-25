<?php
// Special values for this page:
include $_SERVER["DOCUMENT_ROOT"] . "/lib/globals.php";
$pageName = "view results";
array_push($styles, "tablescroll.css");
include $_SERVER["DOCUMENT_ROOT"] . "/lib/header.php";

$con = mysql_connect($dbHost, $dbUser, $dbPass);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db($db, $con);

$vehicle_id = mysql_real_escape_string($_POST['vehicle_id']);
if ($vehicle_id == 0) {
    $vehicle_id = mysql_real_escape_string($_GET['vehicle_id']);
}
#$query = "SELECT m.date, concat(v.model_year,' ', v.make, ' ', v.model) as carName, m.miles, m.gallons, m.mpg, m.priceGallon, m.totalPrice, m.comment FROM `mileage` as m, `vehicles` as v where m.vehicle=v.vehicle_index order by m.date asc";
#$query_vehicle_name = "select concat(v.model_year,' ', v.make, ' ', v.model) as carName from vehicle as v where v.vehicle_index = $vehicle_id";
#$query_data = "select concat(v.model_year,' ', v.make, ' ', v.model) as carName, sum(m.miles) as miles, sum(m.gallons) as gallons, sum(m.totalPrice) as price, count(*) as count from mileage as m, vehicles as v where m.vehicle = v.vehicle_index and v.vehicle_index = $vehicle_id";
$query_data = "select concat(v.model_year,' ', v.make, ' ', v.model) as carName, sum(m.miles) as miles, sum(m.gallons) as gallons, sum(m.totalPrice) as price, min(m.date) as startDate, max(m.date) as endDate, count(*) as count from mileage as m, vehicles as v where m.vehicle = v.vehicle_index and v.vehicle_index = $vehicle_id";
#$query_

$result = mysql_query($query_data);
$numrows = mysql_num_rows($result);
?>
<table border="1" cellpadding="0" cellspacing="0"><?
    if ($numrows == 1)
    {
    $row = mysql_fetch_assoc($result);

    // Figure date range:
    $day = 86400;
    $week = 604800;
    $month = 2628000;
    $year = 31536000;

    $endDateArray = explode('-', $row['endDate']);
    $endDateTimestamp = mktime(0,0,0,$endDateArray[1], $endDateArray[2], $endDateArray[0]);
    $startDateArray = explode('-', $row['startDate']);
    $startDateTimestamp = mktime(0,0,0,$startDateArray[1], $startDateArray[2], $startDateArray[0]);
    $duration = $endDateTimestamp - $startDateTimestamp;

    $days = $duration / $day;
    $weeks = $duration / $week;
    $months = $duration / $month;
    $years = $duration / $year;

    $avgTimeBtwGasDays = $duration / $row['count'] / $day;
    #echo "<p>".$duration."</p>";
    #echo "<p>".$row['count']."</p>";
    #echo "<p>".$days."</p>";
    #echo "<p>".$avgTimeBtwGasDays."</p>";

    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td><b>".$row['carName']."</b></td><td></td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>Avg. MPG</td><td>".number_format($row['miles'] / $row['gallons'], 2)."</td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>Total $</td><td>\$".number_format($row['price'], 2)."</td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>Avg. $/mi</td><td>\$".number_format($row['price'] / $row['miles'], 2)."</td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>Total Miles</td><td>".number_format($row['miles'], 2)."</td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>Total Gallons</td><td>".number_format($row['gallons'], 2)."</td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>Fillup Count</td><td>".$row['count']."</td>";
    echo "\n\t\t\t\t\t\t</tr>";

    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>&nbsp;</td><td></td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>Avg. mi/day</td><td>".number_format($row['miles'] / $days, 2)."</td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>Avg. mi/wk</td><td>".number_format($row['miles'] / $weeks, 2)."</td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>Avg. mi/mon</td><td>".number_format($row['miles'] / $months, 2)."</td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>Avg. mi/year</td><td>".number_format($row['miles'] / $years, 2)."</td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>&nbsp;</td><td></td>";
    echo "\n\t\t\t\t\t\t</tr>";
    echo "\n\t\t\t\t\t\t<tr>";
    echo "\n\t\t\t\t\t\t\t<td>Avg. days per tank</td><td>".number_format($avgTimeBtwGasDays, 2)."</td>";
    echo "\n\t\t\t\t\t\t</tr>";
    }?>
</table>
<br>
<a href="graphs/mpg.php?vehicle_id=<?= $vehicle_id; ?>"><img src="graphs/mpg.php?sizex=320&amp;sizey=240&amp;vehicle_id=<?= $vehicle_id; ?>" width="320" height="240" alt="MPG"></a>
<?include $_SERVER["DOCUMENT_ROOT"]."/lib/footer.php";

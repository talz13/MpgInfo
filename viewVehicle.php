<?php
// Special values for this page:
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/MpgDb.php';
$pageName = "view results";
//array_push($styles, "tablescroll.css");
include $_SERVER['DOCUMENT_ROOT'] . '/lib/header.php';

$db = new MpgDb();

if (array_key_exists('vehicle_id', $_POST) and isset($_POST['vehicle_id'])) {
    $vehicle_id = $db->escapeString($_POST['vehicle_id']);
} else if (array_key_exists('vehicle_id', $_GET) and isset($_GET['vehicle_id'])) {
    $vehicle_id = $db->escapeString($_GET['vehicle_id']);
} else {
    $query = sprintf("select vehicle_index from `vehicles` where username = '%s' and default_vehicle = 1", $db->escapeString(getUsernameFromCookie()));
    $db->runQuery($query);
    if ($db->getRowCount() == 1) {
        $vehicle_id = $db->getRow()['vehicle_index'];
    }
}

$query_data = "select concat(v.model_year,' ', v.make, ' ', v.model) as carName,
                      sum(m.miles) as miles,
                      sum(m.gallons) as gallons,
                      sum(m.totalPrice) as price,
                      min(m.date) as startDate,
                      max(m.date) as endDate,
                      count(*) as count
                from mileage as m
                inner join vehicles as v on m.vehicle = v.vehicle_index
                where v.vehicle_index = $vehicle_id";
#$query_
//echo $query_data;

$db->runQuery($query_data);
?>
<table border="1" cellpadding="0" cellspacing="0"><?php
    if ($db->getRowCount() == 1) {
        $row = $db->getRow();

        // Figure date range:
        $day = 60*60*24;
        $week = $day * 7;
        $month = $week * 52 / 12;
        $year = $day * 365;

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
    } ?>
</table>
<br>
<?php
sprintf('<a href="graphs/graphQuery.php?graph=mpg&vehicle_id=%1$d"><img src="graphs/graphQuery.php?graph=mpg&amp;sizex=%2$d&amp;sizey=%3$d&amp;vehicle_id=%1$d" width="%2$d" height="%3$d" alt="MPG"></a>', $vehicle_id, Config::getXThumb(), Config::getYThumb());

include $_SERVER['DOCUMENT_ROOT'] . '/lib/footer.php';

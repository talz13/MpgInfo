<?php
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/avg.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/funcs.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/MpgDb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/graphs/mpgInfoGraph.php';


$xData = array();
$yData = array();
$average = array();
$bAccumulate = false;
$cumulative;

if (isset($_GET['graph'])) {
    $bVehicleId = isset($_GET['vehicle_id']) and !is_null($_GET['vehicle_id']) and is_numeric($_GET['vehicle_id']);
    switch ($_GET['graph']) {
        case 'mpg':
            $query = "select m.date as date, m.miles / m.gallons as mpg from mileage as m ";
            if ($bVehicleId) {
                $query .= sprintf(", vehicles as v where m.vehicle = v.vehicle_index and v.vehicle_index = %d ", $db->escapeString($_GET['vehicle_id']));
            }
            $query .= "order by m.date asc";
            $title = "MPG each fillup from\n%s to %s";
            break;
        case 'price':
            $query = "select date, priceGallon from mileage order by date asc";
            $title = "$/gal each fillup from\n%s to %s";
            break;
        case 'miles':
            $query = "select m.date as date, m.miles as miles from mileage as m ";
            if ($bVehicleId) {
                $query .= sprintf(", vehicles as v where m.vehicle = v.vehicle_index and v.vehicle_index = %d ", $db->escapeString($_GET['vehicle_id']));
            }
            $query .= "order by m.date asc";
            $title = "Cumulative miles over time\n%s to %s";
            $bAccumulate = true;
            break;
    }
}

$db = new MpgDb();
$db->runQuery($query);

if ($db->getRowCount() > 0) {
    while ($row = $db->getRow()) {
        array_push($xData, $row[0]);
        if ($bAccumulate && !isset($cumulative)) {
            $cumulative = 0;
        } else if ($bAccumulate) {
            $cumulative += $row[1];
            array_push($yData, $cumulative);
        } else {
            array_push($yData, $row[1]);
        }
    }
    if (!$bAccumulate) {
        $average = overallAverage($yData);
    } else {
        $average = 0;
    }
} else {
    array_push($xData, "no dates");
    array_push($yData, 0);
    array_push($average, 0);
}

$interval = count($xData) / 25;
$title = sprintf($title, $xData[0], $xData[count($xData) - 1]);

drawGraph($xData, $yData, $average, $interval, $title);
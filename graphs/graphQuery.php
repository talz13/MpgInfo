<?php
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/avg.php';
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/globals.php';
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/graphs/mpgInfoGraph.php';

startMpgSession();

Config::initDb();

$xData = array();
$yData = array();
$average = array();
$bAccumulate = false;
$yAxisType = 'number';
$cumulative;

if (checkLogin()) {
    $expr = null;
    $graph = filter_input(INPUT_GET, 'graph', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    if (!is_null($graph)) {
        $vehicle_id = filter_input(INPUT_GET, 'vehicle_id', FILTER_VALIDATE_INT);
        switch ($graph) {
            case 'mpg':
                $expr = Vehicle::table_alias('v')->select('date')
                    ->select_expr('r.miles / r.gallons', 'mpg')
                    ->join('refueling', 'v.id = r.vehicle_id', 'r')
                    ->where('v.user_id', getUserId())
                    ->order_by_asc('r.date');
                if (!is_null($vehicle_id)) {
                    $expr = $expr->where('v.id', $vehicle_id);
                }
                $title = "MPG each fillup from\n%s to %s";
                break;
            case 'price':
                $expr = Vehicle::table_alias('v')
                    ->select_many('date', 'price_gallon')
                    ->join('refueling', 'v.id = r.vehicle_id', 'r')
                    ->where('v.user_id', getUserId())
                    ->order_by_asc('r.date');
                $title = "$/gal each fillup from\n%s to %s";
                $yAxisType = 'currency';
                break;
            case 'miles':
                $expr = Vehicle::table_alias('v')
                    ->select_many('r.date', 'r.miles')
                    ->join('refueling', 'v.id = r.vehicle_id', 'r')
                    ->where('v.user_id', getUserId())
                    ->order_by_asc('r.date');
                if (!is_null($vehicle_id)) {
                    $expr = $expr->where('v.id', $vehicle_id);
                }
                $title = "Cumulative miles over time\n%s to %s";
                $bAccumulate = true;
                break;
            default:
                $expr = null;
        }
    }

    if (!is_null($expr)) {
        $records = $expr->find_many();
        $keys = array_keys($records[0]->as_array());
        foreach ($records as $record) {
            array_push($xData, $record->$keys[0]);
            if ($bAccumulate && !isset($cumulative)) {
                $cumulative = 0;
            } else if ($bAccumulate) {
                $cumulative += $record->$keys[1];
                array_push($yData, $cumulative);
            } else {
                array_push($yData, $record->$keys[1]);
            }
        }
        if (!$bAccumulate) {
            $average = overallAverage($yData);
        } else {
            $average = 0;
        }

        $interval = count($xData) / 25;
        $title = sprintf($title, $xData[0], $xData[count($xData) - 1]);

        drawGraph($xData, $yData, $average, $interval, $title, $yAxisType);
    }
}

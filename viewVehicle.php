<?php
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/globals.php';

startMpgSession();

Config::initDb();

$pageName = "view results";
//array_push($styles, "tablescroll.css");
include filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/header.php';

if (checkLogin()) {
    if (array_key_exists('vehicle_id', $_POST)) {
        $vehicle_id = filter_input(INPUT_POST, 'vehicle_id', FILTER_VALIDATE_INT);
    } else if (array_key_exists('vehicle_id', $_GET)) {
        $vehicle_id = filter_input(INPUT_GET, 'vehicle_id', FILTER_VALIDATE_INT);
    } else {
        $vehicle_id = getDefaultVehicle()->id;
    }
    if (checkVehicleId($vehicle_id)) {
        $vehicle = Vehicle::where_id_is($vehicle_id)->find_one();
        displayVehicleDropdown('viewVehicle.php', $vehicle_id);
        $table = '';
        $table .= '<table border="1" cellpadding="0" cellspacing="0">';
        $refuelingRecords = RefuelingSum::where('vehicle_id', $vehicle->id);
            if ($refuelingRecords->count('*') > 0) {
                // Figure date range:
                $day = 60 * 60 * 24;
                $week = $day * 7;
                $month = $week * 52 / 12;
                $year = $day * 365;

                $duration = strtotime($refuelingRecords->max('date')) - strtotime($refuelingRecords->min('date'));

                $days = $duration / $day;
                $weeks = $duration / $week;
                $months = $duration / $month;
                $years = $duration / $year;

                $avgTimeBtwGasDays = $duration / $refuelingRecords->count('*') / $day;

                $table .= sprintf('<tr><td><b>%s %s %s</b></td><td></td></tr>', $vehicle->model_year, $vehicle->make, $vehicle->model);
                $table .= sprintf("<tr><td>Avg. MPG</td><td>%.2f</td></tr>", $refuelingRecords->sum('miles') / $refuelingRecords->sum('gallons'));
//                $table .= sprintf("<tr><td>Total $</td><td>\$%.2f</td></tr>", $refuelingRecords->('price_gallon * gallons'));
                $table .= sprintf("<tr><td>Total $</td><td>\$%.2f</td></tr>", $refuelingRecords->sum('price'));
//                $table .= sprintf("<tr><td>Avg. $/mi</td><td>\$%.2f</td></tr>", $refuelingRecords->sum('price_gallon * gallons') / $refuelingRecords->sum('miles'));
                $table .= sprintf("<tr><td>Avg. $/mi</td><td>\$%.2f</td></tr>", $refuelingRecords->sum('price') / $refuelingRecords->sum('miles'));
                $table .= sprintf("<tr><td>Total Miles</td><td>%.1f</td></tr>", $refuelingRecords->sum('miles'));
                $table .= sprintf("<tr><td>Total Gallons</td><td>%.2f</td></tr>", $refuelingRecords->sum('gallons'));
                $table .= sprintf("<tr><td>Fillup Count</td><td>%d</td></tr>", $refuelingRecords->count('*'));
                $table .= sprintf("<tr><td>&nbsp;</td><td></td></tr>");
                $table .= sprintf("<tr><td>Avg. mi/day</td><td>%.2f</td></tr>", $refuelingRecords->sum('miles') / $days);
                $table .= sprintf("<tr><td>Avg. mi/wk</td><td>%.2f</td></tr>", $refuelingRecords->sum('miles') / $weeks);

                $table .= sprintf("<tr><td>Avg. mi/mon</td><td>%.2f</td></tr>", $refuelingRecords->sum('miles') / $months);
                $table .= sprintf("<tr><td>Avg. mi/year</td><td>%.2f</td></tr>", $refuelingRecords->sum('miles') / $years);
                $table .= sprintf("<tr><td>&nbsp;</td><td></td>");
                $table .= sprintf("<tr><td>Avg. days per tank</td><td>%.2f</td></tr>", $avgTimeBtwGasDays);
            }
        $table .= "</table><br>";
        echo $table;
        printf('<a href="graphs/graphQuery.php?graph=mpg&vehicle_id=%1$d"><img src="graphs/graphQuery.php?graph=mpg&amp;sizex=%2$d&amp;sizey=%3$d&amp;vehicle_id=%1$d" width="%2$d" height="%3$d" alt="MPG"></a>',
            $vehicle->id,
            Config::getXThumb(),
            Config::getYThumb());
    } else {
        echo "<h3>Vehicle does not belong to you!</h3>";
    }
} else {
    displayLoginLink();
}

include filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/footer.php';

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';

startMpgSession();

Config::initDb();

include $_SERVER['DOCUMENT_ROOT'] . '/lib/header.php';
if (checkLogin()) {
    if (array_key_exists('submit', $_POST)) {
        $stringSanitizeFilters = FILTER_FLAG_ENCODE_LOW | FILTER_FLAG_ENCODE_HIGH;

        $month_purchased = filter_input(INPUT_POST, 'month_purchased', FILTER_VALIDATE_INT);
        $day_purchased = filter_input(INPUT_POST, 'day_purchased', FILTER_VALIDATE_INT);
        $year_purchased = filter_input(INPUT_POST, 'year_purchased', FILTER_VALIDATE_INT);
        if ($month_purchased && $day_purchased && $year_purchased && checkdate($month_purchased, $day_purchased, $year_purchased)) {
            $date_purchased = date(sprintf("%d/%d/%d", $month_purchased, $day_purchased, $year_purchased));
        }
        $month_sold = filter_input(INPUT_POST, 'month_sold', FILTER_VALIDATE_INT);
        $day_sold = filter_input(INPUT_POST, 'day_sold', FILTER_VALIDATE_INT);
        $year_sold = filter_input(INPUT_POST, 'year_sold', FILTER_VALIDATE_INT);
        if ($month_sold && $day_sold && $year_sold && checkdate($month_sold, $day_sold, $year_sold)) {
            $date_sold = date(sprintf("%d/%d/%d", $month_sold, $day_sold, $year_sold));
        }

        $vehicle = Vehicle::create();
        $vehicle->user_id = getUserId();
        $vehicle->model_year = filter_input(INPUT_POST, 'model_year', FILTER_VALIDATE_INT);
        $vehicle->make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_STRING, $stringSanitizeFilters);
        $vehicle->model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_STRING, $stringSanitizeFilters);
        $vehicle->trim = filter_input(INPUT_POST, 'trim', FILTER_SANITIZE_STRING, $stringSanitizeFilters);
        $vehicle->color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING, $stringSanitizeFilters);
        if (isset($date_purchased)) {
            $vehicle->purchase_date = $date_purchased;
        }
        if (isset($date_sold)) {
            $vehicle->sold_date = $date_sold;
        }
        if ($current_miles = filter_input(INPUT_POST, 'miles_current', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) {
            $vehicle->current_miles = $current_miles;
        }
        if ($miles_original = filter_input(INPUT_POST, 'miles_original', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) {
            $vehicle->original_miles = round($miles_original, 1);
        }
        if ($miles_final = filter_input(INPUT_POST, 'mileage_sold', FILTER_VALIDATE_INT)) {
            $vehicle->final_miles = $miles_final;
        }
        if ($vin = filter_input(INPUT_POST, 'vin', FILTER_SANITIZE_STRING, $stringSanitizeFilters)) {
            $vehicle->vin = $vin;
        }
        if ($price_purchased = filter_input(INPUT_POST, 'price_purchased', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) {
            $vehicle->purchase_price = round($price_purchased, 2);
        }
        if ($price_sold = filter_input(INPUT_POST, 'price_sold', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) {
            $vehicle->sell_price = round($price_sold, 2);
        }

        $vehicle->save();
    } else {
        ?>
        <form name="submitVehicle" action="addVehicle.php" method="POST">
            <input type="hidden" name="submit" value="true">
            <table border='1'>
                <tr>
                    <td>Model Year *</td>
                    <td><input type="text" name="model_year"></td>
                </tr>
                <tr>
                    <td>Make *</td>
                    <td><input type="text" name="make"></td>
                </tr>
                <tr>
                    <td>Model *</td>
                    <td><input type="text" name="model"></td>
                </tr>
                <tr>
                    <td>Trim *</td>
                    <td><input type="text" name="trim"></td>
                </tr>
                <tr>
                    <td>Color *</td>
                    <td><input type="text" name="color"></td>
                </tr>
                <tr>
                    <td>VIN</td>
                    <td><input type="text" name="vin"></td>
                </tr>
                <tr>
                    <td>Date Purchased</td>
                    <td><input type="text" name="month_purchased" value="MM" maxlength="2">
                        <input type="text" name="day_purchased" value="DD" maxlength="2">
                        <input type="text" name="year_purchased" value="YYYY" maxlength="4"></td>
                </tr>
                <tr>
                    <td>Current Mileage</td>
                    <td><input type="text" name="miles_current"></td>
                </tr>
                <tr>
                    <td>Miles at Purchase</td>
                    <td><input type="text" name="miles_original"></td>
                </tr>
                <tr>
                    <td>Purchase Price</td>
                    <td><input type="text" name="price_purchased"></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Submit"></td>
                </tr>
            </table>
        </form>
    <?php
    }
}
include $_SERVER['DOCUMENT_ROOT'] . '/lib/footer.php';
?>

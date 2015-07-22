<?php
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/globals.php';
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationExceptionInterface;

startMpgSession();

Config::initDb();

include filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/header.php';

if (checkLogin()) {
    if (array_key_exists('vehicle_id', $_POST)) {
        $vehicle_id = filter_input(INPUT_POST, 'vehicle_id', FILTER_VALIDATE_INT);
    } else if (array_key_exists('vehicle_id', $_GET)) {
        $vehicle_id = filter_input(INPUT_GET, 'vehicle_id', FILTER_VALIDATE_INT);
    } else {
        $vehicle_id = Vehicle::where('user_id', getUserId())->where('b_default', 1)->find_one()->id;
    }
    if (checkVehicleId($vehicle_id)) {
        $vehicle = Vehicle::where('id', $vehicle_id)->find_one();
        if (array_key_exists('submit', $_POST)) {
            $stringSanitizeFilters = FILTER_FLAG_ENCODE_LOW | FILTER_FLAG_ENCODE_HIGH;

            try {
                $dateValidator = v::notEmpty()->date();
                $milesValidator = v::notEmpty()->positive()->float()->between(0, 99999999);
                $yearValidator = v::notEmpty()->positive()->int()->between(1900, 3000);
                $amountValidator = v::notEmpty()->positive()->float();
                $stringValidator = v::notEmpty()->string();
                $boolValidator = v::notEmpty()->bool();

                // Required fields:
                if ($yearValidator->assert(filter_input(INPUT_POST, 'model_year'))) {
                    $model_year = (int) filter_input(INPUT_POST, 'model_year');
                }
                if ($stringValidator->length(1, 50)->assert(filter_input(INPUT_POST, 'make'))) {
                    $make = trim(filter_input(INPUT_POST, 'make'));
                }
                if ($stringValidator->length(1, 100)->assert(filter_input(INPUT_POST, 'model'))) {
                    $model = trim(filter_input(INPUT_POST, 'model'));
                }
                if ($stringValidator->length(1, 50)->assert(filter_input(INPUT_POST, 'trim'))) {
                    $trim = trim(filter_input(INPUT_POST, 'trim'));
                }
                if ($stringValidator->length(1, 50)->assert(filter_input(INPUT_POST, 'color'))) {
                    $color = trim(filter_input(INPUT_POST, 'color'));
                }
                if (v::true()->validate(filter_input(INPUT_POST, 'b_default'))) {
                    $b_default = true;
                } else {
                    $b_default = false;
                }

                // Optional fields:
                if ($stringValidator->length(0, 50)->validate(filter_input(INPUT_POST, 'vin'))) {
                    $vin = trim(filter_input(INPUT_POST, 'vin'));
                }
                if ($yearValidator->validate(filter_input(INPUT_POST, 'buy_year')) && $dateValidator->validate(sprintf("%s-%s-%s", filter_input(INPUT_POST, 'buy_year'), filter_input(INPUT_POST, 'buy_month'), filter_input(INPUT_POST, 'buy_day')))) {
                    $purchase_date = date(sprintf("%s-%s-%s", filter_input(INPUT_POST, 'buy_year'), filter_input(INPUT_POST, 'buy_month'), filter_input(INPUT_POST, 'buy_day')));
                }
                if ($yearValidator->validate(filter_input(INPUT_POST, 'sell_year')) && $dateValidator->validate(sprintf("%s-%s-%s", filter_input(INPUT_POST, 'sell_year'), filter_input(INPUT_POST, 'sell_month'), filter_input(INPUT_POST, 'sell_day')))) {
                    $sold_date = date(sprintf("%s-%s-%s", filter_input(INPUT_POST, 'sell_year'), filter_input(INPUT_POST, 'sell_month'), filter_input(INPUT_POST, 'sell_day')));
                }
                if ($milesValidator->validate(filter_input(INPUT_POST, 'miles_current'))) {
                    $current_miles = round(filter_input(INPUT_POST, 'miles_current', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 1);
                }
                if ($milesValidator->validate(filter_input(INPUT_POST, 'miles_original'))) {
                    $original_miles = round(filter_input(INPUT_POST, 'miles_original', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 1);
                }
                if ($milesValidator->validate(filter_input(INPUT_POST, 'sold_miles'))) {
                    $sold_miles = round(filter_input(INPUT_POST, 'sold_miles', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 1);
                }
                if ($amountValidator->validate(filter_input(INPUT_POST, 'price_purchased'))) {
                    $purchased_price = round(filter_input(INPUT_POST, 'price_purchased', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 2);
                }
                if ($amountValidator->validate(filter_input(INPUT_POST, 'sell_price'))) {
                    $sold_price = round(filter_input(INPUT_POST, 'sell_price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 2);
                }

//                $vehicle = Vehicle::where('id', $vehicle_id)->find_one();
                if (isset($model_year) && $vehicle->model_year != $model_year) {
                    $vehicle->model_year = $model_year;
                }
                if (isset($make) && $vehicle->make != $make) {
                    $vehicle->make = $make;
                }
                if (isset($model) && $vehicle->model != $model) {
                    $vehicle->model = $model;
                }
                if (isset($trim) && $vehicle->trim != $trim) {
                    $vehicle->trim = $trim;
                }
                if (isset($color) && $vehicle->color != $color) {
                    $vehicle->color = $color;
                }
                if (isset($purchase_date) && strtotime($vehicle->purchase_date) != strtotime($purchase_date)) {
                    $vehicle->purchase_date = $purchase_date;
                }
                if (isset($sold_date) && strtotime($vehicle->sold_date) != strtotime($sold_date)) {
                    $vehicle->sold_date = $sold_date;
                }
                if (isset($current_miles) && $vehicle->current_miles != $current_miles) {
                    $vehicle->current_miles = $current_miles;
                }
                if (isset($original_miles) && $vehicle->original_miles != $original_miles) {
                    $vehicle->original_miles = $original_miles;
                }
                if (isset($sold_miles) && $vehicle->sold_miles != $sold_miles) {
                    $vehicle->sold_miles = $sold_miles;
                }
                if (isset($vin) && $vehicle->vin != $vin) {
                    $vehicle->vin = $vin;
                }
                if (isset($purchased_price) && $vehicle->purchase_price != $purchased_price) {
                    $vehicle->purchase_price = $purchase_price;
                }
                if (isset($sold_price) && $vehicle->sell_price != $sold_price) {
                    $vehicle->sell_price = $sold_price;
                }
                if (isset($b_default) && $vehicle->b_default != $b_default && $b_default) {
                    $vehicle->b_default = 1;
                    clearDefaultVehicles($vehicle_id);
                }
                $vehicle->save();
            } catch (NestedValidationExceptionInterface $exception) {

            }
        }
        displayVehicleDropdown('editVehicle.php', $vehicle_id);
        ?>
        <form name="submitVehicle" action="editVehicle.php" method="POST">
            <input type="hidden" name="submit" value="true">
            <?php
            if (isset($vehicle_id)) {
                printf('<input type="hidden" name="vehicle_id" value="%d">', $vehicle_id);
            }
            ?>
            <table border='1'>
                <tr>
                    <td>Model Year</td>
                    <td><input type="text" name="model_year" value="<?= $vehicle->model_year ?>"></td>
                </tr>
                <tr>
                    <td>Make</td>
                    <td><input type="text" name="make" value="<?= $vehicle->make ?>"></td>
                </tr>
                <tr>
                    <td>Model</td>
                    <td><input type="text" name="model" value="<?= $vehicle->model ?>"></td>
                </tr>
                <tr>
                    <td>Trim</td>
                    <td><input type="text" name="trim" value="<?= $vehicle->trim ?>"></td>
                </tr>
                <tr>
                    <td>Color</td>
                    <td><input type="text" name="color" value="<?= $vehicle->color ?>"></td>
                </tr>
                <tr>
                    <td>VIN</td>
                    <td><input type="text" name="vin" value="<?= $vehicle->vin ?>"></td>
                </tr>
                <tr>
                    <td>Date Purchased</td>
                    <td><input type="text" name="buy_month" value="<?= explode('-', $vehicle->purchase_date)[1] ?>" maxlength="2" size="2">
                        <input type="text" name="buy_day" value="<?= explode('-', $vehicle->purchase_date)[2] ?>" maxlength="2" size="2">
                        <input type="text" name="buy_year" value="<?= explode('-', $vehicle->purchase_date)[0] ?>" maxlength="4" size="4"></td>
                </tr>
                <tr>
                    <td>Purchase Price</td>
                    <td><input type="text" name="price_purchased" value="<?= $vehicle->purchase_price ?>"></td>
                </tr>
                <tr>
                    <td>Original Miles</td>
                    <td><input type="text" name="miles_original" value="<?= $vehicle->original_miles ?>"></td>
                </tr>
                <tr>
                    <td>Date Sold</td>
                    <td><input type="text" name="sell_month" value="<?= is_null($vehicle->sold_date) ? 'MM' : explode('-', $vehicle->sold_date)[1] ?>" maxlength="2" size="2">
                        <input type="text" name="sell_day" value="<?= is_null($vehicle->sold_date) ? 'DD' : explode('-', $vehicle->sold_date)[2] ?>" maxlength="2" size="2">
                        <input type="text" name="sell_year" value="<?= is_null($vehicle->sold_date) ? 'YYYY' : explode('-', $vehicle->sold_date)[0] ?>" maxlength="4" size="4"></td>
                </tr>
                <tr>
                    <td>Sell Price</td>
                    <td><input type="text" name="sell_price" value="<?= $vehicle->sell_price ?>"></td>
                </tr>
                <tr>
                    <td>Final Miles</td>
                    <td><input type="text" name="sold_miles" value="<?= $vehicle->sold_miles ?>"></td>
                </tr>
                <tr>
                    <td>Set as Default</td>
                    <td><input type="checkbox" name="b_default"<?= $vehicle->b_default ? ' CHECKED DISABLED' : '' ?>></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Submit"></td>
                </tr>
            </table>
        </form>
    <?php
    } else {

    }
} else {
    displayLoginLink();
}
include filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/footer.php';

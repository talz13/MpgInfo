<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';

startMpgSession();

Config::initDb();

include $_SERVER['DOCUMENT_ROOT'] . '/lib/header.php';

if (checkLogin()) {
    if (array_key_exists('submit', $_POST)) {
        $vehicle_id = filter_input(INPUT_POST, 'vehicle', FILTER_VALIDATE_INT);
        if (checkVehicleId($vehicle_id)) {
            $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
            $month = filter_input(INPUT_POST, 'month', FILTER_VALIDATE_INT);
            $day = filter_input(INPUT_POST, 'day', FILTER_VALIDATE_INT);
            if ($year && $month && $day && checkdate($month, $day, $year)) {
                $date = date(sprintf("%d/%d/%d", $month, $day, $year));
            } else {
                $date = date('n/j/Y');
            }
            $refueling = Refueling::create();
            $refueling->date = $date;
            $refueling->miles = filter_input(INPUT_POST, 'miles', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $refueling->gallons = filter_input(INPUT_POST, 'gallons', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $refueling->price_gallon = filter_input(INPUT_POST, 'priceGallon', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $refueling->vehicle_id = $vehicle_id;
            $refueling->comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
            $refueling->save();
        }
        // TODO else display error, vehicle doesn't belong to user!
    }
    $vehicles = Vehicle::where('user_id', getUserId())->find_many();
    ?>
    <form name="submitMileage" action="addRefueling.php" method="POST">
        <table border="1">
            <tr>
                <td>Date:</td>
                <td>
                    <input type="text" name="month" value="<?= date("m"); ?>" maxlength="2" size="2">
                    <input type="text" name="day" value="<?= date("d"); ?>" maxlength="2" size="2">
                    <input type="text" name="year" value="<?= date("Y"); ?>" maxlength="4" size=4><input type="hidden" name="submit" value="true">
                    <input type="hidden" name="vehicle" value="2">
                </td>
            </tr>
            <tr>
                <td>Vehicle:</td>
                <td><select name="vehicle"><?php
                        foreach ($vehicles as $vehicle) {
                            printf('<option value="%s"%s>%s %s</option>', $vehicle->id, ($vehicle->b_default == 1) ? ' SELECTED' : '', $vehicle->model_year, $vehicle->model);
                        }
                        ?></select></td>
            </tr>
            <tr>
                <td>Miles:</td>
                <td><input type="number" step="any" name="miles"></td>
            </tr>
            <tr>
                <td>Price:</td>
                <td><input type="number" step="any" name="priceGallon"></td>
            </tr>
            <tr>
                <td>Gallons:</td>
                <td><input type="number" step="any" name="gallons"></td>
            </tr>
            <tr>
                <td>Comment:</td>
                <td><input type="text" name="comment"></td>
            </tr>
            <tr>
                <td><input type="submit" value="Submit"></td>
            </tr>
        </table>
    </form>
<?php
} else {
    displayLoginLink();
}
include $_SERVER['DOCUMENT_ROOT'] . '/lib/footer.php';
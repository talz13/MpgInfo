<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/MpgDb.php';

startMpgSession();

Config::initDb();

include $_SERVER['DOCUMENT_ROOT'] . '/lib/header.php';

if (checkLogin()) {
    //    $mysqli = getConn();
    if (array_key_exists('submit', $_POST)) {
        $refueling = Refueling::create();
        $refueling->date = sprintf("%d-%d-%d", $_POST['year'], $_POST['month'], $_POST['day']);
        $refueling->miles = $_POST['miles'];
        $refueling->gallons = $_POST['gallons'];
        $refueling->price_gallon = $_POST['priceGallon'];
        $refueling->vehicle_id = $_POST['vehicle'];
        $refueling->comment = $_POST['comment'];
        $refueling->save();
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
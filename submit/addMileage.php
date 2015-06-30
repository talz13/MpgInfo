<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/MpgDb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/funcs.php';

include $_SERVER['DOCUMENT_ROOT'] . '/lib/header.php';

if (checkLogin()) {
    //    $mysqli = getConn();
    if (array_key_exists('submit', $_POST)) {
        $date = $miles = $gallons = $mpg = $priceGallon = $totalPrice = $vehicle = $comment = 0;

        $i = 0;
        $inputArray = array();

        $month = $db->escapeString($_POST['month']);
        $day = $db->escapeString($_POST['day']);
        $year = $db->escapeString($_POST['year']);
        $miles = $db->escapeString($_POST['miles']);
        $gallons = $db->escapeString($_POST['gallons']);
        $priceGallon = $db->escapeString($_POST['priceGallon']);
        $vehicle = $db->escapeString($_POST['vehicle']);
        $comment = strlen($_POST['comment'] > 0) ? $db->escapeString($_POST['comment']) : '';
        $mpg = $miles / $gallons;
        $totalPrice = number_format($gallons * $priceGallon, 2, '.', '');

        $insertQueryString =
            sprintf("INSERT INTO mileage(date, miles, gallons, mpg, priceGallon, totalPrice, vehicle, comment)
                      VALUES (\'%s\', %d, %d, %d, %d, %d, %i, \'%s\')",
            '$year-$month-$day', $miles, $gallons, $mpg, $priceGallon, $totalPrice, $vehicle, $comment);
        $db = new MpgDb();
        $db->runInsertQuery($insertQueryString);
    }

    // Get vehicle list:
    $vehicles = array();
    $getVehiclesQueryString = "SELECT vehicle_index, model_year, model, default_vehicle FROM vehicles WHERE username='%s' ORDER BY model_year DESC";
    $db = new MpgDb();
    $db->runQuery(sprintf($getVehiclesQueryString, $db->escapeString(getUsernameFromCookie())));
    while ($row = $db->getRow()) {
        $tempArray = array($row['vehicle_index'], sprintf('%s %s', $row['model_year'], $row['model']), $row['default_vehicle']);
        array_push($vehicles, $tempArray);
    }
    ?>
    <form name="submitMileage" action="addMileage.php" method="POST">
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
                            printf('<option value="%s"%s>%s</option>', $vehicle[0], ($vehicle[2]) ? ' SELECTED' : '', $vehicle[1]);
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
    ?>
    <table>
    <tr>
        <?= sprintf('<td><h2>Please <a href="%s?redirect=%s">log in</a></h2></td>', buildLocalPath('/login.php'), urlencode($_SERVER['REQUEST_URI'])) ?>
    </tr>
    </table><?php
}
include $_SERVER['DOCUMENT_ROOT'] . '/lib/footer.php';
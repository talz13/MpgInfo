<?php
// Special values for this page:
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/MpgDb.php';
$pageName = "view results";
//array_push($styles, "tablescroll.css");
include $_SERVER['DOCUMENT_ROOT'] . '/lib/header.php';

// Columns array:
$tableColumns = array();
$tableColumns['date'] = 'Date';
$tableColumns['carName'] = 'Car Name';
$tableColumns['miles'] = 'Miles';
$tableColumns['gallons'] = 'Gallons';
$tableColumns['mpg'] = 'MPG';
$tableColumns['priceGallon'] = '$/Gal';
$tableColumns['totalPrice'] = '$';
$tableColumns['comment'] = 'Comment';

$sortBy = '';
//$prevSort = '';
$sortDir = 'asc';
if (array_key_exists('sort', $_GET)) {
    $sortBy = $_GET['sort'];
}
if (array_key_exists('dir', $_GET)) {
    $sortDir = $_GET['dir'];
}
// Default sort:
if (!isset($sortBy) or empty($sortBy)) {
    $sortBy = 'date';
    $sortDir = 'desc';
}

// Sort Links:
$sortLinks = array();
foreach ($tableColumns as $key => $value) {
    array_push($sortLinks, sprintf("<th><a href=\"viewResults.php?sort=%s&dir=%s\">%s</a></th>", $key, ($sortBy === $key and $sortDir === 'asc') ? 'desc' : 'asc', $value));
}
$query = sprintf("SELECT m.date, concat(v.model_year, ' ', v.make, ' ', v.model) as carName, m.miles, m.gallons, m.miles / m.gallons as mpg, m.priceGallon, m.priceGallon * m.gallons as totalPrice, m.comment FROM `mileage` as m, `vehicles` as v where m.vehicle = v.vehicle_index order by %s %s", $sortBy, $sortDir);

$displayValFormat = "<tr><td>%s</td><td>%s</td><td>%01.1f</td><td align=\"right\">%01.3f</td><td>%01.2f</td><td>\$%01.2f</td><td align=\"right\">\$%01.2f</td><td>%s</td></tr>";

$db = new MpgDb();
$db->runQuery($query);
if ($db->getRowCount() > 0) {
    echo '<div id="tableContainer"><table border="1" cellpadding="1" cellspacing="1"><thead class="fixedHeader"><tr>';
    foreach ($sortLinks as $value) {
        echo $value;
    }
    echo '</tr></thead><tbody>';
    while ($row = $db->getRow()) {
        printf($displayValFormat, $row['date'], $row['carName'], $row['miles'], $row['gallons'], $row['mpg'], $row['priceGallon'], $row['totalPrice'], $row['comment']);
    }
    echo '</tbody>
    </table>
    </div>';
} else {
    echo 'No rows!';
}
include $_SERVER['DOCUMENT_ROOT'] . '/lib/footer.php';
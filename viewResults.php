<?php
// Special values for this page:
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
include $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

startMpgSession();

Config::initDb();

$pageName = "view results";

include $_SERVER['DOCUMENT_ROOT'] . '/lib/header.php';

// Columns array:
$tableColumns = array();
$tableColumns['date'] = 'Date';
$tableColumns['car_name'] = 'Car Name';
$tableColumns['miles'] = 'Miles';
$tableColumns['gallons'] = 'Gallons';
$tableColumns['mpg'] = 'MPG';
$tableColumns['price_gallon'] = '$/Gal';
$tableColumns['total_price'] = '$';
$tableColumns['comment'] = 'Comment';

$sortFormat = 'order_by_%s';
//$sortBy = (array_key_exists('sort', $_GET) && array_key_exists($_GET['sort'], $tableColumns)) ? $_GET['sort'] : 'date';
$sortBy = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
if (is_null($sortBy) || !array_key_exists($sortBy, $tableColumns)) {
    $sortBy = 'date';
}
//$sortDir = (array_key_exists('dir', $_GET) && (strtolower($_GET['dir']) == 'asc' || strtolower($_GET['dir']) == 'desc')) ? sprintf($sortFormat, strtolower($_GET['dir'])) : sprintf($sortFormat, 'desc');
$sortDirTemp = filter_input(INPUT_GET, 'dir', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
if (is_null($sortDirTemp) || !($sortDirTemp == 'asc' || $sortDirTemp == 'desc')) {
    if ($sortBy == 'date') {
        $sortDirTemp = 'desc';
    } else {
        $sortDirTemp = 'asc';
    }
}
$sortDir = sprintf($sortFormat, $sortDirTemp);

// Sort Links:
$sortLinks = array();
foreach ($tableColumns as $key => $value) {
    array_push($sortLinks, sprintf("<th><a href=\"viewResults.php?sort=%s&dir=%s\">%s</a></th>", $key, ($sortBy === $key and $sortDir === sprintf($sortFormat, 'asc')) ? 'desc' : 'asc', $value));
}
$records = Vehicle::select_many_expr(array('car_name' => "concat(v.model_year, ' ', v.make, ' ', v.model)"),
                                        array('mpg' => 'r.miles / r.gallons'),
                                        array('total_price' => 'r.gallons * r.price_gallon'))
                    ->select_many('r.date', 'r.miles', 'r.gallons', 'r.price_gallon', 'r.comment')
                    ->where('user_id', getUserId())->table_alias('v')
                    ->join('refueling', 'v.id = r.vehicle_id', 'r')
                    ->$sortDir($sortBy)
                    ->find_many();
echo ORM::get_last_query();
$displayValFormat = "<tr><td>%s</td><td>%s</td><td>%01.1f</td><td align=\"right\">%01.3f</td><td>%01.2f</td><td>\$%01.3f</td><td align=\"right\">\$%01.2f</td><td>%s</td></tr>";

if ($records) {
    echo '<div id="tableContainer"><table border="1" cellpadding="1" cellspacing="1"><thead class="fixedHeader"><tr>';
    foreach ($sortLinks as $value) {
        echo $value;
    }
    echo '</tr></thead><tbody>';
    $tableBody = '';
    foreach ($records as $record) {
        $tableBody .= sprintf($displayValFormat,
                                $record->date,
                                $record->car_name,
                                $record->miles,
                                $record->gallons,
                                $record->mpg,
                                $record->price_gallon,
                                $record->total_price,
                                $record->comment);
    }
    echo $tableBody;
    echo '</tbody></table></div>';
} else {
    echo 'No rows!';
}
include $_SERVER['DOCUMENT_ROOT'] . '/lib/footer.php';
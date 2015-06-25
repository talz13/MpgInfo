<?php
// Special values for this page:
include $_SERVER["DOCUMENT_ROOT"] . "/lib/globals.php";
$pageName = "view results";
//array_push($styles, "tablescroll.css");
include $_SERVER["DOCUMENT_ROOT"] . "/lib/header.php";

$con = mysql_connect($dbHost, $dbUser, $dbPass);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db($db, $con);
// Columns array:
$columns = array();
array_push($columns, 'date');
array_push($columns, 'carName');
array_push($columns, 'miles');
array_push($columns, 'gallons');
array_push($columns, 'mpg');
array_push($columns, 'priceGallon');
array_push($columns, 'totalPrice');
array_push($columns, 'comment');

// Sorting code:
$sortArray = array();
for ($i = 0; $i < count($columns); $i++) {
    array_push($sortArray, ' ' . $columns[$i] . ' asc');
}
for ($i = count($columns); $i < count($columns) * 2; $i++) {
    array_push($sortArray, ' ' . $columns[$i - count($columns)] . ' desc');
}

$sortBy = '';
$prevSort = '';
if (array_key_exists('sort', $_GET)) {
    if (is_numeric($_GET['sort'])) {
        if (is_int(intval($_GET['sort']))) {
            $sortBy = $_GET['sort'];
        }
    }
} else {
    $sortBy = 8;
}

// Sort Links:
$sortLinks = array();
for ($i = 0; $i < count($sortArray); $i++) {
    if ($i < count($sortArray) / 2) {
        array_push($sortLinks, '<th><a href="viewResults.php?sort=' . $i . '">' . ucwords(str_replace('_', ' ', str_replace('totalPrice', '$', str_replace('priceGallon', '$/gal', $columns[$i])))) . '</a></th>');
    } else {
        array_push($sortLinks, '<th><a href="viewResults.php?sort=' . $i . '">' . ucwords(str_replace('_', ' ', str_replace('totalPrice', '$', str_replace('priceGallon', '$/gal', $columns[$i - count($columns)])))) . '</a></th>');
    }
}
$query = "SELECT m.date, concat(v.model_year,' ', v.make, ' ', v.model) as carName, m.miles, m.gallons, m.mpg, m.priceGallon, m.totalPrice, m.comment FROM `mileage` as m, `vehicles` as v where m.vehicle=v.vehicle_index order by " . $sortArray[$sortBy];

#<td>" . $row['date'] . "</td><td>" . $row['carName'] . "</td><td>" . $row['miles'] . "</td><td>" . $row['gallons'] . "</td><td>" . number_format($row['mpg'], 2, '.', ',') . "</td><td>$" . number_format($row['priceGallon'], 3, '.', ',') . "</td><td>$" . number_format($row['totalPrice'], 2, '.', ',') . "</td><td>" . $row['comment'] . "</td>";
#                        date,      name,      miles,                         gallons,       mpg,           price/gal,       total price,     comments
$displayValFormat = "<td>%s</td><td>%s</td><td>%01.1f</td><td align=\"right\">%01.3f</td><td>%01.2f</td><td>\$%01.2f</td><td>\$%01.2f</td><td>%s</td>";

$result = mysql_query($query);
$numrows = mysql_num_rows($result);
?>
<div id="tableContainer">
    <table border="1" cellpadding="1" cellspacing="1"><?php if ($numrows > 0) { ?>
            <thead class="fixedHeader">
                <tr>
                    <?php
                    for ($i = 0; $i < count($sortLinks) / 2; $i++) {
                        if ($i === $sortBy) {
                            echo "\n\t\t\t\t\t\t\t" . $sortLinks[$i + count($columns)];
                        } else {
                            echo "\n\t\t\t\t\t\t\t" . $sortLinks[$i];
                        }
                    }
                    #echo "\n\t\t\t\t\t\t\t<th>&nbsp;</th>";
                    ?></tr>
            </thead>
            <tbody>
                <?php while ($row = mysql_fetch_assoc($result)) { ?>
                    <tr><?php
                        #<td>" . $row['date'] . "</td><td>" . $row['carName'] . "</td><td>" . $row['miles'] . "</td><td>" . $row['gallons'] . "</td><td>" . number_format($row['mpg'], 2, '.', ',') . "</td><td>$" . number_format($row['priceGallon'], 3, '.', ',') . "</td><td>$" . number_format($row['totalPrice'], 2, '.', ',') . "</td><td>" . $row['comment'] . "</td>";
                        printf($displayValFormat, $row['date'], $row['carName'], $row['miles'], $row['gallons'], $row['mpg'], $row['priceGallon'], $row['totalPrice'], $row['comment']);
                        ?></tr>
                <?php } ?>
            </tbody>
            <?php
        }
        mysql_close($con);
        echo "\n";
        ?>
    </table>
</div><?php
include $_SERVER["DOCUMENT_ROOT"] . "/lib/footer.php";

<?php
include "globals.php";
//$dbh = new PDO("mysql:host=$dbHost;dbname=$db",$dbUser,$dbPass);
//foreach ($dbh->query('select * from mileage') as $row) {
//    print_r($row);
//}
$siteRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require sprintf('%s%s', $siteRoot, '/vendor/autoload.php');

include "../lib/funcs.php";

$g = filter_input(INPUT_POST, 'g');
if (!isset($g)) {
    $g = filter_input(INPUT_GET, 'g');
}

$cols = filter_input(INPUT_POST, 'cols');
if (!isset($cols)) {
    $cols = filter_input(INPUT_GET, 'cols');
}

$debug = filter_input(INPUT_POST, 'debug');
if (!isset($debug)) {
    $debug = filter_input(INPUT_GET, 'debug');
}

if (isset($cols)) {
    getCols($cols, $debug);
} else if (isset($g)) {
    getData($g, $debug);
}

function getData($dataToRetrieve, $debug) {
    $data = null;
    switch ($dataToRetrieve) {
//        case 'mpg':
//            $data = getMpgData();
//            break;
//        case 'price':
//            $data = getPriceData();
//            break;
//        case 'both':
//        case 'dashboard':
//            $data = getBothData();
//            break;
        case 'table':
        case 'all':
            $data = getAllData();
            break;
        default:
            die(sprintf('Invalid option: %s', $dataToRetrieve));
    }
    if (isset($debug)) {
        echo json_encode($data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
    } else {
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }
}

function getAllData() {
    $mysqli = getConn();
    $query = "select date, miles, gallons, mpg, priceGallon, totalPrice, comment from mileage order by date asc";
    $result = $mysqli->query($mysqli->real_escape_string($query));

    $data = ['cols' => [['id' => strval(0),
        'label' => 'Date',
        'type' => 'date'
            ],
            ['id' => strval(1),
                'label' => 'Miles',
                'type' => 'number'
            ],
            ['id' => strval(2),
                'label' => 'Gal',
                'type' => 'number'
            ],
            ['id' => strval(3),
                'label' => 'MPG',
                'type' => 'number'
            ],
            ['id' => strval(4),
                'label' => '$/Gal',
                'type' => 'number'
            ],
            ['id' => strval(5),
                'label' => 'Total',
                'type' => 'number'
            ],
            ['id' => strval(6),
                'label' => 'Comment',
                'type' => 'string'
            ]
        ],
        'rows' => []
    ];
    while ($row = $result->fetch_object()) {
        $data['rows'][] = ['c' => [
                ['v' => strtotime($row->date) * 1000, 'f' => $row->date],
                ['v' => $row->miles],
                ['v' => $row->gallons],
                ['v' => $row->mpg],
                ['v' => $row->priceGallon],
                ['v' => $row->totalPrice],
                ['v' => $row->comment]]];
    }
    return $data;
}

//function getMpgData() {
//    $mysqli = getConn();
//    $query = "select date, mpg, priceGallon from mileage order by date asc";
//    $result = $mysqli->query($mysqli->real_escape_string($query));
//
//    $data = array('cols' => array(
//            array(
//                'id' => strval(0),
//                'label' => 'Date',
//                'type' => 'string'
//            ),
//            array(
//                'id' => strval(1),
//                'label' => 'MPG',
//                'type' => 'number'
//            )
//        ),
//        'rows' => array()
//    );
//    while ($row = $result->fetch_object()) {
//        $data['rows'][] = array('c' => array(
//                array('v' => $row->date),
//                array('v' => $row->mpg)));
//    }
//    return $data;
//}
//function getPriceData() {
//    $mysqli = getConn();
//    $query = "select date, priceGallon from mileage order by date asc";
//    $result = $mysqli->query($mysqli->real_escape_string($query));
//
//    $data = array('cols' => array(
//            array(
//                'id' => strval(0),
//                'label' => 'Date',
//                'type' => 'string'
//            //'type' => 'datetime'
//            ),
//            array(
//                'id' => strval(1),
//                'label' => '$/Gallon',
//                'type' => 'number'
//            )
//        ),
//        'rows' => array()
//    );
//    while ($row = $result->fetch_object()) {
//        $data['rows'][] = array('c' => array(
//                array('v' => $row->date),
//                array('v' => $row->priceGallon, 'f' => sprintf("$%01.3f", $row->priceGallon))));
//    }
//    return $data;
//}
//function getBothData() {
//    $mysqli = getConn();
//    $query = "select unix_timestamp(date) * 1000 as date, mpg, priceGallon from mileage order by date asc";
//    $result = $mysqli->query($mysqli->real_escape_string($query));
//
//    $data = array('cols' => array(
//            array(
//                'id' => strval(0),
//                'label' => 'Date',
//                //'type' => 'string'
//                'type' => 'number'
//            ),
//            array(
//                'id' => strval(1),
//                'label' => 'MPG',
//                'type' => 'number'
//            ),
//            array(
//                'id' => strval(2),
//                'label' => '$/Gallon',
//                'type' => 'number'
//            )
//        ),
//        'rows' => array()
//    );
//    while ($row = $result->fetch_object()) {
//        $data['rows'][] = array('c' => array(
//                array('v' => $row->date),
//                array('v' => $row->mpg),
//                array('v' => $row->priceGallon, 'f' => sprintf("$%01.3f", $row->priceGallon))));
//    }
//    return $data;
//}
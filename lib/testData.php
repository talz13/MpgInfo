<?php
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/globals.php';
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/MpgDb.php';

startMpgSession();

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

if (isset($g)) {
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
//    $mysqli = getConn();
    $db = new MpgDb();
    $query = "select date, miles, gallons, priceGallon, comment from refuelings order by date asc";
    $db->runQuery($query);

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
    while ($row = $db->getRow()) {
        $data['rows'][] = ['c' => [
                ['v' => strtotime($row->date) * 1000, 'f' => $row->date],
                ['v' => $row->miles],
                ['v' => $row->gallons],
                ['v' => $row->miles / $row->gallons],
                ['v' => $row->priceGallon],
                ['v' => $row->priceGallon * $row->gallons],
                ['v' => $row->comment]]];
    }
    return $data;
}

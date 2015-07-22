<?php
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/globals.php';

$maxSizeX = 1920;
$maxSizeY = 1080;
$minSizeX = 100;
$minSizeY = 75;
$defaultX = 800;
$defaultY = 600;

function getImgSize() {
    if ($xSize = filter_input(INPUT_GET, 'sizex', FILTER_VALIDATE_INT)) {
        if ($xSize > Config::getXMax()) {
            $xSize = Config::getXMax();
        }
    } else {
        $xSize = Config::getXFull();
    }
    if ($ySize = filter_input(INPUT_GET, 'sizey', FILTER_VALIDATE_INT)) {
        if ($ySize > Config::getYMax()) {
            $ySize = Config::getYMax();
        }
    } else {
        $ySize = Config::getYFull();
    }
    $fontRatio = $xSize / Config::getXFull();

    return array($xSize, $ySize, $fontRatio);
}

function checkLogin() {
    return isset($_SESSION['userId']) ? true : false;
}

function checkVehicleId($testId) {
    if (Vehicle::where('user_id', getUserId())->where('id', $testId)->count('*') == 1) {
        return true;
    } else {
        return false;
    }
}

function clearDefaultVehicles($vehicleId) {
    if (checkVehicleId($vehicleId)) {
        Vehicle::where('user_id', getUserId())->where_not_equal('id', $vehicleId)->find_result_set()->set('b_default', 0)->save();
//        Vehicle::where('id', $vehicleId)->find_one()->set('b_default', 1)->save();
    }
}

function getDefaultVehicle() {
    return Vehicle::where('user_id', getUserId())->where('b_default', 1)->find_one();
}
function getListOfVehicles() {
    return Vehicle::where('user_id', getUserId())->order_by_desc('b_default')->order_by_desc('purchase_date')->find_many();
}

function displayVehicleDropdown($action, $selectedId) {
    $form = sprintf('<form name="changeVehicle" action="%s" method="POST"><table border="1"><tr><td>Select Vehicle</td><td><select onchange="this.form.submit()" name="vehicle_id">', $action);
    foreach(getListOfVehicles() as $vehicleListItem) {
        $form .= sprintf('<option value="%d"%s>%s %s %s</option>', $vehicleListItem->id, $vehicleListItem->id == $selectedId ? ' SELECTED' : '', $vehicleListItem->model_year, $vehicleListItem->make, $vehicleListItem->model);
    }
    $form .= '</select></td><td><input type="submit"</td></tr></table></form>';
    echo $form;
}

function displayLoginLink() {
    printf('<table><tr><td><h2>Please <a href="%s">log in</a></h2></td></tr></table>', buildLocalPath('/login.php'));
}

function getUserId() {
	if (isset($_SESSION['userId']))	{
        return $_SESSION['userId'];
	} else {
		return -1;
	}
}

function setUserId($id) {
    $_SESSION['userId'] = $id;
}

function buildLocalPath($rootRelativePath) {
    if ($rootRelativePath != '' and (strpos($rootRelativePath, '/') != 0 or strpos($rootRelativePath, '\\') === 0)) {
        $rootRelativePath = substr($rootRelativePath, 1, strlen($rootRelativePath) - 1);
    }
    if(isset($_SERVER['HTTPS'])) {
        $protocol = (filter_input(INPUT_SERVER, 'HTTPS') && filter_input(INPUT_SERVER, 'HTTPS') != "off") ? "https" : "http";
    }
    else {
        $protocol = 'http';
    }
    return sprintf('%s://%s%s', $protocol, Config::getBaseUrl(), $rootRelativePath);
}

function startMpgSession() {
    if (!isset($_SESSION)) {
        session_start();
    }
}

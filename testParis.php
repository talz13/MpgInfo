<?php
/**
 * Created by PhpStorm.
 * User: jeff
 * Date: 7/13/2015
 * Time: 1:15 PM
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
include $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

ORM::configure(sprintf('mysql:host=%s;dbname=%s', Config::getDbHost(), Config::getDb()));
ORM::configure('username', Config::getDbUser());
ORM::configure('password', Config::getDbPass());

ORM::configure('logging', true);

$user = User::where('username', 'talz13')->find_one();

//var_dump($user);
//var_dump($vehicles);

//echo array_column($vehicles, 'make');
//$records = Vehicle::where('user_id', $user->id)->table_alias('v')->join('refueling', 'v.id = r.vehicle_id', 'r')->find_many();
//var_dump($records);
//foreach ($records as $record) {
//    echo serialize($record);
//}
$records = Refueling::where('vehicle_id', 2);
printf('Min Date: %s<br>', $records->min('date'));
printf('Max Date: %s<br>', $records->max('date'));
printf('Timespan: %s<br>', strtotime($records->max('date')) - strtotime($records->min('date')));

echo ORM::get_last_query();
ORM::get_query_log();
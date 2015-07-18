<?php
/**
 * Created by PhpStorm.
 * User: jeff
 * Date: 7/13/2015
 * Time: 1:30 PM
 */

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class Trip extends Model {
    public function tripDetails() {
        return $this->has_many('TripDetail');
    }

    public function vehicle() {
        return $this->belongs_to('Vehicle');
    }
}
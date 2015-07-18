<?php
/**
 * Created by PhpStorm.
 * User: jeff
 * Date: 7/13/2015
 * Time: 1:31 PM
 */

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class Vehicle extends Model {
    public function refuelings() {
        return $this->has_many('Refueling');
    }

    public function services() {
        return $this->has_many('Service');
    }

    public function trips() {
        return $this->has_many('Trip');
    }

    public function user() {
        return $this->belongs_to('User');
    }
}
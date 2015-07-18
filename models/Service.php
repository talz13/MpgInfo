<?php
/**
 * Created by PhpStorm.
 * User: jeff
 * Date: 7/13/2015
 * Time: 1:29 PM
 */

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class Service extends Model {
    public function serviceLineItems() {
        return $this->has_many('ServiceLineItem');
    }

    public function vehicle() {
        return $this->belongs_to('Vehicle');
    }
}
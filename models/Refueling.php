<?php
/**
 * Created by PhpStorm.
 * User: jeff
 * Date: 7/13/2015
 * Time: 1:21 PM
 */

require filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/vendor/autoload.php';

class Refueling extends Model {
    public function vehicle() {
        return $this->belongs_to('Vehicle');
    }
}

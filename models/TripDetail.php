<?php
/**
 * Created by PhpStorm.
 * User: jeff
 * Date: 7/13/2015
 * Time: 1:29 PM
 */

require filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/vendor/autoload.php';

class TripDetail extends Model {
    public function trip() {
        return $this->belongs_to('Trip');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: jeff
 * Date: 7/13/2015
 * Time: 1:28 PM
 */

require filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/vendor/autoload.php';

class ServiceLineItem extends Model {
    public function service() {
        return $this->belongs_to('Service');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: jeff
 * Date: 7/13/2015
 * Time: 1:30 PM
 */

require filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/vendor/autoload.php';

class User extends Model {
    public function vehicles() {
        return $this->has_many('Vehicle');
    }

    public function logins() {
        return $this->has_many('Login');
    }
}

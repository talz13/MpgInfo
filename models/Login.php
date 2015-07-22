<?php

/**
 * Created by PhpStorm.
 * User: jeff
 * Date: 7/13/2015
 * Time: 1:27 PM
 */

require filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/vendor/autoload.php';

class Login extends Model{
    public function user() {
        return $this->belongs_to('User');
    }
}

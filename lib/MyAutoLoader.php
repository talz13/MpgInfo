<?php

/**
 * Created by PhpStorm.
 * User: jeff
 * Date: 7/13/2015
 * Time: 3:07 PM
 */

spl_autoload_register('MyAutoLoader::ModelLoader');

class MyAutoLoader {

    public static function ModelLoader($className) {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/models/';
         include $path.$className.'.php';
    }
}
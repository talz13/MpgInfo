<?php
/**
 * Created by PhpStorm.
 * User: Jeff
 * Date: 6/30/2015
 * Time: 7:52 PM
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array('/path/to/entity-files');
$isDevMode = false;

// the connection configuration
$dbParms = array(
    'driver'    => 'pdo_mysql',
    'user'      => Config::getDbUser(),
    'password'  => Config::getDbPass(),
    'dbname'    => Config::getDb()
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParms, $config);
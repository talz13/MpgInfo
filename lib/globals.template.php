<?php

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/funcs.php';
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/MyAutoLoader.php';
require filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/vendor/autoload.php';

class Config
{
    private static $siteName = "Car Info";
    private static $dbUser = "";
    private static $dbPass = "";
    private static $dbHost = "";
    private static $db = "";

    private static $xMax = 1024;
    private static $yMax = 1024;
    private static $xThumb = 320;
    private static $yThumb = 240;
    private static $xFull = 800;
    private static $yFull = 600;
    private static $graphBackgroundColors = ['#EFEFEF@0.5', '#BBCCFF@0.5']; // Alternating colors for graph background

    private static $currencyFormatStr = '$%01.2f';

    private static $baseUrl = '';

    public static function initDb($debug = false) {
        ORM::configure(sprintf('mysql:host=%s;dbname=%s', Config::getDbHost(), Config::getDb()));
        ORM::configure('username', Config::getDbUser());
        ORM::configure('password', Config::getDbPass());

        if ($debug) {
            ORM::configure('logging', true);
        }
    }

    /**
     * @return string
     */
    public static function getSiteName()
    {
        return self::$siteName;
    }

    /**
     * @return string
     */
    public static function getDbUser()
    {
        return self::$dbUser;
    }

    /**
     * @return string
     */
    public static function getDbPass()
    {
        return self::$dbPass;
    }

    /**
     * @return string
     */
    public static function getDbHost()
    {
        return self::$dbHost;
    }

    /**
     * @return string
     */
    public static function getDb()
    {
        return self::$db;
    }

    /**
     * @return int
     */
    public static function getXMax()
    {
        return self::$xMax;
    }

    /**
     * @return int
     */
    public static function getYMax()
    {
        return self::$yMax;
    }

    /**
     * @return int
     */
    public static function getXThumb()
    {
        return self::$xThumb;
    }

    /**
     * @return int
     */
    public static function getYThumb()
    {
        return self::$yThumb;
    }

    /**
     * @return int
     */
    public static function getXFull()
    {
        return self::$xFull;
    }

    /**
     * @return int
     */
    public static function getYFull()
    {
        return self::$yFull;
    }

    /**
     * @return string
     */
    public static function getCurrencyFormatStr()
    {
        return self::$currencyFormatStr;
    }

    /**
     * @return array
     */
    public static function getGraphBackgroundColors()
    {
        return self::$graphBackgroundColors;
    }

    /**
     * @return string
     */
    public static function getBaseUrl()
    {
        return self::$baseUrl;
    }
}

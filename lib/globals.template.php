<?php

class Config
{
    private static $siteName = "car info";
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
}
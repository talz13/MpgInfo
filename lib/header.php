<?php
//ob_start('ob_tidyhandler');
//ob_start("ob_tidyhandler");
//include "lib/globals.php";
//function displayHeader($scripts, $styles) {
//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title><?php
            echo $siteName;
            if (isset($pageName)) {
                echo " - " . $pageName;
            }
            ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=US-ASCII">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/styles/newcss.css" type="text/css">
    </head>
    <body>
        <div class="outerContainer">
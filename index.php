<?php
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/globals.php';

startMpgSession();

include filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/header.php';
?>
    <h1><?=Config::getSiteName()?></h1>
    <a href="graphs/graphQuery.php?graph=mpg"><?=sprintf('<img src="graphs/graphQuery.php?graph=mpg&sizex=%1$d&amp;sizey=%2$d" width="%1$d" height="%2$d" alt="MPG">', Config::getXThumb(), Config::getYThumb())?></a>
    <a href="graphs/graphQuery.php?graph=price"><?=sprintf('<img src="graphs/graphQuery.php?graph=price&sizex=%1$d&amp;sizey=%2$d" width="%1$d" height="%2$d" alt="Price per gallon">', Config::getXThumb(), Config::getYThumb())?></a>
<?php
include filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/footer.php';

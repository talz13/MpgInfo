<?php

include ("../lib/jpgraph-3.0.7/src/jpgraph.php");
include ("../lib/jpgraph-3.0.7/src/jpgraph_line.php");
include ("../lib/avg.php");
include ("../lib/funcs.php");
include ("../lib/globals.php");

// Get size from _GET:
$size = getImgSize($_GET);
//$sizex = $size[0];
//$sizey = $size[1];
// Create graph:
$graph = new Graph($size[0], $size[1], "auto", 5);
$graph->SetScale("textlin");
//$graph->img->SetMargin(40,40,20,80);
//$graph->legend->SetLayout(LEGEND_HOR);
//$graph->legend->SetPos(0.1, 0.025);
$graph->yaxis->SetLabelFormat('$%01.2f');

// Get data from db:
$con = mysql_connect($dbHost, $dbUser, $dbPass);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db($db, $con);

$result = mysql_query("select date, priceGallon from mileage order by date asc");
$numrows = mysql_num_rows($result);
$xdata = array();
$ydata = array();
$average = array();
//$centralAverage = array();
if ($numrows > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        //echo "<br>date: ".$row['date']." mpg: ".$row['mpg'];
        array_push($xdata, $row['date']);
        array_push($ydata, $row['priceGallon']);
    }
    //$centralAverage = centralAverage($ydata, 3);
    $average = overallAverage($ydata);
} else {
    array_push($xdata, "no dates");
    array_push($ydata, 0);
    //array_push($average, 0);
    //array_push($centralAverage, 0);
}
// Get spacing interval for tick labels:
$interval = count($xdata) / 25;

$graph->xaxis->SetTickLabels($xdata);
$graph->xaxis->SetTextTickInterval($interval);
$graph->xaxis->SetFont(FF_VERA, FS_NORMAL, 10 * $size[2]);
$graph->xaxis->SetLabelAngle(65);

// Set a title:
$graph->title->Set("$/gal each fillup from\n" . $xdata[0] . " to " . $xdata[count($xdata) - 1]);
$graph->title->SetFont(FF_VERA, FS_BOLD, 15 * $size[2]);

// Autoscale upper bound on y axis:
$graph->yaxis->scale->SetAutoMin(0);

// Filled grid:
$graph->ygrid->SetFill(true, '#EFEFEF@0.5', '#BBCCFF@0.5');

// X gridlines:
$graph->xgrid->Show(true);

// Create the linear plot
$lineplot = new LinePlot($ydata);
$lineplot->SetColor("orange");
$lineplot->SetWeight(2);
//$lineplot->mark->SetType(MARK_STAR);
//$lineplot->SetLegend("$/gal");
// Create the centralAverage plot
//$lineplot2 = new LinePlot($centralAverage);
//$lineplot2->SetColor("blue");
//$lineplot2->SetWeight(2);
// Create the overall average plot
$lineplot3 = new LinePlot($average);
$lineplot3->SetColor("red");
$lineplot3->SetWeight(2);

// Add the plot to the graph
$graph->Add($lineplot);
//$graph->Add($lineplot2);
$graph->Add($lineplot3);

// Display the graph
$graph->Stroke();
?>

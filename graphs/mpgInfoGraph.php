<?php
/**
 * Created by PhpStorm.
 * User: Jeff
 * Date: 6/25/2015
 * Time: 3:54 PM
 */

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
//require_once ($_SERVER['DOCUMENT_ROOT'] . '/lib/avg.php');
//require_once ($_SERVER['DOCUMENT_ROOT'] . '/lib/funcs.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';

function drawGraph($xData, $yData, $average, $interval, $title, $yAxisType='number')
{
    JpGraph\JpGraph::load();
    JpGraph\JpGraph::module('line');

    // Get size from _GET:
    $size = getImgSize($_GET);

    if ($size[0] > Config::getXMax()) {
        $size[0] = Config::getXMax();
    }
    if ($size[1] > Config::getYMax()) {
        $size[1] = Config::getYMax();
    }

    // Create graph:
    $graph = new Graph($size[0], $size[1], "auto", 5);
    $graph->SetScale("textlin");
    switch ($yAxisType) {
        case 'currency':
            $graph->yaxis->SetLabelFormat(Config::getCurrencyFormatStr());
            break;
    }

    $graph->xaxis->SetTickLabels($xData);
    $graph->xaxis->SetTextTickInterval($interval);
    $graph->xaxis->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 10 * $size[2]);
    $graph->xaxis->SetLabelAngle(65);

    // Set a title:
    $graph->title->Set($title);
    $graph->title->SetFont(FF_DV_SANSSERIF, FS_BOLD, 15 * $size[2]);

    // Autoscale upper bound on y axis:
    $graph->yaxis->scale->SetAutoMin(0);

    // Filled grid:
    $graph->ygrid->SetFill(true, Config::getGraphBackgroundColors()[0], Config::getGraphBackgroundColors()[0]);

    // X gridlines:
    $graph->xgrid->Show(true);

    // Create the linear plot
    $dataLinePlot = new LinePlot($yData);
    $dataLinePlot->SetColor("orange");
    $dataLinePlot->SetWeight(2);
    // Create the overall average plot
    $averageLinePlot = new LinePlot($average);
    $averageLinePlot->SetColor("red");
    $averageLinePlot->SetWeight(2);

    // Add the plot to the graph
    $graph->Add($dataLinePlot);
    $graph->Add($averageLinePlot);

    // Display the graph
    $graph->Stroke();
}
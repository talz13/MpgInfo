<?php
include ("../lib/jpgraph-3.0.7/src/jpgraph.php");
include ("../lib/jpgraph-3.0.7/src/jpgraph_line.php");
include ("../lib/avg.php");
include ("../lib/funcs.php");
include ("../lib/globals.php");

// Get size from _GET:
$size = getImgSize($_GET);

if ($size[0] > 1024)
{
	$size[0] = 1024;
}
if ($size[1] > 1024)
{
	$size[1] = 1024;
}

// Create graph:
$graph = new Graph($size[0],$size[1],"auto", 5);
$graph->SetScale("textlin");

// Get data from db:
$con = mysql_connect($dbHost, $dbUser, $dbPass);
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($db, $con);

$vehicle_id = mysql_real_escape_string($_GET['vehicle_id']);

$query = "select m.date as date, m.mpg as mpg from mileage as m ";
if (!is_null($vehicle_id) and is_numeric($vehicle_id))
{
	$query .= ", vehicles as v where m.vehicle = v.vehicle_index and v.vehicle_index = $vehicle_id ";
}
$query .= "order by m.date asc";

#echo $query;


#$result = mysql_query("select date, mpg from mileage order by date asc");
$result = mysql_query(mysql_real_escape_string($query));
$numrows = mysql_num_rows($result);
$xdata = array();
$ydata = array();
//$y2data = array();
$average = array();
//$centralAverage = array();
if ($numrows > 0)
{
	while ($row = mysql_fetch_assoc($result))
	{
		array_push($xdata, $row['date']);
		array_push($ydata, $row['mpg']);
		//array_push($y2data, $row['priceGallon']);
	}
	//$centralAverage = centralAverage($ydata, 7);
	$average = overallAverage($ydata);
}
else
{
	array_push($xdata, "no dates");
	array_push($ydata, 0);
	//array_push($average, 0);
	array_push($centralAverage, 0);
}
// Get spacing interval for tick labels:
$interval = count($xdata)/25;

$graph->xaxis->SetTickLabels($xdata);
$graph->xaxis->SetTextTickInterval($interval);
$graph->xaxis->SetFont(FF_VERA,FS_NORMAL,10*$size[2]);
$graph->xaxis->SetLabelAngle(65);

// Set a title:
$graph->title->Set("MPG each fillup from\n".$xdata[0]." to ".$xdata[count($xdata)-1]);
$graph->title->SetFont(FF_VERA,FS_BOLD,15*$size[2]);

// Autoscale upper bound on y axis:
$graph->yaxis->scale->SetAutoMin(0);

// Filled grid:
$graph->ygrid->SetFill(true, '#EFEFEF@0.5','#BBCCFF@0.5');

// X gridlines:
$graph->xgrid->Show(true);

// Create the linear plot
$lineplot = new LinePlot($ydata);
$lineplot->SetColor("orange");
$lineplot->SetWeight(2);
//$lineplot->mark->SetType(MARK_SQUARE);
//$lineplot->SetLegend("MPG");

// Create the centralAverage plot
//$lineplot2 = new LinePlot($centralAverage);
//$lineplot2->SetColor("blue");
//$lineplot2->SetWeight(2);

// Create the overall average plot
$lineplot3 = new LinePlot($average);
$lineplot3->SetColor("red");
$lineplot3->SetWeight(2);

// Create the $/gal plot:
//$lineplot4 = new LinePlot($y2data);
//$lineplot4->SetColor("red");
//$lineplot4->mark->SetType(MARK_DIAMOND);
//$lineplot4->SetLegend("$/gal");
//$lineplot4->SetWeight(2);
//$graph->SetY2Scale("lin");
//$graph->y2axis->scale->SetAutoMin(0);
//$graph->y2axis->SetLabelFormat('$%01.2f');

// Add the plot to the graph
$graph->Add($lineplot);
//$graph->Add($lineplot2);
$graph->Add($lineplot3);
//$graph->AddY2($lineplot4);

// Display the graph
$graph->Stroke();
?>

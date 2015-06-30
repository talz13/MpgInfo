<?php
function overallAverage($inputArray)
{
	$outputArray = array();
	$average = array_sum($inputArray)/count($inputArray);
	foreach($inputArray as $element)
	{
		array_push($outputArray, $average);
	}
	
	return $outputArray;
}
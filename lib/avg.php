<?php
function centralAverage($inputArray, $daysBeforeAndAfter)
{
	$outputArray = array();
	$maxAway = $daysBeforeAndAfter;
	$length = count($inputArray);

	for($i = 0; $i < $length; $i++)
	{
		$tempArray = array();
		if ((($i-$maxAway) >= 0) && (($i+$maxAway) <= $length))
			$type='a';
		if (($i-$maxAway) < 0)
			$type='b';
		if (($i+$maxAway) > $length)
			$type='c';
		
		switch ($type)
		{
			case 'a':
				for($j = $i-$maxAway; $j <= $i+$maxAway; $j++)
				{
					array_push($tempArray, $inputArray[$j]);
				}
				break;
			case 'b':
				for($j = 0; $j <= $i+$maxAway; $j++)
				{
					array_push($tempArray, $inputArray[$j]);
				}
				break;
			case 'c':
				for($j = ($i-$maxAway); $j < $length; $j++)
				{
					array_push($tempArray, $inputArray[$j]);
				}
				break;
		}
		$average = array_sum($tempArray)/count($tempArray);
		array_push($outputArray, $average);
		$tempArray = null;
	}

	return $outputArray;
}

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
?>

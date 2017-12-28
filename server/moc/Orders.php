<?php
/*
This is what an empty state.json should look like:
{"inventoryRooms":{"assembly":[],"bodies":[],"engines":[],"wheels":[]},"orders":[]}
*/
$state = json_decode(file_get_contents('state.json'));

if($state == null)
{
	$state = json_decode('{"inventoryRooms":{"assembly":[],"bodies":[],"engines":[],"wheels":[]},"orders":[]}');
}

if (isset($_REQUEST['get']))
{
	$i = 0;
	foreach($state->orders as $key => $var){
		if($var->status == 0){
			$i = $i + 1;
		}
	}
	if($i > 3){
		header("503​ ​Service​ ​Unavailable", true, 503);
	}
}
if (isset($_REQUEST['post']))
{
	$i = 0;
	foreach($state->orders as $key => $var){
		if($var->status == 0){
			$i = $i + 1;
		}
	}
	if($i > 3){
		header("503​ ​Service​ ​Unavailable", true, 503);
	} else {
		$orderID = count($state->orders);
		$var = (object) [
			"id" => $orderID,
			"status" => 0
			];
		array_unshift($state->orders, $var);
		echo json_encode($var);
	}
}

file_put_contents('state.json', json_encode($state));
?>
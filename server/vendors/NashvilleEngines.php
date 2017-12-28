<?php
/*
This is what an empty state.json should look like:
{"inventoryRooms":{"assembly":[],"bodies":[],"engines":[],"wheels":[]},"orders":[]}
*/

if (isset($_REQUEST['post']))
{
	$partOrder = json_decode($_REQUEST['post']);
	sleep(1);
	/*
	$carPart = (object) [
		"type" => $partOrder.part,
		"color" => 1,
		"option" => $partOrder.option,
		"orderID" => $partOrder.orderID
		]; */
	$carPart = new stdClass;
	$carPart->type = $partOrder->part;
	$carPart->color = 1;
	$carPart->option = $partOrder->option;
	$carPart->orderID = $partOrder->orderID;

	if($partOrder->part != 2){ //enum PartType Engine = 2
		header("400 ​Service​ ​Unavailable", true, 400);
	} else {
		echo json_encode($carPart);
	}
}

?>
<?php
/*
This is what an empty state.json should look like:
{"inventoryRooms":{"assembly":[],"bodies":[],"engines":[],"wheels":[]},"orders":[]}
*/

if (isset($_REQUEST['post']))
{
	$paintOrder = json_decode($_REQUEST['post']);
	//sleep(4);
	/*
	$carPart = (object) [
		"type" => $partOrder.part,
		"color" => 1,
		"option" => $partOrder.option,
		"orderID" => $partOrder.orderID
		]; */
	$carPart = new stdClass;
	$carPart->type = $paintOrder->part->type;
	$carPart->color = $paintOrder->color;
	$carPart->option = $paintOrder->part->option;
	$carPart->orderID = $paintOrder->part->orderID;

	echo json_encode($carPart);
}

?>
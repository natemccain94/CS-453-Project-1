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

$oldestOrderID = -1;
$oldestOrderFound = false;
$bad = false;
$bads = false;
foreach($state->orders as $i => $x)
{
	if($x->status == 0)
	{
		$oldestOrderID = $x->id;
		$oldestOrderFound = true;
	}
	if($oldestOrderID == -1)
	{
		$oldestOrderFound = false;
	}
}

if(isset($_REQUEST['delete']))
{
	$orderID = json_decode($_REQUEST['delete']);
	if($orderID != $oldestOrderID)
	{
		$bad = true;
	}
	else
	{
		if(count($state->inventoryRooms->assembly) == 3)
		{
			$bodyFound = false;
			$wheelFound = false;
			$engineFound = false;
			foreach($state->inventoryRooms->assembly as $var)
			{
				switch ($var->type) {
					case 1:
						if($var->orderID == $orderID)
						{
							$bodyFound = true;
						}
						else
						{
							$bad = true;
						}
						break;
					case 2:
						if($var->orderID == $orderID)
						{
							$engineFound = true;
						}
						else
						{
							$bad = true;
						}
						break;
					case 3:
						if($var->orderID == $orderID)
						{
							$wheelFound = true;
						}
						else
						{
							$bad = true;
						}
						break;
				}
			}
		}
		else
		{
			$bad = true;
		}
	}
	if($bad)
	{
		header("400 Bad Request", true, 400);
	}
	else
	{
		array_shift($state->inventoryRooms->assembly);
		array_shift($state->inventoryRooms->assembly);
		array_shift($state->inventoryRooms->assembly);
		//setting order status to Completed
		foreach($state->orders as $var)
		{
			if($var->id == $orderID)
			{
				$var->status = 1;
			}
		}

		foreach($state->orders as $key => $y)
		{
			if($y->status == 0)
			{
				$oldestOrderID = $y->id;
				$oldestOrderFound = true;
			}
			if($oldestOrderID == -1)
			{
				$oldestOrderFound = false;
			}
		}
		if($oldestOrderFound)
		{
			foreach($state->inventoryRooms->bodies as $key => $y)
			{
				if($y->orderID == $oldestOrderID)
				{
					array_push($state->inventoryRooms->assembly, $y);
					array_splice($state->inventoryRooms->bodies, $key , 1);
				}
			}
			foreach($state->inventoryRooms->wheels as $key => $y)
			{
				if($y->orderID == $oldestOrderID)
				{
					array_push($state->inventoryRooms->assembly, $y);
					array_splice($state->inventoryRooms->wheels, $key , 1);
				}
			}
			foreach($state->inventoryRooms->engines as $key => $y)
			{
				if($y->orderID == $oldestOrderID)
				{
					array_push($state->inventoryRooms->assembly, $y);
					array_splice($state->inventoryRooms->engines, $key , 1);
				}
			}
		}
	}
	$bodyFound = false;
	$wheelFound = false;
	$engineFound = false;
	foreach($state->inventoryRooms->assembly as $var)
	{
		switch ($var->type) {
			case 1:
				$bodyFound = true;
				break;
			case 2:
				$engineFound = true;
				break;
			case 3:
				$wheelFound = true;
				break;
		}
	}
	$assemblyStatus = new stdClass;
	$assemblyStatus->orderID = $oldestOrderID;
	$assemblyStatus->readyParts = $state->inventoryRooms->assembly;
	if($bodyFound && $wheelFound && $engineFound)
	{
		$assemblyStatus->readyToAssemble = true;
	}
	else
	{
		$assemblyStatus->readyToAssemble = false;
	}
	echo json_encode($assemblyStatus);
}

if(isset($_REQUEST['get']))
{
	$bodyFound = false;
	$wheelFound = false;
	$engineFound = false;
	foreach($state->inventoryRooms->assembly as $var)
	{
		switch ($var->type) {
			case 1:
				$bodyFound = true;
				break;
			case 2:
				$engineFound = true;
				break;
			case 3:
				$wheelFound = true;
				break;
		}
	}
	$assemblyStatus = new stdClass;
	$assemblyStatus->orderID = $oldestOrderID;
	$assemblyStatus->readyParts = $state->inventoryRooms->assembly;
	if($bodyFound && $wheelFound && $engineFound)
	{
		$assemblyStatus->readyToAssemble = true;
	}
	else
	{
		$assemblyStatus->readyToAssemble = false;
	}
	echo json_encode($assemblyStatus);
}

if (isset($_REQUEST['post']))
{
	$orderIDFound = false;
	$existingOrderFound = false;
	$carPart = json_decode($_REQUEST['post']);
	foreach($state->orders as $key => $var)
	{
		if($var->id == $carPart->orderID && $existingOrderFound != true)
		{
			$existingOrderFound = true;
		}
	}
	if($existingOrderFound && $oldestOrderID == $carPart->orderID)
	{
		foreach($state->inventoryRooms->assembly as $var)
		{
			if($var->type == $carPart->type)
			{
				$bad = true;
			}
		}
		if(!$bad)
		{
			array_unshift($state->inventoryRooms->assembly, $carPart);
		}
	}
	else
	{
		switch($carPart->type) 
		{
			//Body
			case 1:
				foreach($state->inventoryRooms->bodies as $var)
				{
					if($var->orderID == $carPart->orderID)
					{
						$bads = true;
					}
				}
				if(count($state->inventoryRooms->bodies) > 3)
				{
					$bad = true;
				}
				if(!$bad)
				{
					array_unshift($state->inventoryRooms->bodies, $carPart);
				}
				break;
			//Engine
			case 2:
				foreach($state->inventoryRooms->engines as $var)
				{
					if($var->orderID->orderID == $carPart->orderID)
					{
						$bad = true;
					}
				}
				if(count($state->inventoryRooms->engines) > 3)
				{
					$bad = true;
				}
				if(!$bad)
				{
					array_unshift($state->inventoryRooms->engines, $carPart);
				}
				break;
			//Wheel
			case 3:
				foreach($state->inventoryRooms->wheels as $var)
				{
					if($var->orderID == $carPart->orderID)
					{
						$bad = true;
					}
				}
				if(count($state->inventoryRooms->wheels) > 3)
				{
					$bad = true;
				}
				if(!$bad)
				{
					array_unshift($state->inventoryRooms->wheels, $carPart);
				}
				break;

		}
	}
	if($bad || !$existingOrderFound)
	{
		header("400 Bad Request", true, 400);
	}
}



file_put_contents('state.json', json_encode($state));
?>
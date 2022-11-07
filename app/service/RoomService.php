<?php

include_once "../app/config/config.php";
include_once ROOT_PATH."api/Controller/RoomController.php";

$response = array();
if(isset($_REQUEST['service_name']) && !empty($_REQUEST['service_name'] ))
{
	$room = new RoomController();
	if($_REQUEST['service_name'] == 'book_room')
		$response = $room->bookroom($_REQUEST);
	else if($_REQUEST['service_name'] == 'list_room')
		$response = $room->listRoombookinghistory();
	else if($_REQUEST['service_name'] == 'cancel_room')
		$response = $room->cancelRoom($_REQUEST);
	else
		$response =array('code' => 400, 'status'=>'Provide the correct service');
}
else
{
	$response =array('code' => 400, 'status'=>'Missing service name');
}	
echo json_encode($response,true);








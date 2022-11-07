<?php
include_once ROOT_PATH."config/config.php";
include_once ROOT_PATH."model/Room_booking.php";
include_once ROOT_PATH."model/Room.php";

class RoomController	{
	
	
	public function RoomController(){
		
	}
		
	
	
	public function bookRoom($request){
		try{
			$insert_id=0;
			
			$response = $this->validateInput($request);
			if($response === true) {
					
				$room=new Room();
				$room_id_arr = $room->getByTitle($request['title']); 
				$start_date=new DateTime($request['start_at']);
				$end_date=new DateTime($request['end_at']);
				$start_day=$start_date->format('w');				
				$start_time = date_format($start_date , 'H:i:s');
				$end_time = date_format($end_date , 'H:i:s');
				$total_count = 0;
				$room_id = 0;
				if(count($room_id_arr) > 0) 
				{	
					for($i=0;$i<count($room_id_arr);$i++) {
						
						//checking the room availability days
						if(strpos($room_id_arr[$i]['days'], $start_day) === false)
						{
							$response=array('code' => 400, 'status' => 'Class is not available to your slot day.');
							break;
							return $response;
						}
						
						// checking the time availability of the room
						if(($start_time - $room_id_arr[$i]['start_time']) < 0 || ($end_time - $room_id_arr[$i]['end_time'])	 > 0)
						{
							$response=array('code' => 400, 'status' => 'Class is not available to your slottime.');
							break;
							return $response;
						}
						$total_count = $room_id_arr[$i]['total_count'];
						$room_id =  $room_id_arr[$i]['id'];
					}
					
					$rmbk = new Room_booking();
					$request['room_id'] = 	$room_id;//print_r($request);
					$lst = $rmbk->show($request);
					
					if(count($lst) >0){
						if($total_count <= $lst[0]['booking_count'] ) {
							$response=array('code' => 400, 'status' => 'Maximum booking reached.');
						}
						else 
						{
							$insert_id = $rmbk->update($request);
							if($insert_id > 0)
									$response=array('code' => 200, 'status' => 'Room booking updated Successfuly');
						}
					}
					else
					{
						$insert_id = $rmbk->insert($request);
						if($insert_id > 0)
						$response=array('code' => 200, 'status' => 'Room booked Successfuly');
					}
					if(count($response) < 0)
						$response=array('code' => 400, 'status' => 'Not able to book at this time.');
				}
				else
					$response=array('code' => 400, 'status' => 'No matching class available.');
			}
			else
			{
				return $response; 
			}
			return $response; 
			
		}
		catch(Exception $exception) {
			throw $exception;
		}
		return $response; 
	}
	
	
	public function listRoombookinghistory(){
		try{
			$rmbk = new Room_booking();
			$ret_arr = $rmbk->getBooking();
			if(count($ret_arr)>0) {
				$response=array('code' => 200, 'status' => 'Room booking list', 'list' => $ret_arr);
			}
			else
				$response=array('code' => 400, 'status' => 'No records found.');
			
			
		}
		catch(Exception $exception) {
			throw $exception;
		}
		return $response; 
	}
	
	private function validateInput($request) {
		
			
			// checking class room is empty
			if(!isset($request['title']) || empty($request['title']))
			{
			$response=array('code' => 400, 'status' => 'Class room input is missing.');
				return $response;
			}
			
			// checking start date is empty
			if(!isset($request['start_at']) || empty($request['start_at']))
			{
				$response=array('code' => 400, 'status' => 'input start time is missing.');
				return $response;
			}
			
			
			//checking end time is empty
			if(!isset($request['end_at']) || empty($request['end_at']))
			{
				$response=array('code' => 400, 'status' => 'input End time is missing.');
				return $response;
			}
			
			$date_now = date('Y-m-d');
			$date1_arr = explode(' ', $request['start_at']);
			$date2_arr = explode(' ', $request['end_at']);
			
			if($date_now > $date1_arr[0])
			{
				$response=array('code' => 400, 'status' => 'Dates should be as future dates.');
				return $response;
			}
			
			if($date1_arr[0] > $date2_arr[0])
			{
				$response=array('code' => 400, 'status' => 'End date should not be less than start date.');
				return $response;
			}
			
			// checking the room booking for same day.
			$date1=date_create($date1_arr);
			$date2=date_create($date2_arr); 
			$diff=date_diff($date1,$date2);
			if($diff->days != 0  )
			{
				$response=array('code' => 400, 'status' => 'Dates are not matching.');
				return $response;
			}
			
			// Checking start and end time should be greater and min 60 mins required.
			
		return true;
	}
	
	public function cancelRoom($request){
		try{
			$insert_id=0;
			$response = $this->validateCancellationInput($request);
			if($response === true) {
				$rmbk = new Room_booking();
				$response = $rmbk->cancelRoomBooking($request);
				if($response === true)
					$response=array('code' => 200, 'status' => 'Cancellation done successfully.');
				else if($response === fallse)
					$response=array('code' => 400, 'status' => 'Cancellation not possible at this time.');
				else 
					$response=array('code' => 400, 'status' => 'No matching room booking found.');
			}
			else
				return $response;
			
			
		}
		catch(Exception $exception) {
			throw $exception;
		}
		return $response; 
	}
	
	private function validateCancellationInput($request) {
			
			// checking class room is empty
			if(!isset($request['title']) || empty($request['title']))
			{
				$response=array('code' => 400, 'status' => 'Class room input is missing.');
				return $response;
			}
			
			// checking start date is empty
			if(!isset($request['start_at']) || empty($request['start_at']))
			{
				$response=array('code' => 400, 'status' => 'input start time is missing.');
				return $response;
			}
						
			// checking the room booking cancellation before 24 hours
			$date1=strtotime("now");
			$date2=strtotime($request['start_at']);
			$diff = ($date1 - $date2)/3600;
			if($diff < 24 )
			{
				$response=array('code' => 400, 'status' => 'Cancellation allowed only before 24 hours');
				return $response;
			}
			
		return true;
	}
}

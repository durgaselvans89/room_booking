<?php

include_once ROOT_PATH."common/dbConnect.php";
include_once ROOT_PATH."model/Room.php";

class Room_booking
{
    private $table = 'room_booking';
	private $column1 = 'room_id';
	private $column2 = 'start_at';
	private $column3 = 'end_at';
	private $column4 = 'booking_count';
	
	private $conn;
	public function Room_booking(){
		$dbCon=new dbConnect();
		$this->conn=$dbCon->getConnect();
	}
	
	public function insert($request) {
		try{
			$insert_id=0;
			$count =1;
			$query="INSERT INTO ".$this->table."(".$this->column1.",".$this->column2.",".$this->column3.",".$this->column4.") values(".$request['room_id'].",'".$request[$this->column2]."','".$request[$this->column3]."',".$count.")";
			$result=mysqli_query($this->conn,$query);
			if (!$result) {
				return 0;
			}
			$insert_id=mysqli_insert_id($this->conn); 
			$result->close();		
		}
		catch(Exception $exception) {
			throw $exception;
		}
		return $insert_id; 
	}
	
	public function update($request) {
		try{
			$insert_id=0;
			
			$query="update ".$this->table." set ".$this->column4."= ".$this->column4."+1 Where ".$this->column1."= ".$request[$this->column1]." and ".$this->column2."='".$request[$this->column2]."' and ".$this->column3."='".$request[$this->column3]."'";
			$result = mysqli_query($this->conn,$query); //echo $query;
			$insert_id=mysqli_affected_rows($this->conn); //echo 'aff'.$insert_id;
			$result->close();
		}
		catch(Exception $exception) {
			throw $exception;
		}
		return $insert_id; 
	}
	
	public function show($request) {
		try{
			$id_arr=array();	
			$query = "SELECT ".$this->column1.",".$this->column4." FROM ".$this->table." WHERE ".$this->column1." = '".$request['room_id']."' and ".$this->column2." = '".$request['start_at']."' and ".$this->column3." = '".$request['end_at']."'"; 
			$result=mysqli_query($this->conn,$query);
			if($result){
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
					$id_arr[] = $row;
				}
			}
		}
		catch(Exception $exception) {
			throw $exception;
		}
		return $id_arr; 
	}
	
	public function getBooking() {
		try{
			$id_arr=array();	
			$d=strtotime("now");
			$date1=date("Y-m-d h:i:s", $d);
			$d=strtotime("+7 day");
			$date2 = date("Y-m-d h:i:s", $d);
			$query = "SELECT ".$this->column2.",".$this->column3.",".$this->column4.", rooms.title FROM ".$this->table. " inner join rooms on rooms.id= ".$this->table.".room_id WHERE ".$this->column2." between '".$date1."' and '".$date2."'"; 
			$result=mysqli_query($this->conn,$query);
			if($result){
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
					$id_arr[] = $row;
				}
			}
		}
		catch(Exception $exception) {
			throw $exception;
		}
		return $id_arr; 
	}
	
	// Canceling the Class Room booking.
	
	public function cancelRoomBooking($request) {
		try{
			$id_arr=array();	
						
			$query = "SELECT ".$this->column2.",".$this->column3.",".$this->column4.", rooms.id FROM ".$this->table. " inner join rooms on rooms.id= ".$this->table.".room_id WHERE ".$this->column2." = '".$request['start_at']."' and rooms.title='".$request['title']."'"; //echo $query;
			$result=mysqli_query($this->conn,$query);
			if($result){
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
					$id_arr[] = $row;
				}
			}
			
			if(count($id_arr) > 0) {
				$query = "delete from ".$this->table. " WHERE ".$this->column1." = '".$id_arr[0]['id']."' and ".$this->column2." = '".$request['start_at']."'";// echo $query;
				$result=mysqli_query($this->conn,$query);
				return true;
			}
			else
				return false;
		}
		catch(Exception $exception) {
			throw $exception;
		}
		return $id_arr; 
	}
	
	
	
}

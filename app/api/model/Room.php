<?php
include_once ROOT_PATH."common/dbConnect.php";
class Room
{
    private $table = 'rooms';
	private $column1 = 'id';
	private $column2 = 'title';
	private $column3 = 'days';
	private $column4 = 'start_time';
	private $column5 = 'end_time';	
	private $column6 = 'min_hours';
	private $column7 = 'total_count';
	
	
	public function Room(){
		$dbCon=new dbConnect();
		$this->conn=$dbCon->getConnect();
			
	}
	
	public function insert($request) {
		try{
			$insert_id=0;
			$query="INSERT INTO ".$this->table."(".$this->column1.",".$this->column2.",".$this->column3.",".$this->column4.") values(?,?,?,?)";
			$sql = $this->conn->prepare($query);
			$count =1;
			$sql->bind_param("issi",$request[$this->column1],$request[$this->column2],$request[$this->column3],$count);
			$result = $sql->execute();
			if (!$result) {
				return 0;
			}
			$insert_id=$sql->insert_id; 
			$sql->close();		
		}
		catch(Exception $exception) {
			throw $exception;
		}
		return $insert_id; 
	}
	
	public function getByTitle($name='') {
		try{
			$id_arr=array();	
			$query = "SELECT ".$this->column1.",".$this->column3.",".$this->column4.",".$this->column5.",".$this->column6.",".$this->column7." FROM ".$this->table." WHERE ".$this->column2." = '".$name."'";
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
	
	public function update($request) {
		try{
			
		}
		catch(Exception $exception) {
			throw $exception;
		}
		return $insert_id; 
	}
	
	public function list($name=array()) {
		try{
			$id_arr=array();	
			$query = "SELECT ".$this->column1.",".$this->column3.",".$this->column4.",".$this->column5.",".$this->column6.",".$this->column7." FROM ".$this->table." WHERE ".$this->column2." = '".$name['title']."' and ".$this->column4." = '".$name['start_at']."'  and ".$this->column5." = '".$name['end_at']."'";
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
	
}

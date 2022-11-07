<?php
ini_set('display_errors',0); 
ini_set('default_charset', 'UTF-8');
date_default_timezone_set('Asia/Calcutta');
include_once ROOT_PATH."config/config.php";
class dbConnect 
{

	private $host;
	private	$username;
	private $password;
	private	$dbname;
	private	$dbCon=Null;
		
	public function dbConnect(){
		$this->host = HOST;
		$this->username = USER_NAME ;
		$this->password = PASSWORD;
		$this->dbname = DB_NAME;
	}
	public function getConnect()
	{
		try
		{	
			$this->dbCon=mysqli_connect($this->host,$this->username,$this->password,$this->dbname);
			mysqli_query($this->dbname);
			mysqli_query($this->dbCon,"SET NAMES utf8");
			mysqli_query($this->dbCon,"SET CHARACTER SET utf8");
			
			if($this->dbCon->connect_errno) {
				echo "Faied to connect Mysql"; exit;
			}
			
			return $this->dbCon;
			
		}
		catch(Exception $exception) {
			throw $exception;
		}
	}
	
	
}

	
?>

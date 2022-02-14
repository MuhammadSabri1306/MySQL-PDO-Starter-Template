<?php
/**
 * 
 */
class Database
{
	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $name = DB_NAME;
	private $db;
	private $stm;

	function __construct(){
		$db = "mysql:host=$this->host;dbname=$this->name";
		$option = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		try{
			$this->db = new PDO($db, $this->user, $this->pass, $option);
		}catch(PDOException $e){
			die($e->getMessage());
		}
	}

	function query($query){
		$this->stm =$this->db->prepare($query);
	}

	function bind($param, $value, $type = null){
		if(is_null($type)){
			$type = is_int($value) ? PDO::PARAM_INT
				: is_bool($value) ? PDO::PARAM_BOOL
				: is_null($value) ? PDO::PARAM_NULL
				: PDO::PARAM_STR;
		}

		$this->stm->bindValue($param, $value, $type);
	}

	function execute(){
		$this->stm->execute();
	}

	function resultSet(){
		$this->execute();
		return $this->stm->fetchAll(PDO::FETCH_ASSOC);
	}

	function resultRow(){
		$this->execute();
		return $this->stm->fetch(PDO::FETCH_ASSOC);
	}
}
<?php
/**
 * MySQL PDO Starter Template for PHP
 * @source https://github.com/MuhammadSabri1306/MySQL-PDO-Starter-Template
 * @author Muhammad Sabri <muhammadsabri1306@gmail.com>
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

	/**
	 * Setup the MySQL's query of for operation
	 * @param String $query
	 */
	function query($query){
		$this->stm = $this->db->prepare($query);
	}

	function bind($param, $value, $type = null){
		if(is_null($type)){
			$type = is_int($value) ? PDO::PARAM_INT
				: (is_bool($value) ? PDO::PARAM_BOOL
				: (is_null($value) ? PDO::PARAM_NULL
				: PDO::PARAM_STR));
		}

		$this->stm->bindValue($param, $value, $type);
	}

	/**
	 * Execute MySQL query after set
	 */
	function execute(){
		if($this->stm->execute()){
			return true;
		}
		return false;
	}

	/**
	 * Get result as table by operation, used with SELECT query
	 * @return (Multidimensional) Array
	 * @example $data = $db->resultSet(); echo $data[0]['field'];
	 */
	function resultSet(){
		$this->execute();
		return $this->stm->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Get result as a row by operation, used with SELECT query
	 * @return Array
	 * @example $data = $db->resultRow(); echo $data['field'];
	 */
	function resultRow(){
		$this->execute();
		return $this->stm->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Get number of rows by operation's result, used with SELECT query
	 * @return Number
	 */
	function numRows(){
		return count($this->resultSet());
	}

	/**
	 * Get id recently inserted, used after INSERT query
	 * @return String
	 */
	function lastInsertId(){
		return $this->db->lastInsertId();
	}

	function debugDumpParams(){
		return $this->stm->debugDumpParams();
	}
}

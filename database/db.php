<?php

class Database {
	public $connection;

	public function connection()
	{
		require("config.php");

		$this->connection = new PDO('mysql:host=' . $connData['host'] . ';dbname=' . $connData['dbname']. ';charset=' . $connData['charset'],
			$connData['username'], $connData['password']);

		return $this->connection;
	}

	public function makeInsert($query) 
	{
		$conn = $this->connection();
		$exec = $conn->exec($query);
		$conn = null;

		return $exec;
	}

	public function makeSelect($query)
	{
		$conn = $this->connection();
		$select = $conn->query($query);
		$conn = null;

		return $select;
	}
}
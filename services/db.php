<?php
class dbServices
{
	private $hostName;
	private $userName;
	private $password;
	private $dbName;
	private $dbcon;
	function __construct($hostName, $userName, $password, $dbName)
	{
		$this->hostName = $hostName;
		$this->userName = $userName;
		$this->password = $password;
		$this->dbName = $dbName;
	}
	function connect()
	{
		$dbcon = new mysqli($this->hostName, $this->userName, $this->password, $this->dbName);
		if ($dbcon->connect_error) {
			return false;
		}
		$this->dbcon = $dbcon;
		return $dbcon;
	}
	function close()
	{
		$this->dbcon->close();
	}
	function insert($tbName, $valuesArray, $fieldArray = null)
	{
		if ($fieldArray != null) {
			$fields = "(" . implode(',', $fieldArray) . ")";
		} else {
			$fields = '';
		}
		$values = implode(',', $valuesArray);
		$insertCmd = "INSERT INTO $tbName $fields VALUES ($values)";
		if ($this->dbcon->query($insertCmd) === TRUE) {
			return true;
		}
		return false;
	}
	function select($tbName, $fieldArray = null, $where = null)
	{
		if ($fieldArray != null) {
			$fields = implode(',', $fieldArray);
		} else {
			$fields = '*';
		}
		if ($where != null) {
			$where = "WHERE $where";
		} else {
			$where = '';
		}
		$selectCmd = "SELECT $fields FROM $tbName $where";
		$result = $this->dbcon->query($selectCmd);
		if ($result->num_rows > 0) {
			return $result;
		}
		return false;
	}
	function select_user($tbName, $fieldArray = null, $where = null)
	{
		if ($fieldArray != null) {
			$fields = implode(',', $fieldArray);
		} else {
			$fields = '*';
		}
		if ($where != null) {
			$where = "WHERE $tbName.email='$where'";
		} else {
			$where = '';
		}
		$selectCmd = "SELECT $fields FROM $tbName $where";
		$result = $this->dbcon->query($selectCmd);
		if ($result->num_rows > 0) {
			return $result;
		}
		return false;
	}
}
?>
<?php
class Database
{
    private $_port = '3306';
    private $_hostname = 'localhost';
    private $_username = '';
    private $_password = '';
    private $_database = '';

	protected $connection;

	public function __construct()
	{
		$this->connection = mysqli_connect(
		    $this->_hostname,
		    $this->_username,
		    $this->_password,
		    $this->_database,
		    $this->_port
        );

		if (mysqli_connect_errno($this->connection))
		{
			die("Failed to connect to MySQL: " . mysqli_connect_error());
		}
	}

	public function __destruct()
	{
		return mysqli_close($this->connection);
	}

	public function query($query)
	{
		return mysqli_query($this->connection, $query);
	}

	public function num_rows($result)
	{
		return mysqli_num_rows($result);
	}

	public function fetch_row($result)
	{
    	return mysqli_fetch_row($result);
	}

	public function fetch_array($result)
	{
		return mysqli_fetch_array($result);
	}

	public function fetch_assoc($result)
	{
		return mysqli_fetch_assoc($result);
	}

	public function fetch_object($result)
	{
		return mysqli_fetch_object($result);
	}

	public function last_id()
	{
		return mysqli_insert_id($this->connection);
	}

	public function escape($data)
	{
		return mysqli_real_escape_string($this->connection,$data);
	}
}
?>
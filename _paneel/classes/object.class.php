<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/db.class.php");

class Object
{
    private $_connection = null;

    public function __construct()
    {
        $this->_connection = new Database();
    }

    protected function getConnection()
    {
        return $this->_connection;
    }
}
?>
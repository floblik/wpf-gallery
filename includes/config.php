<?php

class DBConnector extends PDO
{
    
    private	$host = "host";
	private $user = "user";
	private $pass = "pass";
	private $dbname = "dbname";
    
    public function __construct()
    {
        
        $dsn     = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $options = array(
            
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        );
        
        parent::__construct($dsn, $this->user, $this->pass, $options);
        
    }
}


?>
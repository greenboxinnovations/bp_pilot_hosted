<?php

class Database 
{
    private $_db;
    static $_instance;

    private function __construct() {
        // $this->_db = new PDO('mysql:host=localhost;dbname=pump_master', 'pump_master_user', 'pump_master_user123!@#');

        $host_name  = Globals::DB_HOSTNAME;
        $user_name  = Globals::DB_USER_NAME;
        $password   = Globals::DB_PASSWORD;
        $db_name    = Globals::DB_NAME;

        try { 
            $this->_db = new PDO('mysql:host='.$host_name.';dbname='.$db_name.'', ''.$user_name.'', ''.$password.'');
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
        } catch (PDOException $error) {
           // echo 'Connection error: ' . $error->getMessage();
        }               
       
    }

    private function __clone(){}

    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    public function query($sql) {        
	    //return $this->_db->query($sql);
	    $this->stmt = $this->_db->prepare($sql);
    }
    public function bind($param, $value, $type = null){
	    if (is_null($type)) {
	        switch (true) {
	            case is_int($value):
	                $type = PDO::PARAM_INT;
	                break;
	            case is_bool($value):
	                $type = PDO::PARAM_BOOL;
	                break;
	            case is_null($value):
	                $type = PDO::PARAM_NULL;
	                break;
	            default:
	                $type = PDO::PARAM_STR;
	        }
	    }
    $this->stmt->bindValue($param, $value, $type);
    }

    public function execute(){
    	return $this->stmt->execute();
    }

    public function resultset(){
    	$this->execute();
    	return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount(){
        return $this->stmt->rowCount(PDO::FETCH_NUM);
    }

    public function beginTransaction(){
        return $this->dbh->beginTransaction();
    }

    public function endTransaction(){
        return $this->dbh->commit();
    }

    public function cancelTransaction(){
        return $this->dbh->rollBack();
    }

    public function debugDumpParams(){
        return $this->stmt->debugDumpParams();
    }


    public function lastInsertId(){
        return $this->_db->lastInsertId();
    }

}

?>
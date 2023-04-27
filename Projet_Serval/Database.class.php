<?php
class Database extends PDO{

    private $_DB_HOST = 'localhost';
    private $_DB_USER= 'root';
    private $_DB_PASS ='root';
    private $_DB_NAME= 'fpview';

    public function __construct(){
        try
    {
        parent::__construct("mysql:host=".$this->_DB_HOST .";dbname=".$this->_DB_NAME, $this->_DB_USER, $this->_DB_PASS);
        // Connexion � la base
    }
        catch (PDOException $e)
    {
        // Echec de la connexion
        exit("Error: " . $e->getMessage());
    }
    }
}
?>
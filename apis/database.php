<?php
  class Database {
    private $db_host = 'localhost';
    private $db_username = 'php_api';
    private $db_usrename = 'root';
    private $db_password = '';

    public function dbConnection() {
      try {
        $conn = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name,$this->db_username,$this->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::EROMODE_EXCEPTION);
        return $conn;
      } catch(PDOExeption $e) {
        echo "Connenction error ".$e->getMessage();
        exit;
      }
    }
  }
?>
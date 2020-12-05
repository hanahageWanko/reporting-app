<?php
  require __DIR__ . '/env.php';
  class Database
  {
      private $db_host;
      private $db_name = '';
      private $db_username = '';
      private $db_password = '';

      public function __construct()
      {
          $this->db_host = $_SERVER['DB_HOST'];
          $this->db_name = $_SERVER['DB_NAME'];
          $this->db_username = $_SERVER['DB_USERNAME'];
          $this->db_password = $_SERVER['DB_PASSWORD'];
      }

      public function dbConnection()
      {
          try {
              $conn = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name.';charset=utf8', $this->db_username, $this->db_password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              return $conn;
          } catch (PDOExeption $e) {
              echo "Connenction error ".$e->getMessage();
              exit;
          }
      }
  }

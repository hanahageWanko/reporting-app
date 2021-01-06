<?php
  require __DIR__ . '/../env.php';
  date_default_timezone_set('Asia/Tokyo');

  class Database
  {
      public static function dbConnection()
      {
          try {
              $db_host     = $_SERVER['DB_HOST'];
              $db_name     = $_SERVER['DB_NAME'];
              $db_username = $_SERVER['DB_USERNAME'];
              $db_password = $_SERVER['DB_PASSWORD'];
              $conn = new PDO('mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8', $db_username, $db_password);
              $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              return $conn;
          } catch (PDOExeption $e) {
              echo "Connenction error ".$e->getMessage();
              exit;
          }
      }

      public static function query($query, $params = array())
      {
          $stmt = self::dbConnection();
          $stmt = $stmt->prepare($query);
          $stmt->execute($params);
          // $data = $stmt->fetchAll();
          return $stmt;
      }
  }

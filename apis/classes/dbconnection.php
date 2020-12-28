<?php
require __DIR__ . '/database.php';
class CreateDBinstance {
  public function dbInstanceConnection()
  {
    $db_connection = new Database();
    return $db_connection->dbConnection();
  }

  public function setContent()
  {
    return json_decode(file_get_contents("php://input"));
  }
}

?>
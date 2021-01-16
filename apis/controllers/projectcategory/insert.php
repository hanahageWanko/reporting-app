<?php
  require_once __DIR__ . '/../../headers/insert.php';

  if ($_SERVER["REQUEST_METHOD"] != "POST"):
    echo json_encode(Validate::resultMessage(0, 405, 'Method Not Allowed'));
    return;
  endif;
  
  $data = json_decode(file_get_contents("php://input"));
  $name = $data->name;
  $table_project_category = $_SERVER['T_PROJECT_CATEGORY'];
  $query = "INSERT INTO `$table_project_category` (name) VALUES(:name)";

  if (!Validate::dataValidate($data)) {
      $fields = ['fields' => ['name']];
      echo json_encode(Validate::resultMessage(0, 422, 'Please Fill in all Required Fields!', $fields));
      return;
  }

  if (!Validate::moreThanStr($name, 100, 'The project category name can be up to 100 characters.')) {
      return;
  }

  $checkName = "SELECT `name` FROM $table_project_category WHERE `name`=:name";

try {
    $checkItem = Database::select($checkName, [':name' => $name]);
    if ($checkItem->rowCount() > 0) {
      echo json_encode(Validate::resultMessage(0, 422, 'This Name already in use!'));
      return;
    }
    
    $insert = Database::post($query, ['name'=>$name]);
    if ($insert) {
        echo json_encode(Validate::resultMessage(0, 201, 'Data Inserted Successfully'));
    }
} catch (PDOException $e) {
    echo json_encode(Validate::resultMessage(0, 500, $e->getMessage()));
    return;
}

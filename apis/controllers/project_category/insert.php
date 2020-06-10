<?php
  require_once __DIR__ . '/../headers/insert.php';

  if ($_SERVER["REQUEST_METHOD"] != "POST"):
    echo json_encode(Validate::resultMessage(0, 405, 'Method Not Allowed'));
    return;
  endif;
  
  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();
  $data = $db->setContent();

  if (!isset($data->name) || empty(trim($data->name))):
    $fields = ['fields' => ['name']];
    echo json_encode(Validate::resultMessage(0, 422, 'Please Fill in all Required Fields!', $fields));
    return;
  endif;

  $table_project_category = $_SERVER['T_PROJECT_CATEGORY'];
  $name = $data->name;
    $insert_query = "INSERT INTO `$table_project_category` (name) VALUES(:name)";
  
if (100 < strlen($name)):
    echo json_encode(Validate::resultMessage(0, 422, 'The project category name can be up to 400 characters.'));
    return;
endif;

try {
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $insert_stmt->execute();
    echo json_encode(Validate::resultMessage(0, 201, 'Data Inserted Successfully'));
} catch (PDOException $e) {
    echo json_encode(Validate::resultMessage(0, 500, $e->getMessage()));
    return;
}
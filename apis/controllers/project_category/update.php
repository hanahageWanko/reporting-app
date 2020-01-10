<?php
require_once __DIR__ . '/../../headers/update.php';

if ($_SERVER["REQUEST_METHOD"] != "PUT"):
  echo json_encode(Validate::resultMessage(0, 405, 'Method Not Allowed'));
  return;
endif;

$data = json_decode(file_get_contents("php://input"));

$table_project_category = $_SERVER['T_PROJECT_CATEGORY'];
$updateQuery = "UPDATE `$table_project_category` SET 
                  name = :name,
                  create_date = :create_date,
                  update_date = :update_date
                  WHERE id = :id";

$postId  = $data->id;
$getPost = "SELECT * FROM `$table_project_category` WHERE id=:id";
$checkName = "SELECT `name` FROM $table_project_category WHERE `name`=:name";

try {
    $checkItem = Database::select($getPost, [':id' => $postId]);
    if ($checkItem->rowCount() === 0) {
        echo json_encode(Validate::resultMessage(0, 400, 'Invlid ID'));
        return;
    }

    $row = $checkItem->fetch(PDO::FETCH_ASSOC);
    $postName       = Database::updateBindValue($row, $data, 'name');
    $postUpdateDate = date('Y-m-d-H-i');
    $postCreateDate = $row['create_date'];

    $checkName = Database::select($checkName, [':name' => $postName]);
    $checkName->fetch(PDO::FETCH_ASSOC);
    if ($checkName->rowCount() > 0 && $postName !== $row['name']) {
     echo json_encode(Validate::resultMessage(0, 422, 'This Name already in use!'));
     return;
   }
    
    if (!Validate::moreThanStr($postName, 100, 'The project category name can be up to 100 characters.')) {
        return;
    }

    $postItem = [
      ':id' => $postId,
      ':name' => $postName,
      ':update_date' => $postUpdateDate,
      ':create_date' => $postCreateDate
    ];
    Database::post($updateQuery, $postItem);
    echo json_encode(Validate::resultMessage(0, 200, 'Data updated successfully'));
} catch (PDOException $e) {
    echo json_encode(Validate::resultMessage(0, 500, $e->getMessage()));
}

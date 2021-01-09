<?php
require_once __DIR__ . '/../../headers/delete.php';

if ($_SERVER["REQUEST_METHOD"] != "DELETE"):
  echo json_encode(Validate::resultMessage(0, 405, 'Method Not Allowed'));
  return;
endif;

$data = json_decode(file_get_contents("php://input"));
$table_users = $_SERVER['T_USER'];
$table_study = $_SERVER['T_STUDY'];
$table_project_category['T_PROJECT_CATEGORY'];
$postId = $data->id;
$checkPost = "SELECT * FROM $table_users WHERE id=:id";
$deletePost = "DELETE $table_users" . "," . "$table_study 
                FROM $table_users 
                LEFT JOIN $table_study 
                ON $table_users.id = $table_study.user_id 
                WHERE $table_users.id=:id";

// 削除対象が存在するかどうかを検索
if (!isset($postId) || empty($postId)) {
  echo json_encode(Validate::resultMessage(0, 400, 'Invlid ID'));
  return;
}
  
try {
  $checkItem = Database::select($checkPost, [':id' => $postId]);
  if ($checkItem->rowCount() === 0) {
      echo json_encode(Validate::resultMessage(0, 400, 'Invlid ID'));
      return;
  }
  Database::post($deletePost, [':id' => $postId]);
  echo json_encode(Validate::resultMessage(0, 200, 'Post Deleted Successfuly'));
} catch (PDOException $e) {
  echo json_encode(Validate::resultMessage(0, 500, $e->getMessage()));
}


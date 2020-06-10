<?php
require_once __DIR__ . '/../headers/delete.php';

if ($_SERVER["REQUEST_METHOD"] != "DELETE"):
  echo json_encode(Validate::resultMessage(0, 405, 'Method Not Allowed'));
  return;
endif;

$db = new CreateDBinstance();
$conn = $db->dbInstanceConnection();
$data = $db->setContent();

$table_users = $_SERVER['T_USER'];
$table_study = $_SERVER['T_STUDY'];
$table_project_category['T_PROJECT_CATEGORY'];

$msg['message'] = '';

$post_id = $data->id;
$check_post = "SELECT * FROM $table_users WHERE id=:post_id";
$delete_post = "DELETE $table_users" . "," . "$table_study 
                FROM $table_users 
                LEFT JOIN $table_study 
                ON $table_users.id = $table_study.user_id 
                WHERE $table_users.id=:post_id";

// 削除対象が存在するかどうかを検索
if (isset($post_id)) {
  $check_post_stmt = $conn->prepare($check_post);
  $check_post_stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
  $check_post_stmt->execute();
  if ($check_post_stmt->rowCount() > 0) {
    $delete_post_stmt = $conn->prepare($delete_post);
    $delete_post_stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $msg['message'] = $delete_post_stmt->execute() ? Validate::resultMessage(0, 200, 'Post Deleted Successfuly') : Validate::resultMessage(0, 400, 'Post Not Deleted');
  } else {
    $msg['message'] = Validate::resultMessage(0, 400, 'Invlid ID');
  }
  echo json_encode($msg);
}

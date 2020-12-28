<?php
require_once __DIR__ . '/../headers/update.php';
require_once __DIR__ . '/../functions.php';

$db = new CreateDBinstance();
$conn = $db->dbInstanceConnection();
$data = $db->setContent();

$msg['message'] = '';

$table_project_category = $_SERVER['T_PROJECT_CATEGORY'];
$update_query = "UPDATE `$table_project_category` SET 
                  name = :name, 
                  delete_flg = :delete_flg,
                  create_date = :create_date,
                  update_date = :update_date
                  WHERE id = :id";

// 変更対象が存在するかどうかを検索
if (isset($data->id)) {
  $post_id  = $data->id;
  $get_post = "SELECT * FROM `$table_project_category` WHERE id=:post_id";
  $get_stmt = $conn->prepare($get_post);
  $get_stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
  $get_stmt->execute();
  if ($get_stmt->rowCount() > 0) {
    $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
    $post_name       = updateBindValue($row, $data, 'name');
    $post_delete_flg = updateBindValue($row, $data, 'delete_flg');
    $update_date     = updateBindValue($row, $data, 'update_date');
    $post_update_date     = date('Y-m-d-H-i');
    $post_create_date    = $row['create_date'];

    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindValue(':name',        htmlspecialchars(strip_tags($post_name)), PDO::PARAM_STR);
    $update_stmt->bindValue(':delete_flg',  $post_delete_flg, PDO::PARAM_INT);
    $update_stmt->bindValue(':update_date', $post_update_date, PDO::PARAM_STR);
    $update_stmt->bindValue(':create_date', $post_create_date, PDO::PARAM_STR);
    $update_stmt->bindValue(':id',          $post_id, PDO::PARAM_INT);
  }
  $msg['message'] = $update_stmt->execute() ? resultMessage(0, 200, 'Data updated successfully') : resultMessage(0, 400, 'Data not updated');
} else {
  $msg['message'] = resultMessage(0, 400, 'Invlid ID');
}

echo json_encode($msg);

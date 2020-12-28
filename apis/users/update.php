<?php
require_once __DIR__ . '/../headers/update.php';
require_once __DIR__ . '/../functions.php';

$db = new CreateDBinstance();
$conn = $db->dbInstanceConnection();
$data = $db->setContent();

$msg['message'] = '';
$table_users = $_SERVER['T_USER'];
$update_query = "UPDATE `$table_users` SET 
                  user_name = :user_name, 
                  email = :email, 
                  password = :password,
                  delete_flg = :delete_flg,
                  last_login_time = :last_login_time,
                  update_date = :update_date,
                  create_date = :create_date
                  WHERE id = :id";

// 変更対象が存在するかどうかを検索
if (isset($data->id)) {
  $post_id  = $data->id;
  $get_post = "SELECT * FROM `$table_users` WHERE id=:post_id";
  $get_stmt = $conn->prepare($get_post);
  $get_stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
  $get_stmt->execute();
  if ($get_stmt->rowCount() > 0) {
    $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
    $post_user_name       = updateBindValue($row, $data, 'user_name');
    $post_email           = updateBindValue($row, $data, 'email');
    $post_password        = updateBindValue($row, $data, 'password');
    $post_delete_flg      = updateBindValue($row, $data, 'delete_flg');
    $post_last_login_time = updateBindValue($row, $data, 'last_login_time');
    $post_update_date     = date('Y-m-d-H-i');
    $post_create_date     = $row['create_date'];

    $update_stmt = $conn->prepare($update_query);
    $options = [ 'cost' => 10 ];
    $update_stmt->bindValue(':user_name',       htmlspecialchars(strip_tags($post_user_name)), PDO::PARAM_STR);
    $update_stmt->bindValue(':email',           htmlspecialchars(strip_tags($post_email)), PDO::PARAM_STR);
    $update_stmt->bindValue(':password',        password_hash( $post_password, PASSWORD_DEFAULT, $options), PDO::PARAM_STR);
    $update_stmt->bindValue(':delete_flg',      $post_delete_flg, PDO::PARAM_INT);
    $update_stmt->bindValue(':last_login_time', $post_last_login_time, PDO::PARAM_STR);
    $update_stmt->bindValue(':update_date',     $post_update_date, PDO::PARAM_STR);
    $update_stmt->bindValue(':create_date',     $post_create_date, PDO::PARAM_STR);
    $update_stmt->bindValue(':id',              $post_id, PDO::PARAM_INT);
  }
  $msg['message'] = $update_stmt->execute() ? resultMessage(0, 200, 'Data updated successfully') : resultMessage(0, 400, 'Data not updated');
} else {
  $msg['message'] = resultMessage(0, 400, 'Invlid ID');
}

echo  json_encode($msg);

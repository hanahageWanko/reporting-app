<?php
require_once __DIR__ . '/../headers/update.php';

$db = new CreateDBinstance();
$conn = $db->dbInstanceConnection();
$data = $db->setContent();

$msg['message'] = '';
$message = new Messages();

$table_users = $_SERVER['T_USER'];

$user_name   = $data->user_name;
$email       = $data->email;
$password    = $data->password;
$delete_flg  = $data->delete_flg;
$update_date = date('Y-m-d-H-i');
$last_login_time = $data->last_login_time;
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
  if($get_stmt->rowCount() > 0) {
    $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
    var_dump(strval($user_name));
    $post_user_name       = isset($user_name)       ? $user_name       : $row['user_name'];
    $post_email           = isset($email)           ? $email           : $row['email'];
    $post_password        = isset($password)        ? $password        : $row['password'];
    $post_delete_flg      = isset($delete_flg)      ? $delete_flg      : $row['delete_flg'];
    $post_last_login_time = isset($last_login_time) ? $last_login_time : $row['last_login_time'];
    $post_update_date     = $update_date;
    $post_create_date     = $row['create_date']; 

    $update_stmt = $conn->prepare($update_query);

    $update_stmt->bindValue(':user_name',       htmlspecialchars(strip_tags($post_user_name)),PDO::PARAM_STR);
    $update_stmt->bindValue(':email',           htmlspecialchars(strip_tags($post_email)),PDO::PARAM_STR);
    $update_stmt->bindValue(':password',        $post_password,PDO::PARAM_STR);
    $update_stmt->bindValue(':delete_flg',      $post_delete_flg, PDO::PARAM_INT);
    $update_stmt->bindValue(':last_login_time', $post_last_login_time, PDO::PARAM_STR);
    $update_stmt->bindValue(':update_date',     $post_update_date, PDO::PARAM_STR);
    $update_stmt->bindValue(':create_date',     $post_create_date, PDO::PARAM_STR);
    $update_stmt->bindValue(':id',              $post_id, PDO::PARAM_INT);
  }
  $msg['message'] = $update_stmt->execute() ? $message->Success() : $message->Failure(); 

} else {
  $msg['message'] = $message->InvlidId();
}

echo  json_encode($msg);

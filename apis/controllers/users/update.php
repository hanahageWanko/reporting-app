<?php
require_once __DIR__ . '/../../headers/update.php';

if ($_SERVER["REQUEST_METHOD"] != "PUT"):
  echo json_encode(Validate::resultMessage(0, 405, 'Method Not Allowed'));
  return;
endif;

$data = json_decode(file_get_contents("php://input"));

$table_users = $_SERVER['T_USER'];
$updateQuery = "UPDATE `$table_users`
                SET 
                  user_name = :user_name, 
                  email = :email, 
                  password = :password,
                  last_login_time = :last_login_time,
                  update_date = :update_date,
                  create_date = :create_date
                WHERE id = :id";

// 変更対象が存在するかどうかを検索
if (!isset($data->id) || empty($data->id)) {
    echo json_encode(Validate::resultMessage(0, 400, 'Invlid ID')) ;
    return;
}
try {
    $postId  = $data->id;
    $getPostQuery = "SELECT * FROM `$table_users` WHERE id=:postId";
    $getPost = Database::select($getPostQuery, [':postId' => $postId]);
    
    if ($getPost->rowCount() === 0) {
        return;
    }

    $user_name   = trim($data->user_name);
    $email       = trim($data->email);
    $password    = trim($data->password);
  
    if (!Validate::mailFormat($email, 'Invalid Email Address!')) {
        return;
    }

    if (!Validate::lessThanStr($password, 8, 'Your password must be at least 8 characters long!')) {
        return;
    }

    if (!Validate::lessThanStr($user_name, 3, 'Your name must be at least 3 characters long!')) {
        return;
    }

    $checkEmail = "SELECT `email` FROM $table_users WHERE `email`=:email";
    $checkItem = Database::select($checkEmail, [':email' => $email]);
    $row = $getPost->fetch(PDO::FETCH_ASSOC);
    if ($checkItem->rowCount() > 0 && $row['email'] !== $email) {
        echo json_encode(Validate::resultMessage(0, 422, 'This E-mail already in use!'));
        return;
    }

    $postUserName       = Database::updateBindValue($row, $data, 'user_name');
    $postEmail          = Database::updateBindValue($row, $data, 'email');
    $postPassword       = Database::updateBindValue($row, $data, 'password');
    $postLastLoginTime  = date('Y-m-d-H-i');
    $postUpdateDate     = date('Y-m-d-H-i');
    $postCreateDate     = $row['create_date'];
    
    $postItem = [
      ':id' => $postId,
      ':user_name' => $postUserName,
      ':email' => $postEmail,
      ':password' => password_hash($postPassword, PASSWORD_DEFAULT),
      ':last_login_time' => $postLastLoginTime,
      ':update_date' => $postUpdateDate,
      ':create_date' => $postCreateDate
    ];
    Database::post($updateQuery, $postItem);
    echo json_encode(Validate::resultMessage(0, 200, 'Data updated successfully'));
} catch (PDOException $e) {
    echo json_encode(Validate::resultMessage(0, 500, $e->getMessage()));
}

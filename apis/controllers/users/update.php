<?php
require_once __DIR__ . '/../headers/update.php';
require_once __DIR__ . '/../classes/validate.php';

if ($_SERVER["REQUEST_METHOD"] != "PUT"):
  echo json_encode(validate\Validate::resultMessage(0, 405, 'Method Not Allowed'));
  return;
endif;

$db = new CreateDBinstance();
$conn = $db->dbInstanceConnection();
$data = $db->setContent();

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
  try {
    $post_id  = $data->id;
    $get_post = "SELECT * FROM `$table_users` WHERE id=:post_id";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $get_stmt->execute();
    if ($get_stmt->rowCount() > 0) {

      $user_name   = trim($data->user_name);
      $email       = trim($data->email);
      $password    = trim($data->password);
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)):
        echo json_encode(validate\Validate::resultMessage(0, 422, 'Invalid Email Address!'));
        return;
      endif;

      if (strlen($password) < 8):
          echo json_encode(validate\Validate::resultMessage(0, 422, 'Your password must be at least 8 characters long!'));
          return;
      endif;

      if (strlen($user_name) < 3):
          echo json_encode(validate\Validate::resultMessage(0, 422, 'Your name must be at least 3 characters long!'));
          return;
      endif;

      $check_email = "SELECT `email` FROM $table_users WHERE `email`=:email";
      $check_email_stmt = $conn->prepare($check_email);
      $check_email_stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $check_email_stmt->execute();

      if ($check_email_stmt->rowCount()):
        echo json_encode(validate\Validate::resultMessage(0, 422, 'This E-mail already in use!'));
        return;
      else:
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        $post_user_name       = updateBindValue($row, $data, 'user_name');
        $post_email           = updateBindValue($row, $data, 'email');
        $post_password        = updateBindValue($row, $data, 'password');
        $post_delete_flg      = updateBindValue($row, $data, 'delete_flg');
        $post_last_login_time = updateBindValue($row, $data, 'last_login_time');
        $post_update_date     = date('Y-m-d-H-i');
        $post_create_date     = $row['create_date'];

        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindValue(':user_name',       htmlspecialchars(strip_tags($post_user_name)), PDO::PARAM_STR);
        $update_stmt->bindValue(':email',           htmlspecialchars(strip_tags($post_email)), PDO::PARAM_STR);
        $update_stmt->bindValue(':password',        password_hash( $post_password, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $update_stmt->bindValue(':delete_flg',      $post_delete_flg, PDO::PARAM_INT);
        $update_stmt->bindValue(':last_login_time', $post_last_login_time, PDO::PARAM_STR);
        $update_stmt->bindValue(':update_date',     $post_update_date, PDO::PARAM_STR);
        $update_stmt->bindValue(':create_date',     $post_create_date, PDO::PARAM_STR);
        $update_stmt->bindValue(':id',              $post_id, PDO::PARAM_INT);
        $update_stmt->execute();
        echo json_encode(validate\Validate::resultMessage(0, 200, 'Data updated successfully'));
      endif;
    }
  } catch (PDOException $e) {
    echo json_encode(validate\Validate::resultMessage(0, 500, $e->getMessage()));
  }
} else {
  echo json_encode(validate\Validate::resultMessage(0, 400, 'Invlid ID')) ;
}


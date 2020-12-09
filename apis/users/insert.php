<?php
  require_once __DIR__ . '/../headers/insert.php';
  
  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();
  $data = $db->setContent();

  $msg['message'] = '';
  $message = new Messages();

  $table_users = $_SERVER['T_USER'];
  $user_name   = $data->user_name;
  $email       = $data->email;
  $password    = $data->password;
  $insert_query = "INSERT INTO `$table_users` (user_name, email, password) VALUES(:user_name, :email, :password)";

if (isset($user_name) && isset($email) && isset($password)) {
    if (!empty($user_name) && !empty($email) && !empty($password)) {
        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':user_name', htmlspecialchars(strip_tags($user_name)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':email',     htmlspecialchars(strip_tags($email)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':password',  htmlspecialchars(strip_tags($password)), PDO::PARAM_STR);
      
        $msg['message'] = $insert_stmt->execute() ? $message->Success() : $message->Failure();
    } else {
        $msg['message'] = $message->EmptyField();
    }
} else {
    $msg['message'] = $message->MissingField();
}

echo json_encode($msg);

<?php
  require_once __DIR__ . '/../../headers/insert.php';

Session::redirect(isset($_SESSION["login"]), '/login');

  if (!Validate::requestType("POST")) {
      exit();
  }

  $data = json_decode(file_get_contents("php://input"));
  
  if (!Validate::dataValidate($data)) {
      $fields = ['fields' => ['user_name', 'email', 'password']];
      echo json_encode(Validate::resultMessage(0, 422, 'Please Fill in all Required Fields!', $fields));
  }

  $table_users = $_SERVER['T_USER'];
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
  
  try {
      $checkEmail = "SELECT `email` FROM $table_users WHERE `email`=:email";
      $checkItem = Database::select($checkEmail, [':email' => $email]);

      if ($checkItem->rowCount() > 0) {
          echo json_encode(Validate::resultMessage(0, 422, 'This E-mail already in use!'));
          return;
      } else {
          $insert_query = "INSERT INTO `$table_users` (user_name, email, password) VALUES(:user_name, :email, :password)";
          $insert_stmt = Database::dbConnection()->prepare($insert_query);
          $insert_stmt->bindValue(':user_name', strip_tags($user_name), PDO::PARAM_STR);
          $insert_stmt->bindValue(':email', strip_tags($email), PDO::PARAM_STR);
          $insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
          $insert_stmt->execute();
          echo json_encode(Validate::resultMessage(1, 201, 'You have successfully registered.'));
      }
  } catch (PDOException $e) {
      echo json_encode(Validate::resultMessage(0, 500, $e->getMessage()));
      return;
  }

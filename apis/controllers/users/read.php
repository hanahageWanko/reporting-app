<?php
  require_once __DIR__ . '/../../headers/read.php';

  Session::redirect(isset($_SESSION["login"]), '/login');

  if (!Validate::requestType("GET")) {
      exit();
  }
  
  $table_users = $_SERVER['T_USER'];

  if (isset($_GET['id'])) {
      $postId = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
      'options' => [
        'default' => 'all_posts',
        'min_range' => 1
      ]
    ]);
  } else {
      $postId = 'all_posts';
  }

  $sql = is_numeric($postId)
         ? "SELECT * FROM `$table_users` WHERE id ='$postId'"
         : "SELECT * FROM `$table_users`";
  $fetchItem = ['id', 'email', 'user_name', 'last_login_time', 'create_date', 'update_date'];
  $stmt = Database::fetch($sql, $fetchItem);
  if ($stmt) {
      echo json_encode($stmt);
  } else {
      echo json_encode(Validate::resultMessage(0, 400, 'No post found'));
  }

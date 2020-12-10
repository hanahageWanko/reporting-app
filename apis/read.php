<?php
  // SET HEADER
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: GET");
  header("Access-Control-Allow-Credentials: true");
  header("Content-Type: application/json; charset=UTF-8");

  require 'database.php';
  $db_connection = new Database();
  $conn = $db_connection->dbConnection();
  $table_users = $_SERVER['T_USER'];

  if(isset($_GET['id'])) {
    $post_id = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
      'options' => [
        'default' => 'all_posts',
        'min_range' => 1
      ]
    ]);
  } else {
    $_post_id = 'all_posts';
  }

  $sql = is_numeric($post_id)
         ? "SELECT * FROM `$table_users` WHERE id ='$post_id'"
         : "SELECT * FROM `$table_users`";

  $stmt = $conn->prepare($sql);

  $stmt->execute();

  if($stmt->rowCount() > 0) {
    $post_array = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $post_data = [
        'id' => $row['id'],
        'email' => $row['email'], 
        'user_name' => $row['user_name'],
        'login_time' => $row['login_time'],
        'create_date' => $row['create_date'],
        'update_date' => $row['update_date']
      ];
      array_push($post_array, $post_data);
    }
    echo json_encode($post_array);
  } else {
    echo json_encode(['message' => 'No post found']);
  }

?>
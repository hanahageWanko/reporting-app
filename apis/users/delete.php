<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: DELETE");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  require __DIR__ . '/../database.php';
  $db_connection = new Database();
  $conn = $db_connection->dbConnection();
  $table_users = $_SERVER['T_USER'];

  $data = json_decode(file_get_contents("php://input"));
  $msg['message'] = '';
  $post_id = $data->id;
  $check_post = "SELECT * FROM `$table_users` WHERE id=:post_id";
  $delete_post = "DELETE FROM `$table_users` WHERE id=:post_id";

  // 削除対象が存在するかどうかを検索
  if(isset($post_id)) {
    $check_post_stmt = $conn->prepare($check_post);
    $check_post_stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $check_post_stmt->execute();
    if($check_post_stmt->rowCount() > 0){
      $delete_post_stmt = $conn->prepare($delete_post);
      $delete_post_stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
      $msg['message'] = $delete_post_stmt->execute() ? 'Post Deleted Successfully' : 'Post Not Deleted';
    } else {
      $msg['message'] = 'Invlid ID';
    }
    echo json_encode($msg);
  } 

?>
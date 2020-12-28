<?php
  require_once __DIR__ . '/../headers/delete.php';
  require_once __DIR__ . '/../classes/validate.php';

  if ($_SERVER["REQUEST_METHOD"] != "DELETE"):
    echo json_encode(validate\Validate::resultMessage(0, 405, 'Method Not Allowed'));
    return;
  endif;
  
  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();
  $data = $db->setContent();

  $table_project_category = $_SERVER['T_PROJECT_CATEGORY'];

  $msg['message'] = '';

  $post_id = $data->id;
  $check_post = "SELECT * FROM `$table_project_category` WHERE id=:post_id";
  $delete_post = "DELETE FROM `$table_project_category` WHERE id=:post_id";

  // 削除対象が存在するかどうかを検索
  if(isset($post_id)) {
    $check_post_stmt = $conn->prepare($check_post);
    $check_post_stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $check_post_stmt->execute();
    if($check_post_stmt->rowCount() > 0){
      $delete_post_stmt = $conn->prepare($delete_post);
      $delete_post_stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
      $msg['message'] = $delete_post_stmt->execute() ? validate\Validate::resultMessage(0, 200, 'Post Deleted Successfuly') : validate\Validate::resultMessage(0, 400, 'Post Not Deleted');
    } else {
      $msg['message'] = validate\Validate::resultMessage(0, 400, 'Invlid ID');
    }
    echo json_encode($msg);
  } 

?>
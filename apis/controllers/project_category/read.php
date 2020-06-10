<?php
  require_once __DIR__ . '/../../headers/read.php';

  if ($_SERVER["REQUEST_METHOD"] != "GET"):
    echo json_encode(Validate::resultMessage(0, 405, 'Method Not Allowed'));
    return;
  endif;

  $table_project_category = $_SERVER['T_PROJECT_CATEGORY'];
  if(isset($_GET['id'])) {
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
         ? "SELECT * FROM `$table_project_category` WHERE id ='$postId'"
         : "SELECT * FROM `$table_project_category`";

  $fetchItem = ['id', 'name', 'create_date', 'update_date'];

  $stmt = Database::fetch($sql, $fetchItem);
  if($stmt) {
    echo json_encode($stmt);
  } else {
    echo json_encode(Validate::resultMessage(0, 400, 'No post found'));
  }

?>